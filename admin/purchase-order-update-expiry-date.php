<?php
session_start();

if (isset($_POST['ingredient_id']) && isset($_POST['expiry_date'])) {
    $ingredientId = $_POST['ingredient_id'];
    $expiryDate = $_POST['expiry_date'];

    // Check if the ingredient exists in the session
    if (isset($_SESSION['siItems'])) {
        foreach ($_SESSION['siItems'] as $key => $item) {
            if ($item['ingredient_id'] == $ingredientId) {
                // Update the expiry date in the session
                $_SESSION['siItems'][$key]['expiryDate'] = $expiryDate;
                echo 'Expiry date updated successfully.';
                exit;
            }
        }
    }

    echo 'Ingredient not found in session.';
} else {
    echo 'Invalid request.';
}
?>