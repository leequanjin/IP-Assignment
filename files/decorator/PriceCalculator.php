<?php

class BasePrice implements PriceCalculator {
    public function calculatePrice(float $price, int $qty): float {
        return $price * $qty;
    }
}

