<?php
require_once 'controllers/CartController.php';
?>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th scope="col" class="text-center">Product Title</th>
            <th scope="col" class="text-center">Product Image</th>
            <th scope="col" class="text-center">Quantity</th>
            <th scope="col" class="text-center">Product Price</th>
            <th scope="col" class="text-center">Total Price</th>
            <th scope="col" class="text-center">Remove</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $grandTotal = 0;
        $conversionRate = 1;
        $currencyConverter = new CurrencyConverter();
        $selectedCurrency = $_GET['currency'] ?? 'MYR';
        if ($selectedCurrency !== 'MYR') {
            $conversionRate = $currencyConverter->getConversionRate($selectedCurrency);
        }
        ?>
        <?php foreach ($cartProductDetails as $product): ?>
            <?php
            $id = $product['id'];
            $title = $product['title'];
            $image = $product['image'];
            $imageSrc = 'uploads/' . $image;
            $qty = (int) $product['qty'];
            $price = (float) $product['price'];

            $convertedPrice = $price * $conversionRate;

            $calculator = new BasePrice();
            if (isset($_GET['voucher']) && $_GET['voucher'] == 'DISCOUNT10') {
                $calculator = new DiscountDecorator($calculator, 0.10); // 10% discount
            }

            $subTotal = $calculator->calculatePrice($convertedPrice, $qty);
            $grandTotal += $subTotal;
            ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($title); ?></td>
                <td class="text-center"><img src="<?php echo $imageSrc; ?>" width="100" height="100"></td>
                <td class="text-center"><?php echo $qty; ?></td>
                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($convertedPrice, 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($subTotal, 2); ?></td>
                <td class="text-center">
                    <a href="userIndex.php?module=cart&action=delete&product_id=<?php echo urlencode($id); ?>&currency=<?php echo urlencode($selectedCurrency); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Remove <?php echo $title ?> from cart?')">Delete</a>
                </td>
                <td><input type="text" name="voucher" placeholder="Enter Voucher Code"></td>
            </tr>
        <?php endforeach; ?>
        <?php if (isset($_GET['voucher']) && $_GET['voucher'] == 'DISCOUNT10'): ?>
        <div class="alert alert-success text-center fw-bold">
            🎉 10% Discount Applied with Voucher: DISCOUNT10
        </div>
    <?php endif; ?>


    <tr>
        <td colspan="4" class="text-end fw-bold">Grand Total:</td>
        <td class="text-center fw-bold"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($grandTotal, 2); ?></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="6" class="text-end">
            <a href="#" class="btn btn-success">Proceed to Payment</a>
        </td>
    </tr>
</tbody>
</table>

