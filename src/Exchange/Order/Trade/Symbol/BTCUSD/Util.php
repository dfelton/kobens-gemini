 <?php

 namespace Kobens\Gemini\Exchange\Order\Trade\Symbol\BTCUSD;

 use Kobens\Gemini\Api\Rest\DataModel\Trade;

 final class Util
 {
     /**
      * @var Trade
      */
     private $trade;

     public function __construct(Trade $trade)
     {
        $this->trade = $trade;
     }

     /**
      * Example: '0.00100'
      *
      * @return string
      */
     public function getFeePercent(): string
     {
         $usdAmount = \bcmul($this->trade->getPrice(), $this->trade->getAmount(), 10);

         // Gemini's Max Fee Precision is  one one thousandth of a percent
         $feePercent = \bcdiv($this->trade->getFeeAmount(), $usdAmount, 5);

         // Verify exact precision occurred
         if (\rtrim(\bcmul($usdAmount, $feePercent, 14), '0') !== $this->trade->getFeeAmount()) {
             throw \LogicException('Unable to determine exact fee percent from Trade');
         }

         return $feePercent;
     }

     /**
      * Example: '0.000000123467800'
      *
      * @return string
      */
     public function getCostBasisPerSubunit(): string
     {
        $baseCostPerSubunit = \bcmul($this->trade->getPrice(), '0.00000001', 10);
        $feePerSubunit = \bcmul($baseCostPerSubunit, $this->getFeePercent(), 15);
        return \bcadd($baseCostPerSubunit, $feePerSubunit, 14);
     }
 }
