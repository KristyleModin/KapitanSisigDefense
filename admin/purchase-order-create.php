<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve the supplier ID from the URL
if (isset($_GET['track'])) {
    $supplier_id = $_GET['track'];
    $_SESSION['supplier_id'] = $supplier_id;  // Store supplier_id in the session

    // Fetch supplier details from the database
    $supplier_query = "SELECT firstname, lastname FROM suppliers WHERE id = $supplier_id LIMIT 1";
    $supplier_result = mysqli_query($conn, $supplier_query);

    // Fetch ingredients related to the supplier and their respective unit of measure from supplier_ingredients
    $ingredient_query = "
    SELECT si.ingredient_id, i.name as ingredient_name, u.id as unit_id, u.uom_name
    FROM supplier_ingredients si
    JOIN ingredients i ON si.ingredient_id = i.id
    JOIN units_of_measure u ON si.unit_id = u.id
    WHERE si.supplier_id = $supplier_id AND si.ingredient_id = i.id
";

    $ingredient_result = mysqli_query($conn, $ingredient_query);

    // Check if supplier details were found
    if (mysqli_num_rows($supplier_result) > 0) {
        $supplier = mysqli_fetch_assoc($supplier_result);
        $supplier_name = $supplier['firstname'] . " " . $supplier['lastname'];
    } else {
        $supplier_name = "Unknown Supplier";
    }
} else {
    // Default if no supplier is selected
    $supplier_name = "No Supplier Selected";
}

?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Purchase Order</strong>
                <a href="purchase-order-select-supplier.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="purchase-orders-code.php" method="POST">
                <div class="mb-3">
                <h4 class="mb-0">Supplier: <strong><?= $supplier_name; ?></strong></h4>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Ingredients</label>
                        <select name="ingredient_id" class="form-select mySelect2">
                            <option value="">-- Select Ingredient --</option>
                            <?php
                                if($ingredient_result) {
                                    if(mysqli_num_rows($ingredient_result) > 0) {
                                        while ($ingItem = mysqli_fetch_assoc($ingredient_result)) {
                                            ?>
                                            <option value="<?= $ingItem['ingredient_id']; ?>">
                                                <?= $ingItem['ingredient_name']; ?> (<?= $ingItem['uom_name']; ?>) <!-- Updated to uom_name -->
                                            </option>
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
                        <input type="decimal" name="quantity" value="1" min="1" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="submit" name="addIngredient" class="btn btn-outline-primary">Add Ingredient</button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="hidden" id="order_status" name="order_status" value="Placed">
                        <input type="hidden" value="<?= $_SESSION['loggedInUser']['firstname'] ?>" id="adminName" name="adminName">
                        <input type="hidden" name="unit_id" value="<?= $ingItem['unit_id']; ?>">
                        <input type="hidden" name="supplierName" id="supplierName" value="<?= $supplier_id?>">
                        <input type="hidden" name="supplier_id" id="supplier_id" value="<?= $supplier_id?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 mb-4">
            <div class="card-header">
                <h4 class="mb-0">Ingredients</h4>
            </div>
            <div class="card-body">
                <?php
                // Check if ingredientItems are in session
                if (isset($_SESSION['ingredientItems'])) {
                    $sessionIngredients = $_SESSION['ingredientItems'];

                    // Check if the session array is empty after removing all ingredients
                    if (empty($sessionIngredients)) {
                        // Unset session variables if no ingredients left
                        unset($_SESSION['ingredientItems']);
                        unset($_SESSION['ingredientItemIds']);
                    }

                    // If there are still ingredients in the cart, display them
                    if (!empty($sessionIngredients)) {
                    ?>
                        <div class="mb-3" id="ingredientArea">
                            <div id="ingredientContent">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item No.</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>UoM</th> <!-- Update header for UoM -->
                                            <th>Quantity</th>
                                            <th>Total Price</th> 
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $i = 1;
                                        foreach ($sessionIngredients as $key => $item) : 
                                        ?>
                                            <tr>
                                                <td><?= $i++; ?></td>
                                                <td><?= $item['name']; ?></td>
                                                <td><?= $item['category']; ?></td>
                                                <td>Php <?= $item['price']; ?></td>
                                                <td><?= $item['unit_name']; ?></td> <!-- Updated to display UoM name -->
                                                <td>
                                                    <div class="input-group qtyBox">
                                                        <input type="hidden" value="<?= $item['ingredient_id'];?>" class="ingId">
                                                        <button class="input-group-text ing-decrement">-</button>
                                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                                        <button class="input-group-text ing-increment">+</button>
                                                    </div>
                                                </td>
                                                <td>Php <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                                <td>
                                                    <a href="purchase-order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                <div class="mt-2 mb-2">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Select Payment Method</label>
                            <select id="ingPayment_mode" class="form-select">
                                <option value="">-- Select Payment --</option>
                                <option value="Cash Payment">Cash Payment</option>
                                <option value="Online Payment">Online Payment</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                                <br/>
                                <button type="button" class="btn btn-warning w-100 proceedToPlaceIng">Proceed to place order</button>
                            </div>
                    </div>
                </div>
                <?php
                    } else {
                        // If no ingredients are left, show the "No items added" message
                        echo '<h5>No items added</h5>';
                    }
                } else {
                    // If no ingredients have been added to the session
                    echo '<h5>No items added</h5>';
                }
                ?>
            </div>
        </div>
    </div>

<?php include('includes/footer.php'); ?>