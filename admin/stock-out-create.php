<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Stock Out
                <a href="#" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Ingredients</label>
                        <select name="ingredient_id" class="form-select mySelect2">
                            <option value="">-- Select Ingredient --</option>
                            <?php
                                $ingredients = getAll('ingredients');
                                if($ingredients) {
                                    if(mysqli_num_rows($ingredients) > 0) {
                                        foreach($ingredients as $ingItem) {
                                            ?>
                                                <option value="<?= $ingItem['id']; ?>"><?= $ingItem['name']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No Ingredients found!</option>';
                                    }
                                } else {
                                    echo '<option value="">Something went wrong!</option>';
                                }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="submit" name="addIngredient" class="btn btn-outline-primary">Add Ingredient</button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="hidden" id="order_status" name="order_status" value="Placed">
                        <input type="hidden" value="<?= $_SESSION['loggedInUser']['firstname'] ?>" id="adminName" name="adminName">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 mb-4">
        <div class="card-header">
             <h4 class="mb-0">Ingredients</h4>
        </div>
        <div class="card-body" id="ingredientArea">
             <?php
              // Check if soItems are in session
              if(isset($_SESSION['soItems']))
              {
                  $sessionIngredients = $_SESSION['soItems'];

                  // Check if the session array is empty after removing all ingredients
                  if(empty($sessionIngredients)){
                      // Unset session variables if no ingredients left
                      unset($_SESSION['soItems']);
                      unset($_SESSION['soItemIds']);
                  }

                  if (!empty($sessionIngredients)) {
                  ?>

                    <div class="mb-3" id="ingredientContent">
                        <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>UoM</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th> 
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($sessionIngredients as $key => $item) : 
                            ?>
                                <tr>
                                    <td><?= $item['name']; ?></td>
                                    <td><?= $item['unit_name']; ?></td>
                                    <td><?= $item['category']; ?></td>
                                    <td><?= $item['sub_category']; ?></td>
                                    <td>Php <?= $item['price']; ?></td>
                                    <td>
                                    <div class="input-group qtyBox">
                                        <input type="hidden" value="<?= $item['ingredient_id'];?>" class="ingId">
                                        <button class="input-group-text so-decrement">-</button>
                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                        <button class="input-group-text so-increment">+</button>
                                    </div>
                                    </td>
                                    <td>Php <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    <td>
                                        <a href="stock-out-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>

                        <div class="mt-2">
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Select Reason</label>
                                <select id="reason" class="form-select">
                                    <option value="">-- Select Reason --</option>
                                    <option value="Damaged">Damaged</option>
                                    <option value="Expired">Expired</option>
                                    <option value="Lacking">Lacking</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <br/>
                                <button type="button" class="btn btn-warning w-100 proceedToPlaceSo">Proceed to Stock Out</button>
                            </div>
                        </div>
                    </div>
                    </div>
                  <?php
                  } else {
                    // If no products are left, show the "No items added" message
                    echo '<h5>No items added</h5>';
                }
              } else {
                 // If no items have been added to the session
                 echo '<h5>No items added</h5>';
              }
             ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
