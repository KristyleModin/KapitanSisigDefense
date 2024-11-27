<?php include('includes/header.php'); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Menu Product
                <a href="products.php" class="btn btn-primary float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage();?>
            <form action="code.php" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Select Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php
                            $categories = getAll('categories');
                            if($categories) {
                                if(mysqli_num_rows($categories)) {
                                    foreach($categories as $cItem) {
                                        echo '<option value="'.$cItem['id'].'">'.$cItem['name'].'</option>';
                                    }
                                } else {
                                    echo '<option value="">No categories found!</option>';
                                }
                            } else {
                                echo '<option value="">Something went wrong.</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Menu Product Name *</label>
                        <input type="text" name="productname" required class="form-control" />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="">Price *</label>
                        <input type="number" name="price" required class="form-control" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="">Image *</label>
                        <input type="file" name="image" class="form-control" />
                    </div>
                    <div class="col-md-12 mb-3 text-end">
                        <button type="submit" name="saveProduct" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Automatically calculate the product quantity based on available ingredients
    document.addEventListener("DOMContentLoaded", function() {
        // Assuming product_id is not needed for a new product (saveProduct)
        // For a new product, the quantity will be calculated based on the ingredients
        const productId = 0;  // New product, no existing product_id
        
        // If this is an existing product, fetch the available quantity
        if (productId > 0) {
            fetch('get_product_quantity.php?product_id=' + productId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('calculatedQuantity').value = data.quantity;
                    } else {
                        document.getElementById('calculatedQuantity').value = '0';
                    }
                })
                .catch(error => {
                    console.error('Error calculating quantity:', error);
                    document.getElementById('calculatedQuantity').value = '0';
                });
        }
    });
</script>

<?php include('includes/footer.php'); ?>