<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Menu Product
                <a href="products.php" class = "btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php  alertMessage(); ?>
            <form action="code.php" method="POST" enctype="multipart/form-data">

            <?php 
                $paramValue = checkParam('id');
                if(!is_numeric($paramValue)){
                    echo '<h5>ID is not an integer.</h5>';
                    return false;
                }

                $productData = getById('products', $paramValue);
                if($productData){
                    if($productData['status'] == 200) {
                        ?>
                        <input type="hidden" name="product_id" value="<?= $productData['data']['id']; ?>" >

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="">Select Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php
                                    $categories = getAll('categories');
                                    if($categories) {
                                        if(mysqli_num_rows($categories) > 0) {
                                            foreach($categories as $cItem) {
                                                ?>
                                                    <option value="<?= $cItem['id']; ?>" <?= $productData['data']['category_id'] == $cItem['id'] ? 'selected':''; ?>>
                                                    <?= $cItem['name']; ?>
                                                    </option>';
                                                <?php
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
                                <input type="text" name="productname" value="<?= $productData['data']['productname']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= $productData['data']['description']; ?></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Price *</label>
                                <input type="number" name="price" value="<?= $productData['data']['price']; ?>" class="form-control" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="">Image *</label>
                                <input type="file" name="image" class="form-control" />
                                <img src="../<?= $productData['data']['image']; ?>" style="width:40px;height:40px;" alt="product image" />
                            </div>
                            <div class="col-md-4 mb-3 text-end">
                                <br />
                                <button type="submit" name="updateProduct" class="btn btn-outline-primary">Update</button>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo '<h5'.$productData>['message'].'</h5>';
                    }
                } else {
                    echo 'Something went wrong.';
                    return false;

                }
            ?>

                
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>