<!-- Author     : Christopher Yap Jian Xing -->

<?php
interface PriceCalculator {
    public function calculatePrice(float $price, int $qty): float;
}


