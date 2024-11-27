<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Unit Category
                <a href="units.php" class="btn btn-outline-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="category_unit_name">Category Name *</label>
                        <input type="text" name="category_unit_name" required class="form-control" />
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" name="saveUnitCategory" class="btn btn-outline-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>