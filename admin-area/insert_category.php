<?php
$xmlFile = '../xml-files/categories.xml';
$error = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_category"])) {
    $category_title = prepareInput($_POST["category_title"]);

    try {
        if (empty($category_title)) {
            $error = "Please enter a category title.";
        } else {
            if (file_exists($xmlFile)) {
                $dom = new DOMDocument();
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->load($xmlFile);
                $root = $dom->documentElement;
            } else {
                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $root = $dom->createElement('categories');
                $dom->appendChild($root);
            }

            $xpath = new DOMXPath($dom);
            $query = "//category[title='$category_title']";
            $entries = $xpath->query($query);

            if ($entries->length > 0) {
                echo "<script>alert('This category already exists.');</script>";
            } else {
                $newCategory = $dom->createElement('category');

                $title = $dom->createElement('title', $category_title);
                $newCategory->appendChild($title);

                $root->appendChild($newCategory);
                $dom->save($xmlFile);

                $successMessage = "Category has been inserted successfully!";
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('Something went wrong. Please try again later.');</script>";
        error_log($e->getMessage());
    }
}

function prepareInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<form action="" method="post" class="mb-2">
    <div class="input-group mb-2">
        <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-receipt"></i></span>
        <input type="text" class="form-control" name="category_title" placeholder="Insert new category">
    </div>
    <div class="form-text m-1 mb-3 <?php echo ($error ? 'text-danger' : ''); ?>">
        <?php if ($error): ?>
            <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
        <?php endif; ?>
    </div>
    <div class="input-group mb-2">
        <input type="submit" class="form-control btn btn-secondary" name="insert_category" value="Submit">
    </div>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success mt-2">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
</form>
