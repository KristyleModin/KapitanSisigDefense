<?php
require '../config/function.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if 'id' parameter is set and is numeric
$paramResultId = checkParam('id');
if (is_numeric($paramResultId)) {
    $customerId = validate($paramResultId);

    $customer = getById('customers', $customerId);

    if ($customer['status'] == 200) {
        $customerDeleteRes = delete('customers', $customerId);
        if ($customerDeleteRes) {
            $_SESSION['message'] = 'Customer deleted successfully!';
            header('Location: customers.php');
            exit();
        } else {
            $_SESSION['message'] = 'Something went wrong.';
            header('Location: customers.php');
            exit();
        }
    } else {
        $_SESSION['message'] = $customer['message'];
        header('Location: customers.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Invalid ID.';
    header('Location: customers.php');
    exit();
}
?>