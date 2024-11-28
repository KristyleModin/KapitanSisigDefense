<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if supplier_id is provided
if (isset($_GET['track'])) {
    $supplier_id = $_GET['track'];

    // Fetch supplier details (only active suppliers)
    $supplier_query = "SELECT firstname, lastname 
                       FROM suppliers 
                       WHERE id = $supplier_id AND status = 'active' 
                       LIMIT 1";
    $supplier_result = mysqli_query($conn, $supplier_query);

    // Check if supplier exists and is active
    if (mysqli_num_rows($supplier_result) > 0) {
        $supplier = mysqli_fetch_assoc($supplier_result);
        $supplier_name = $supplier['firstname'] . " " . $supplier['lastname'];
    } else {
        // Redirect back if supplier not found or inactive
        $_SESSION['alert'] = "Supplier not found or inactive.";
        header("Location: purchase-order-select-supplier.php");
        exit();
    }

    // Fetch ingredients related to the active supplier
    $ingredient_query = "
    SELECT si.ingredient_id, i.name as ingredient_name, u.id as unit_id, u.uom_name
    FROM supplier_ingredients si
    JOIN ingredients i ON si.ingredient_id = i.id
    JOIN units_of_measure u ON si.unit_id = u.id
    WHERE si.supplier_id = $supplier_id";
    $ingredient_result = mysqli_query($conn, $ingredient_query);
} else {
    // Default if no supplier is selected
    $supplier_name = "No Supplier Selected";
}
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Purchase Order</h4>
            <a href="purchase-order-select-supplier.php" class="btn btn-outline-danger float-end">Back</a>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="purchase-orders-code.php" method="POST">
                <div class="mb-3">
                    <h4 class="mb-0">Supplier: <strong><?= $supplier_name; ?></strong></h4>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="ingredient_id">Select Ingredients</label>
                        <select name="ingredient_id" class="form-select mySelect2">
                            <option value="">-- Select Ingredient --</option>
                            <?php
                            if ($ingredient_result && mysqli_num_rows($ingredient_result) > 0) {
                                while ($ingItem = mysqli_fetch_assoc($ingredient_result)) {
                                    ?>
                                    <option value="<?= $ingItem['ingredient_id']; ?>">
                                        <?= $ingItem['ingredient_name']; ?> (<?= $ingItem['uom_name']; ?>)
                                    </option>
                                    <?php
                                }
                            } else {
                                echo '<option value="">No Ingredients found!</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="quantity">Quantity</label>
                        <input type="decimal" name="quantity" value="1" min="1" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="submit" name="addIngredient" class="btn btn-outline-primary">Add Ingredient</button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="hidden" name="order_status" value="Placed">
                        <input type="hidden" name="supplier_id" value="<?= $supplier_id ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Ingredients -->
    <div class="card mt-3 mb-4">
        <div class="card-header">
            <h4 class="mb-0">Ingredients</h4>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['ingredientItems']) && !empty($_SESSION['ingredientItems'])) {
                ?>
                <div class="mb-3" id="ingredientArea">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Item No.</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>UoM</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($_SESSION['ingredientItems'] as $key => $item) {
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $item['name']; ?></td>
                                    <td><?= $item['category']; ?></td>
                                    <td>Php <?= $item['price']; ?></td>
                                    <td><?= $item['unit_name']; ?></td>
                                    <td>
                                        <div class="input-group qtyBox">
                                            <input type="hidden" value="<?= $item['ingredient_id']; ?>" class="ingId">
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
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 mb-2">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Select Payment Method</label>
                            <select id="ingPayment_mode" name="payment_mode" class="form-select">
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
                echo '<h5>No items added</h5>';
            }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
