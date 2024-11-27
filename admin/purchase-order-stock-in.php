<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

/// Retrieve the supplier ID from the URL
if (isset($_GET['track'])) {
    $order_track = $_GET['track'];
}

// Only populate session data if siItems and siItemIds are not already set
if (!isset($_SESSION['siItems']) || empty($_SESSION['siItems'])) {
    $_SESSION['siItems'] = [];
    $_SESSION['siItemIds'] = [];

    // Retrieve purchase order details along with associated ingredients
    $query = "SELECT po.*, ii.ingredient_id, ii.quantity, ii.price, i.name AS ingredient_name, u.uom_name as unit_name, u.id as unit_id
              FROM purchaseOrders po
              JOIN ingredients_Items ii ON po.id = ii.order_id
              JOIN ingredients i ON ii.ingredient_id = i.id
              JOIN units_of_measure u ON ii.unit_id = u.id
              WHERE po.tracking_no = '$order_track'";

    $orders = mysqli_query($conn, $query);

    if ($orders && mysqli_num_rows($orders) > 0) {
        // Populate session with ingredients
        while ($orderData = mysqli_fetch_assoc($orders)) {
            $ingredientId = $orderData['ingredient_id'];
            $ingredient = [
                'ingredient_id' => $ingredientId,
                'name' => $orderData['ingredient_name'],
                'quantity' => $orderData['quantity'],
                'price' => $orderData['price'],
                'unit_name' => $orderData['unit_name'],
                'unit_id' => $orderData['unit_id'],
                'expiryDate' => date('Y-m-d')
            ];

            // Add to sessions
            $_SESSION['siItems'][] = $ingredient;
            $_SESSION['siItemIds'][] = $ingredientId;
        }
    }
}

?>
<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Stock In
                <a href="purchase-orders.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php 
            alertMessage();

            $query = "SELECT po.*, a.* FROM purchaseOrders po, admins a WHERE 
                        a.id = po.customer_id AND tracking_no='$order_track' 
                        ORDER BY po.id DESC";
            $orders = mysqli_query($conn, $query);

            if($orders && mysqli_num_rows($orders) > 0){
                $orderData = mysqli_fetch_assoc($orders);
                $supplierName = $orderData['supplierName'];
                $supplier = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");
                $supplierData = mysqli_fetch_assoc($supplier);
                $orderId = $orderData['id'];
            ?>
            <div class="card card-body shadow border-1 mb-4 ">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Order Details</h4>
                        <label class="mb-1">
                            Purchase Order No: <span class="fw-bold"><?= htmlspecialchars($orderData['tracking_no'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Order Date: <span class="fw-bold"><?= htmlspecialchars($orderData['order_date'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Order Status: <span class="fw-bold"><?= htmlspecialchars($orderData['order_status'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Payment Method: <span class="fw-bold"><?= htmlspecialchars($orderData['ingPayment_mode'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                    </div>
                    <div class="col-md-6">
                        <h4>Customer Details</h4>
                        <label class="mb-1">
                            Name: <span class="fw-bold"><?= htmlspecialchars($orderData['firstname'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($orderData['lastname'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Position: 
                            <span class="fw-bold">
                                <?= $orderData['position'] == 1 ? 'Owner' : 'Employee'?>
                            </span>
                        </label>
                        <br/>
                        <br/>
                    </div>
                    <div class="col-md-6 pt-3">
                        <h4>Supplier Details</h4>
                        <label class="mb-1">
                            Supplier Name: <span class="fw-bold"><?= htmlspecialchars($supplierData['firstname'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($supplierData['lastname'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Phone Number: <span class="fw-bold"><?= htmlspecialchars($supplierData['phonenumber'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <label class="mb-1">
                            Address: <span class="fw-bold"><?= htmlspecialchars($supplierData['address'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                        <br/>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3 mb-4">
        <div class="card-header">
            <h4 class="mb-0">Purchase Order Items Details</h4>
        </div>
        <div class="card-body">
        <?php
                       $orderItemQuery = "
                       SELECT 
                           ii.quantity as orderItemQuantity, 
                           ii.price as orderItemPrice, 
                           i.name as ingredientName,
                           uom.uom_name as unit_name
                       FROM purchaseOrders po 
                       JOIN ingredients_items ii ON ii.order_id = po.id 
                       JOIN ingredients i ON i.id = ii.ingredient_id 
                       JOIN units_of_measure uom ON uom.id = ii.unit_id
                       WHERE po.tracking_no = '$order_track'
                        ";                   
    

                        $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                        $i = 1;
                        if($orderItemsRes){
                            if(mysqli_num_rows($orderItemsRes) > 0){
                                $totalAmount = 0;
                                ?>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item No. </th>
                                                <th>Ingredient</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($orderItemRow = mysqli_fetch_assoc($orderItemsRes)): ?>
                                                <tr>
                                                    <td width="10%" class=" text-center">
                                                        <?= $i++; ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['ingredientName'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'], 2); ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['unit_name'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        <?= $orderItemRow['orderItemQuantity']; ?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 2); ?>
                                                    </td> 
                                                </tr>
                                                <?php $totalAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity']; ?>
                                            <?php endwhile; ?>

                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Price: </td>
                                                <td colspan="1" class="text-end fw-bold">Php <?= number_format($totalAmount, 2); ?></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                <?php
                            }else{
                                echo ' <h5>No record Found!</h5>';
                                return false;
                            }
                        }else{
                            echo ' <h5>No record Found!</h5>';
                            return false;
                        }
                    ?>
        </div>
    </div>
    <!-- Ingredients Section -->
    <div class="card mt-3 mb-4">
        <div class="card-header">
            <h4 class="mb-0">Ingredients</h4>
        </div>
        <div class="card-body" id="siArea">
            <?php
            } $i = 1;
            if (isset($_SESSION['siItems'])) {
                $sessionIngredients = $_SESSION['siItems'];
                if (empty($sessionIngredients)) {
                    unset($_SESSION['siItems']);
                    unset($_SESSION['siItemIds']);
                }

                if (!empty($sessionIngredients)) {
            ?>
                <div class="mb-3" id="siContent">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Item No.</th>
                                <th>Ingredient</th>
                                <th>Price</th>
                                <th>Unit</th>
                                <th>Expiry Date</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                            unset($_SESSION['error_message']); // Clear the message after displaying it
                        }

                        $totalAmount = 0; // Initialize the total amount

                        foreach ($sessionIngredients as $key => $item) : 
                            $itemTotal = $item['price'] * $item['quantity']; // Calculate total for each item
                            $totalAmount += $itemTotal; // Add to the cumulative total
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $item['name']; ?></td>
                                <td>Php <?= $item['price']; ?></td>
                                <td><?= $item['unit_name']; ?></td>
                                <td> 
                                    <input type="date" id="expiry_date_<?= $item['ingredient_id']; ?>" 
                                        name="expiry_date[<?= $item['ingredient_id']; ?>]" 
                                        value="<?= $item['expiryDate']; ?>"  
                                        class="form-control" required
                                        onchange="updateExpiryDate(<?= $item['ingredient_id']; ?>)">
                                </td>
                                <td>
                                    <div class="input-group qtyBox">
                                        <input type="hidden" value="<?= $item['ingredient_id'];?>" class="ingId">
                                        <button class="input-group-text si-decrement">-</button>
                                        <input type="decimal" min="0" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                        <button class="input-group-text si-increment">+</button>
                                    </div> 
                                </td>
                                <td>Php <?= number_format($itemTotal, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">Total Price: </td>
                                <td colspan="1" class="text-end fw-bold">Php <?= number_format($totalAmount, 2); ?></td>
                            </tr>
                    </tbody>
                    </table>

                    <div>
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-4">
                                <br/>
                                <input type="hidden" name="order_track" id="order_track" value="<?= $order_track; ?>">
                                <button type="button" class="btn btn-warning w-100 stockInBtn">Stock In</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                } else {
                    echo '<h5>No items added</h5>';
                }
            } else {
                echo '<h5>No items added</h5>';
            }
            ?>
        </div>
    </div>
</div>

<script>
    function updateExpiryDate(ingredientId) {
    const expiryDate = document.getElementById(`expiry_date_${ingredientId}`).value;
    
    // Send an AJAX request to the server to update the session with the new expiry date
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'purchase-order-update-expiry-date.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Send the ingredient ID and the new expiry date to the server
    xhr.send(`ingredient_id=${ingredientId}&expiry_date=${expiryDate}`);

    // Optionally, you can show some confirmation message or handle the response here
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log('Expiry date updated successfully.');
        } else {
            console.error('Error updating expiry date.');
        }
    };
}
</script>

<?php include('includes/footer.php'); ?>