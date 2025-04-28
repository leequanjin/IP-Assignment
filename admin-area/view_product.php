<?php
$xmlFile = '../xml-files/products.xml';
$dom = new DOMDocument();
$dom->load($xmlFile);
$products = $dom->getElementsByTagName('product');

session_start();
if (isset($_SESSION['success_message'])):
    ?>
    <script type="text/javascript">
        alert("<?php echo addslashes($_SESSION['success_message']); ?>");
    </script>
    <?php
    unset($_SESSION['success_message']);
endif;
?>

<table class="table table-bordered align-middle">
    <thead>
        <tr>
            <th scope="col" class="text-center">Product ID</th>
            <th scope="col" class="text-center">Product Title</th>
            <th scope="col" class="text-center">Product Image</th>
            <th scope="col" class="text-center">Product Price</th>
            <th scope="col" class="text-center">Available Stock</th>
            <th scope="col" class="text-center">Edit</th>
            <th scope="col" class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($products as $product) {
            $id = $product->getElementsByTagName('id')->item(0)->nodeValue;
            $title = $product->getElementsByTagName('title')->item(0)->nodeValue;
            $price = $product->getElementsByTagName('price')->item(0)->nodeValue;
            $stock = $product->getElementsByTagName('stock')->item(0)->nodeValue;
            $image = $product->getElementsByTagName('image')->item(0)->nodeValue;
            $imageSrc = '../admin-area/uploads/' . $image;
            ?>
            <tr>
                <th scope="row" class="text-center"><?php echo $id ?></th>
                <td class="text-center"><?php echo $title ?></td>
                <td><img src="<?php echo $imageSrc ?>" alt="<?php echo $title ?>" width="100%" height="150px" style="object-fit: contain;" /></td>
                <td class="text-center">RM <?php echo number_format($price, 2) ?></td>
                <td class="text-center"><?php echo $stock ?></td>
                <td class="text-center"><a href="index.php?edit_product=<?php echo $id ?>" class="text-dark"><i class="fa-solid fa-pen-to-square fa-lg"></i></a></td>
                <td class="text-center"><a href="index.php?delete_product=<?php echo $id ?>" class="text-dark"><i class="fa-solid fa-trash fa-lg"></i></a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
