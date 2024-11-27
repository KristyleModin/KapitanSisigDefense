<?php

include('../config/function.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Asia/Manila');



if(!isset($_SESSION['ingredientItems'])) {
    $_SESSION['ingredientItems'] = [];
}

if(!isset($_SESSION['ingredientItemIds'])) {
    $_SESSION['ingredientItemIds'] = [];
}
if (isset($_POST['addIngredient'])) {
    $ingredientId = validate($_POST['ingredient_id']);
    $quantity = validate($_POST['quantity']);
    $unit_id = $_POST['unit_id']; // Retrieve unit_id from the form
    $supplier_id = validate($_POST['supplier_id']);
    
    // Updated query to join the units table
    $checkIngredient = mysqli_query($conn, "
        SELECT si.*, i.name AS ingredient_name, i.category AS ingredient_category, u.uom_name AS unit_name
        FROM supplier_ingredients si
        LEFT JOIN ingredients i ON si.ingredient_id = i.id
        LEFT JOIN units_of_measure u ON si.unit_id = u.id
        WHERE  si.supplier_id = '$supplier_id' AND si.ingredient_id='$ingredientId' LIMIT 1
    ");

    if ($checkIngredient) {
        if (mysqli_num_rows($checkIngredient) > 0) {
            $row = mysqli_fetch_assoc($checkIngredient); // Fetch the ingredient details

            // Prepare the ingredient data to be saved
            $ingredientData = [
                'ingredient_id' => $row['ingredient_id'],
                'name' => $row['ingredient_name'], // Correct the reference to ingredient name
                'unit_id' => $row['unit_id'], // UoM ID
                'unit_name' => $row['unit_name'], // UoM name
                'category' => $row['ingredient_category'],
                'price' => $row['price'],
                'quantity' => $quantity,
            ];

            // Check if ingredient is already in session, then update or add
            if (!in_array($row['ingredient_id'], $_SESSION['ingredientItemIds'])) {
                array_push($_SESSION['ingredientItemIds'], $row['ingredient_id']);
                array_push($_SESSION['ingredientItems'], $ingredientData);
            } else {
                foreach ($_SESSION['ingredientItems'] as $key => $ingSessionItem) {
                    if ($ingSessionItem['ingredient_id'] == $row['ingredient_id']) {
                        $newQuantity = $ingSessionItem['quantity'] + $quantity;

                        $ingredientData = [
                            'ingredient_id' => $row['ingredient_id'],
                            'name' => $row['ingredient_name'], // Correct reference here as well
                            'unit_id' => $row['unit_id'], // Store UoM ID
                            'unit_name' => $row['unit_name'], // Store UoM name
                            'category' => $row['ingredient_category'],
                            'price' => $row['price'],
                            'quantity' => $newQuantity,
                        ];

                        $_SESSION['ingredientItems'][$key] = $ingredientData;
                    }
                }
            }

            // Redirect with the supplier ID in the URL
            redirect("purchase-order-create.php?track=$supplier_id", 'Ingredient added: ' . $quantity . ' ' . $row['ingredient_name']);
        } else {
            redirect("purchase-order-create.php?track=$supplier_id", 'No such ingredient found!');
        }
    } else {
        redirect("purchase-order-create.php?track=$supplier_id", 'Something went wrong!');
    }
}


if (isset($_POST['ingredientIncDec'])) {
    $ingredientId = validate($_POST['ingredient_id']);
    $quantity = validate($_POST['quantity']);

    // Fetch product details from the database
    $checkIngredient = mysqli_query($conn, "SELECT * FROM ingredients WHERE id='$ingredientId' LIMIT 1");

    // Initialize a flag
    $flag = false;

    // Check if product exists
    if (mysqli_num_rows($checkIngredient) > 0) {
        $row = mysqli_fetch_assoc($checkIngredient);

        // Loop through session items and update the quantity
        foreach ($_SESSION['ingredientItems'] as $key => $item) {
            if ($item['ingredient_id'] == $ingredientId) {
                // Update the quantity
                $_SESSION['ingredientItems'][$key]['quantity'] = $quantity;
                $flag = true; // Quantity changed
                break; // Exit the loop once the item is found and updated
            }
        }
    }

    // Prepare JSON response based on the flag
    if ($flag) {
        jsonResponse(200, 'success', "Quantity changed.");
    } else {
        jsonResponse(500, 'error', "Something went wrong!");
    }
}



if (isset($_POST['proceedToPlaceIng'])) {
    $adminName = validate($_POST['adminName']);
    $ingPayment_mode = validate($_POST['ingPayment_mode']);
    $supplierName = validate($_POST['supplierName']);
    $order_status = validate($_POST['order_status']);

    // Ensure correct usage of variable names
    $checkAdmin = mysqli_query($conn, "SELECT * FROM admins WHERE firstname='$adminName' LIMIT 1");

    if ($checkAdmin) {
        if (mysqli_num_rows($checkAdmin) > 0) {
            $_SESSION['invoice_no'] = "INV-" . rand(111111, 999999);
            $_SESSION['adminName'] = $adminName;
            $_SESSION['order_status'] = $order_status;
            $_SESSION['ingPayment_mode'] = $ingPayment_mode;
            $_SESSION['supplierName'] = $supplierName;

            jsonResponse(200, 'success', 'Admin found');
        } else {
            $_SESSION['adminName'] = $adminName;
            jsonResponse(404, 'warning', 'Admin not found');
        }
    } else {
        jsonResponse(500, 'error', 'Something Went Wrong');
    }
}

if (isset($_POST['proceedToUpdateIng'])) {
    $order_status = validate($_POST['order_status']);
    $order_id = validate($_POST['order_id']);

    // Debugging: print the order ID and the order data
    error_log("Order ID: " . $order_id);
    $orderData = getByID('purchaseorders', $order_id);
    error_log(print_r($orderData, true));

    if ($orderData['status'] != 200) {
        jsonResponse(404, 'error', 'Purchase order not found');
        exit();
    }

    if ($order_status != '') {
        $data = ['order_status' => $order_status];
        $updateResult = update('purchaseorders', $order_id, $data);

        if ($updateResult) {
            jsonResponse(200, 'success', 'Purchase order updated successfully');
        } else {
            jsonResponse(500, 'error', 'Failed to update order status');
        }
    } else {
        jsonResponse(400, 'error', 'Invalid order ID or status');
    }
}

if(isset($_POST['stockInBtn'])) {
    $order_status = validate($_POST['order_status']); 
    $order_track = validate($_POST['order_track']); 

    if (empty($order_track)) {
        jsonResponse(400, 'error', 'Invalid order tracking number');
        exit();
    }

    // Fetch the order ID based on the tracking number
    $query = "SELECT id FROM purchaseorders WHERE tracking_no = '$order_track' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $orderData = mysqli_fetch_assoc($result);

    if ($orderData) {
        $order_id = $orderData['id'];
        $data = ['order_status' => $order_status];
        $updateResult = update('purchaseorders', $order_id, $data);

        if ($updateResult && $order_status == 'Delivered') {
            // Query to fetch ordered ingredients
            $orderItemQuery = "SELECT ii.quantity as orderItemQuantity, i.id as ingredientId, ii.unit_id,
                                po.id as purchaseorder_id, po.customer_id as admin_id, po.invoice_no as invoice_no, po.supplierName as supplier_id
                               FROM purchaseOrders po 
                               JOIN ingredients_items ii ON ii.order_id = po.id 
                               JOIN ingredients i ON i.id = ii.ingredient_id 
                               WHERE po.tracking_no='$order_track'";

            $orderItemsRes = mysqli_query($conn, $orderItemQuery);
            $orderItemRow = mysqli_fetch_assoc($orderItemsRes);

            $orderData = [
                'admin_id' => $orderItemRow['admin_id'],
                'purchaseorder_id' => $orderItemRow['purchaseorder_id'],
                'invoice_no' => $orderItemRow['invoice_no'],
                'supplier_id' => $orderItemRow['supplier_id'],
                'stockin_date' => date('Y-m-d H:i:s')
            ];

            $orderResult = insert('stockin', $orderData);
            $lastOrderId = mysqli_insert_id($conn);


            if ($orderItemsRes && mysqli_num_rows($orderItemsRes) > 0) {
                foreach ($_SESSION['siItems'] as $ingItem) {
                    $ingredientId = $ingItem['ingredient_id'];
                    $quantity = $ingItem['quantity'];
                    $unit_id = $ingItem['unit_id'];
                    $price = $ingItem['price'];
                    $expiryDate = $ingItem['expiryDate'];

                    if (strtotime($expiryDate) < strtotime(date('Y-m-d'))) {
                        $_SESSION['message'] = "Error: Expiry date cannot be in the past for ingredient ID: $ingredientId.";
                        header("Location: purchase-order-stock-in.php?track=$order_track");
                        exit();
                    }
                    
                    if (empty($expiryDate)) {
                        $_SESSION['message'] = "Error: Expiry date is required for ingredient ID: $ingredientId.";
                        header("Location: purchase-order-stock-in.php?track=$order_track");
                        exit();
                    }                    
                
                    // Get the unit ratio to calculate the effective quantity
                    $checkUnitRatio = mysqli_query($conn, "SELECT ratio FROM units_of_measure WHERE id='$unit_id'");
                    $ingQtyData = mysqli_fetch_assoc($checkUnitRatio);
                    
                    if ($ingQtyData) {
                        $ratio = (float)$ingQtyData['ratio'];
                        $totalIngQuantity = $ratio * $quantity;
                
                        // Fetch current quantity for the ingredient
                        $selectIngredientQtyQuery = mysqli_query($conn, "SELECT quantity FROM ingredients WHERE id='$ingredientId'");
                        $selectIngredientQtyResult = mysqli_fetch_assoc($selectIngredientQtyQuery);
                
                        if ($selectIngredientQtyResult) {
                            $currentQuantity = (float)$selectIngredientQtyResult['quantity'];
                            $newQuantity = $currentQuantity + $totalIngQuantity;
                            
                            $updateQuantityQuery = mysqli_query($conn, "UPDATE ingredients SET quantity = '$newQuantity' WHERE id = '$ingredientId'");

                            $dataOrderItem = [
                                'stockin_id' => $lastOrderId,
                                'ingredient_id' => $ingredientId,
                                'quantity' => $quantity,
                                'totalQuantity' => $totalIngQuantity,
                                'unit_id' => $unit_id,
                                'totalPrice' => $price * $quantity,
                                'expiryDate' => $expiryDate,
                            ];
                
                            $orderItemQuery = insert('stockin_ingredients', $dataOrderItem);
                
                            if (!$updateQuantityQuery) {
                                jsonResponse(500, 'error', 'Failed to update ingredient quantity for ingredient ID: ' . $ingredientId);
                                exit();
                            }

                            if (!$orderItemQuery) {
                                jsonResponse(500, 'error', "Failed to insert ingredient data for ingredient ID: $ingredientId.");
                                exit();
                            }
                        } else {
                            jsonResponse(500, 'error', 'Ingredient not found for ID: ' . $ingredientId);
                            exit();
                        }
                    } else {
                        $_SESSION['message'] = "Error: no ratio for unit found.";
                        header("Location: purchase-order-stock-in.php?track=$order_track");
                        exit();
                    }
                }
                jsonResponse(200, 'success', 'Order delivered and ingredient quantities updated successfully');
            } else {
                jsonResponse(404, 'error', 'No ingredients found for this order');
            }
        } else {
            jsonResponse(200, 'success', 'Order status updated successfully');
        }
    } else {
        jsonResponse(404, 'error', 'Order not found');
    }
}





if (isset($_POST['savePurchaseOrder'])) {
    $adminName = validate($_SESSION['adminName']);
    $invoice_no = validate($_SESSION['invoice_no']);
    $order_status = validate($_SESSION['order_status']);
    $ingPayment_mode = validate($_SESSION['ingPayment_mode']);
    $supplierName = validate($_SESSION['supplierName']);
    $order_placed_by_id = ($_SESSION['loggedInUser']['firstname']);

    // Check if customer exists
    $checkAdmin = mysqli_query($conn, "SELECT * FROM admins WHERE firstname='$adminName' LIMIT 1");
    $checkSupplier = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");

    if (!$checkAdmin || !$checkSupplier) {
        jsonResponse(500, 'error', 'Something Went Wrong');
        exit;
    }

    if (mysqli_num_rows($checkAdmin) > 0 && mysqli_num_rows($checkSupplier) > 0) {
        $adminData = mysqli_fetch_assoc($checkAdmin);
        $supplierData = mysqli_fetch_assoc($checkSupplier);

        if (!isset($_SESSION['ingredientItems'])) {
            jsonResponse(404, 'warning', 'No items to place order');
            exit;
        }

        // Get the last tracking number from the purchaseOrders table
        $lastTrackingQuery = mysqli_query($conn, "SELECT tracking_no FROM purchaseOrders ORDER BY id DESC LIMIT 1");
        $lastTrackingNumber = 0;

        if ($lastTrackingQuery && mysqli_num_rows($lastTrackingQuery) > 0) {
            $lastTracking = mysqli_fetch_assoc($lastTrackingQuery);
            $lastTrackingNumber = (int) $lastTracking['tracking_no'];
        }

        // Increment the tracking number
        $newTrackingNumber = str_pad($lastTrackingNumber + 1, 6, '0', STR_PAD_LEFT);

        $totalAmount = 0;
        $sessionIngredients = $_SESSION['ingredientItems'];  // Make sure this exists
        foreach ($sessionIngredients as $ingredientItems) {
            $totalAmount += $ingredientItems['price'] * $ingredientItems['quantity'];
        }

        $data = [
            'customer_id' => $adminData['id'],
            'tracking_no' => $newTrackingNumber,
            'invoice_no' => $invoice_no,
            'total_amount' => $totalAmount,
            'order_date' => date('Y-m-d H:i:s'),
            'order_status' => $order_status,
            'ingPayment_mode' => $ingPayment_mode,
            'order_placed_by_id' => $order_placed_by_id,
            'supplierName' => $supplierName //id sa supplier ang masave ani, dili supplierName
        ];

        $result = insert('purchaseOrders', $data);
        $lastOrderId = mysqli_insert_id($conn);

        foreach ($sessionIngredients as $ingItem) {
            $ingredientId = $ingItem['ingredient_id'];
            $unitId = $ingItem['unit_id'];
            $price = $ingItem['price'];
            $quantity = $ingItem['quantity'];

            $dataIngredientItem = [
                'order_id' => $lastOrderId,
                'ingredient_id' => $ingredientId,
                'unit_id' => $unitId,
                'price' => $price,
                'quantity' => $quantity,
            ];

            $orderItemQuery = insert('ingredients_items', $dataIngredientItem);
        }

        unset($_SESSION['ingredientItemIds'], $_SESSION['ingredientItems'], $_SESSION['ingPayment_mode'], $_SESSION['invoice_no'], $_SESSION['supplierName']);
        jsonResponse(200, 'success', 'Order placed successfully!');
    } else {
        jsonResponse(404, 'warning', 'No Admin or Supplier found');
    }
}

if (isset($_POST['siIncDec'])) {
    $ingredientId = validate($_POST['ingredient_id']);
    $quantity = validate($_POST['quantity']);

    // Fetch product details from the database
    $checkIngredient = mysqli_query($conn, "SELECT * FROM ingredients WHERE id='$ingredientId' LIMIT 1");

    // Initialize a flag
    $flag = false;

    // Check if product exists
    if (mysqli_num_rows($checkIngredient) > 0) {
        $row = mysqli_fetch_assoc($checkIngredient);

        // Loop through session items and update the quantity
        foreach ($_SESSION['siItems'] as $key => $item) {
            if ($item['ingredient_id'] == $ingredientId) {
                // Update the quantity
                $_SESSION['siItems'][$key]['quantity'] = $quantity;
                $flag = true; // Quantity changed
                break; // Exit the loop once the item is found and updated
            }
        }
    }

    // Prepare JSON response based on the flag
    if ($flag) {
        jsonResponse(200, 'success', "Quantity changed.");
    } else {
        jsonResponse(500, 'error', "Something went wrong!");
    }
}

?>