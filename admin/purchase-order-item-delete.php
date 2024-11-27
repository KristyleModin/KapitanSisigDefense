<?php
require '../config/function.php';

// Start the session to access session variables
session_start();

// Retrieve supplier_id from session
$supplier_id = isset($_SESSION['supplier_id']) ? $_SESSION['supplier_id'] : null;

$paramResult = checkParam('index');

if(is_numeric($paramResult)){

    $indexValue = validate($paramResult);

    if(isset($_SESSION['ingredientItems']) && isset($_SESSION['ingredientItemIds'])){
        unset($_SESSION['ingredientItems'][$indexValue]);
        unset($_SESSION['ingredientItemIds'][$indexValue]);

        // If supplier_id exists, pass it to the redirect URL
        if ($supplier_id) {
            redirect("purchase-order-create.php?track=$supplier_id", 'Item Removed!');
        } else {
            redirect("purchase-order-create.php?track=$supplier_id", 'Item Removed!');
        }
    } else {
        redirect("purchase-order-create.php?track=$supplier_id", 'There is no item.');
    }

} else {
    redirect("purchase-order-create.php?track=$supplier_id", 'param not numeric');
}
?>
