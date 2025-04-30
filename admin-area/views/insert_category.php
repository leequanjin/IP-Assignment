<form action="index.php?action=insert" method="post">
    <div class="mb-2">
        <input type="text" name="category_title" class="form-control" placeholder="Insert new category">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_message'];
    unset($_SESSION['success_message']); ?></div>
<?php endif;
