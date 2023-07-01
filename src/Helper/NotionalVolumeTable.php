<?php

declare(strict_types=1);

namespace Kobens\Gemini\Helper;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume\ResponseInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;

final class NotionalVolumeTable
{
    public static function getTable(ResponseInterface $response, OutputInterface $output): Table
    {
        $table = new Table($output);
        $table->addRows([
            ['Date', $response->getDate()],
            ['Last Updated MS', $response->getLastUpdatedMs()],
            [new TableCell('', ['colspan' => 2])],
            ['API Maker Fee BPS', $response->getApiMakerFeeBPS()],
            ['API Taker Fee BPS', $response->getApiTakerFeeBPS()],
            [new TableCell('', ['colspan' => 2])],
            ['Block Maker Fee BPS', $response->getBlockMakerFeeBPS()],
            ['Block Taker Fee BPS', $response->getBlockTakerFeeBPS()],
            ['Fix Maker Fee BPS', $response->getFixMakerFeeBPS()],
            ['Fix Taker Fee BPS', $response->getFixTakerFeeBPS()],
            ['Web Maker Fee BPS', $response->getWebMakerFeeBPS()],
            ['Web Taker Fee BPS', $response->getWebTakerFeeBPS()],
            [new TableCell('', ['colspan' => 2])],
            ['Notional 30 Day Volume', $response->getNotional30DayVolume()],
            [new TableCell('', ['colspan' => 2])],
            [new TableCell('Notional 1 Day Volume', ['colspan' => 2])],
        ]);
        /** @var \Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume\OneDayVolume $day */
        foreach ($response->getNotional1DayVolume() as $day) {
            $table->addRow([$day->getDate(), self::formatNotionalVolume($day->getNotionalVolume())]);
        }
        return $table;
    }

    private static function formatNotionalVolume(string $volume): string
    {
        if (strpos($volume, '.') === false) {
            $volume .= '.';
        }
        $volume = \explode('.', $volume);
        $volume[0] = str_pad($volume[0], 4, ' ', STR_PAD_LEFT);
        $volume[1] = str_pad($volume[1], 10, '0', STR_PAD_RIGHT);
        return implode('.', $volume);
    }
}
