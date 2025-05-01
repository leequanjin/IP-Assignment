<?php
require_once 'controllers/CategoryController.php';
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
                <td class="text-center">
                    <a href="index.php?module=category&action=view&delete_category=<?php echo urlencode($title); ?>" class="text-dark"
                       onclick="return confirm('Are you sure you want to delete the category: <?php echo $title ?>?')">
                        <i class="fa-solid fa-trash fa-lg"></i>
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>