<!-- Author     : Christopher Yap Jian Xing -->

<?php
require_once 'BasePrice.php';

class DiscountDecorator implements PriceCalculator {
    protected $calculator;
    protected $discountRate;

    public function __construct(PriceCalculator $calculator, float $discountRate) {
        $this->calculator = $calculator;
        $this->discountRate = $discountRate;
    }

    public function calculatePrice(float $price, int $qty): float {
        $originalTotal = $this->calculator->calculatePrice($price, $qty);
        $discountAmount = $originalTotal * $this->discountRate;
        return $originalTotal - $discountAmount;
    }
}
