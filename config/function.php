<?php

session_start();

require 'dbcon.php';

// Input field
function validate($inputData){
    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// Redirect from one page to another
function redirect($url, $message){
    $_SESSION['message'] = $message;  // Changed from 'status' to 'message'
    header('Location: '. $url);
    exit(0);
}


// Display messages/status after any process
function alertMessage(){
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6>'.htmlspecialchars($_SESSION['message']).'</h6>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        unset($_SESSION['message']);  // Unset after displaying
    }
}


// Create new record
function insert($tableName, $data){
    
    global $conn;

    $table = validate($tableName);

    $columns = array_keys($data);
    $values = array_values($data);

    $finalColumn = implode(',', $columns);
    $finalValues = "'".implode("','", $values)."'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn,$query);
    return $result;
}

// Update data 
function update($tableName, $id, $data){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $updateDataString = "";

    foreach($data as $column => $value){
        $updateDataString .= $column. '='."'$value',";
    }

    $finalUpdateData = substr(trim($updateDataString),0,-1);

    $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo 'Query Error: ' . mysqli_error($conn);
    }

    return $result;
}


function getAll($tableName, $status = NULL){
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    $query = ($status == 'status') ? "SELECT * FROM $table WHERE $status = '0'" : "SELECT * FROM $table";
    return mysqli_query($conn, $query);
}

function getById($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_assoc($result);
            $response = [
                'status' =>200,
                'data' => $row,
                'message' => 'Record Found'
            ];
            return $response;
        }else{
            $response = [
                'status' =>404,
                'message' => 'No Data Found'
            ];
            return $response;
        }
    }else{
        $response = [
            'status' =>500,
            'message' => 'Something Went Wrong'
        ];
        return $response;
    }
}

// Delete Data
function delete($tableName, $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo 'Query Error: ' . mysqli_error($conn);
    }

    return $result;
}

// Check parameter
function checkParam($type){
    if(isset($_GET[$type])){
        if($_GET[$type] != ''){
            return $_GET[$type];
        }else{
            return '<h5>No ID found.</h5>';
        }
    }else{
        return '<h5>No ID given.</h5>';
    }
}

function jsonResponse($status, $status_type, $message ){
    $response = [
        'status' => $status,
        'status_type' => $status_type,
        'message' => $message
    ];

    echo json_encode($response);
    return;
}
  
function logoutSession() {
    unset($_SESSION['loggedIn']);
    unset($_SESSION['loggedInUser']);
}

function getIngredientQuantity($ingredient_id) {
    global $conn;

    $query = "SELECT quantity FROM ingredients WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ingredient_id); // 'i' indicates integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ingredient = $result->fetch_assoc();
    return $ingredient ? $ingredient['quantity'] : 0;  // Return the quantity or 0 if not found
}


// Function to get recipe by product ID
function getRecipeByProductId($product_id) {
    global $conn;

    $query = "SELECT * FROM recipes WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id); // 'i' indicates integer
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc(); // Fetch one record
}

// Function to get all ingredients for a recipe
function getRecipeIngredients($recipe_id) {
    global $conn;

    $query = "SELECT * FROM recipe_ingredients WHERE recipe_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $recipe_id); // 'i' indicates integer
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as an associative array
}


// Function to calculate available product quantity based on ingredients
function calculateProductQuantity($product_id) {
    $recipe = getRecipeByProductId($product_id);
    if (!$recipe) {
        return 0;  // No recipe found for the product
    }

    $ingredients = getRecipeIngredients($recipe['id']);
    if (!$ingredients) {
        return 0;  // No ingredients found for the recipe
    }

    $min_available_quantity = PHP_INT_MAX;  // Start with a very large number to find the minimum

    // Loop through each ingredient and calculate the possible number of products
    foreach ($ingredients as $ingredient) {
        $ingredient_id = $ingredient['ingredient_id'];
        $ingredient_quantity_required = $ingredient['quantity'];  // Quantity needed per product for this ingredient

        // Get available quantity of the ingredient
        $available_quantity = getIngredientQuantity($ingredient_id);

        // Calculate how many products can be made with the available ingredient
        if ($ingredient_quantity_required > 0) {
            $possible_quantity = floor($available_quantity / $ingredient_quantity_required);

            // Update the minimum quantity based on the limiting ingredient
            if ($possible_quantity < $min_available_quantity) {
                $min_available_quantity = $possible_quantity;
            }
        }
    }

    return $min_available_quantity;
}



// // Function to save inventory report into the database
// function saveInventoryReport($conn, $report_date, $start_date, $end_date, $remainingProducts, $completedOrders, $cancelledOrders) {
//     // Prepare the report data as a JSON or serialized string
//     $report_data = json_encode([
//         'remaining_products' => $remainingProducts,
//         'completed_orders' => $completedOrders,
//         'cancelled_orders' => $cancelledOrders,
//         'start_date' => $start_date,
//         'end_date' => $end_date
//     ]);

//     // Get the current time for the report
//     $report_time = date('H:i:s');
    
//     // Prepare SQL query to insert the report into the saved_reports table
//     $query = "
//         INSERT INTO saved_products_reports (report_date, report_time, start_date, end_date, report_data)
//         VALUES ('$report_date', '$report_time', '$start_date', '$end_date', '$report_data')
//     ";

//     // Execute the query and check if the insertion was successful
//     if (mysqli_query($conn, $query)) {
//         // Get the last inserted ID
//         return mysqli_insert_id($conn);
//     } else {
//         return false;
//     }
// }


// Function to fetch the saved report by ID from the database
function getReportById($conn, $reportId) {
    $query = "SELECT * FROM saved_products_reports WHERE id = $reportId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// Function to decode the JSON report data
function decodeReportData($reportData) {
    return json_decode($reportData, true);
}






?>