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

            $subTotal = $qty * $convertedPrice;
            $grandTotal += $subTotal;
            ?>
            <tr>
                <td class="text-center"><?php echo htmlspecialchars($title); ?></td>
                <td class="text-center"><img src="<?php echo $imageSrc; ?>" width="100" height="100"></td>
                <td class="text-center">
                    <form action="userIndex.php?module=cart&action=update&currency=<?php echo urlencode($selectedCurrency); ?>" method="POST" class="d-flex justify-content-center align-items-center gap-2">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($qty); ?>" min="1" class="form-control" style="width: 70px;">
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>

                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($convertedPrice, 2); ?></td>
                <td class="text-center"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($subTotal, 2); ?></td>
                <td class="text-center">
                    <a href="userIndex.php?module=cart&action=delete&product_id=<?php echo urlencode($id); ?>&currency=<?php echo urlencode($selectedCurrency); ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Remove <?php echo $title ?> from cart?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="4" class="text-end fw-bold">Grand Total:</td>
            <td class="text-center fw-bold"><?php echo htmlspecialchars($selectedCurrency) . ' ' . number_format($grandTotal, 2); ?></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="6" class="text-end">
                <a href="userIndex.php" class="btn btn-secondary">Continue Shopping</a>
                <a href="userIndex.php?module=payment&action=view&currency=<?php echo urlencode($selectedCurrency); ?>&grand_total=<?php echo urlencode($grandTotal); ?>" class="btn btn-success">Proceed to Payment</a>
            </td>
        </tr>
    </tbody>
</table>