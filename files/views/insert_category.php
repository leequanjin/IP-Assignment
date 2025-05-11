<!-- Author     : Lee Quan Jin-->

<?php
require_once 'controllers/CategoryController.php';
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
