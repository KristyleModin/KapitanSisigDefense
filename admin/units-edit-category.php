<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Unit of Measurement Category
                <a href="units.php" class="btn btn-outline-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php 
            // Connect to the database
            // $conn = new mysqli('localhost', 'root', '', 'units_db');

            // Fetch the category ID from the URL
            $category_id = $_GET['id'];

            // Fetch the category name from the database
            $sql = "SELECT * FROM unit_categories WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $category_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            ?>

            <form action="code.php" method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Unit Category Name *</label>
                        <input type="text" name="category_unit_name" required class="form-control" value="<?php echo htmlspecialchars($category['category_unit_name']); ?>" />
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="submit" name="updateUnitCategory" class="btn btn-outline-primary">Update</button>
                    </div>
                </div>
            </form>

            <?php $stmt->close(); ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>