<?php
$xmlFile = '../xml-files/categories.xml';
$dom = new DOMDocument();
$dom->load($xmlFile);
$categories = $dom->getElementsByTagName('category');

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
            <th scope="col" class="text-center">Category Title</th>
            <th scope="col" class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($categories as $category) {
            $title = $category->getElementsByTagName('title')->item(0)->nodeValue;
            ?>
            <tr>
                <td class="text-center"><?php echo $title ?></td>
                <td class="text-center"><a href="index.php?delete_category=<?php echo $title ?>" class="text-dark"><i class="fa-solid fa-trash fa-lg"></i></a></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
