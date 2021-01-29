<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade;

use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;

final class CalculateCompletedProfits
{
    /**
     * FIXME: Need to use pair information to determine where fee is coming from.
     * FIXME: grab amount from meta array once available (for buy/sells at varying prices,for example added additional onto position at lower price after initial buy)
     * FIXME: Pull transaction data from logs to accumulate fees from individual transactions of an order, not assume hard coded BPS for entire order
     *
     * @param Trade $trade
     * @return array
     */
    public static function get(Trade $trade): array
    {
        $meta = json_decode($trade->getMeta());
        $pair = Pair::getInstance($trade->getSymbol());

        $costBasis = self::getCostBasis($trade);
        $proceeds = Multiply::getResult($trade->getSellAmount(), $meta->sell_price);

        // Assuming 10BPS (lowest volume tier pricing if all "maker or cancel" orders)
        $feeBuy = Multiply::getResult($costBasis, '0.001');
        $feeSell = Multiply::getResult($proceeds, '0.001');

        $profitQuote = Subtract::getResult(
            Subtract::getResult(
                Subtract::getResult($proceeds, $feeSell),
                $feeBuy
            ),
            $costBasis
        );

        return [
            $pair->getQuote()->getSymbol() => $profitQuote,
            $pair->getBase()->getSymbol() => Subtract::getResult($trade->getBuyAmount(), $trade->getSellAmount()),
        ];
    }

    /**
     * While we could use the `buy_price` from the meta data to get a more accurate cost basis, this has an unintended side effect
     * if the savings from that cheaper buy price was used early from manual decisions outside the bot. If
     * this happens, we can run into a scenario where we assume too much profits, and too low of a
     * buffer of reserves could cause the upcoming order to hit insufficient funds.
     *
     * Therefore for the purposes of calculating profits, for re-investment funds, use the
     * original buy price target. This leans on the conservative end for caution.
     *
     *
     * @param Trade $trade
     */
    private static function getCostBasis(Trade $trade)
    {
        return Multiply::getResult($trade->getBuyAmount(), $trade->getBuyPrice());
    }
}
