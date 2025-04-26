<?php
include '../includes/connect.php';

$subjectCode = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["insert_category"])) {
    $category_title = prepareInput($_POST["category_title"]);

    try {
        $selectQuery = "SELECT COUNT(*) FROM categories where category_title = (:category_title)";
        $selectStmt = $conn->prepare($selectQuery);
        $selectStmt->bindParam(':category_title', $category_title);
        $selectStmt->execute();

        $numRows = $selectStmt->fetchColumn();

        if ($numRows > 0) {
            echo "<script>alert('This category already exists.');</script>";
        } else {
            $insertQuery = "INSERT INTO categories (category_title) VALUES (:category_title)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bindParam(':category_title', $category_title);
            $insertStmt->execute();

            echo "<script>alert('Category has been inserted successfully!');</script>";
        }
    } catch (PDOException $e) {
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
    <div class="input-group mb-2">
        <input type="submit" class="form-control btn btn-secondary" name="insert_category" value="Submit">
    </div>
</form>

