<?php 
ob_start(); // Start output buffering
session_start(); // Ensure session is started at the top of the file
include('includes/header.php'); 

// Enable error reporting to catch any issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the ingredient ID and stockin ID from the URL parameters
$ingredientId = isset($_GET['ingredient_id']) ? intval($_GET['ingredient_id']) : 0;
$stockinId = isset($_GET['stockin_id']) ? intval($_GET['stockin_id']) : 0;

if ($ingredientId > 0 && $stockinId > 0) {
    // Query to fetch ingredient details (name, unit_id)
    $ingredientQuery = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name, units_of_measure.ratio AS unit_ratio
                        FROM ingredients 
                        LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id
                        WHERE ingredients.id = $ingredientId";
    $ingredientResult = mysqli_query($conn, $ingredientQuery);
    $ingredient = mysqli_fetch_assoc($ingredientResult);

    // Query to fetch stock details for the specific ingredient and stockin_id
    $stockQuery = "SELECT si.*, uom.uom_name AS unit_name, uom.ratio AS unit_ratio
                   FROM stockin_ingredients si
                   JOIN units_of_measure uom ON uom.id = si.unit_id
                   WHERE si.stockin_id = $stockinId AND si.ingredient_id = $ingredientId";
    $stockResult = mysqli_query($conn, $stockQuery);
    $stock = mysqli_fetch_assoc($stockResult);
    
    if (!$ingredient || !$stock) {
        $_SESSION['error_message'] = "Ingredient or stock information not found.";
        header("Location: ingredients-view.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: ingredients-view.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = floatval($_POST['quantity']); // Stock-out quantity
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    // Validate input
    $availableQuantity = $stock['quantity'] * $stock['unit_ratio']; // Total stock in base unit
    if ($quantity <= 0 || $quantity > $availableQuantity) {
        $_SESSION['error_message'] = "Invalid stock-out quantity.";
        header("Location: ingredients-stock-out.php?stockin_id=$stockinId&ingredient_id=$ingredientId");
        exit;
    } else {
        // Convert the stock-out quantity to the stockin unit (if needed)
        $adjustedQuantityInStockinUnit = $quantity / $stock['unit_ratio'];

        // Record the stock-out
        $stockOutQuery = "INSERT INTO stock_out (stockin_id, ingredient_id, quantity, reason, created_at)
                          VALUES ($stockinId, $ingredientId, $quantity, '$reason', NOW())";

        // Update the stock in stockin_ingredients table (reduce stock)
        $updateStockinIngredientsQuery = "UPDATE stockin_ingredients 
                                          SET quantity = quantity - $adjustedQuantityInStockinUnit 
                                          WHERE stockin_id = $stockinId AND ingredient_id = $ingredientId";

        // Update the ingredients table (reduce total stock directly)
        $updateIngredientsQuery = "UPDATE ingredients 
                                   SET quantity = quantity - $quantity 
                                   WHERE id = $ingredientId";

        // Execute all queries
        $stockOutResult = mysqli_query($conn, $stockOutQuery);
        $updateStockinResult = mysqli_query($conn, $updateStockinIngredientsQuery);
        $updateIngredientsResult = mysqli_query($conn, $updateIngredientsQuery);

        if ($stockOutResult && $updateStockinResult && $updateIngredientsResult) {
            $_SESSION['success_message'] = "Successfully recorded stock-out for " . htmlspecialchars($ingredient['name']) . ".";
        } else {
            $_SESSION['error_message'] = "Failed to record stock-out. Please try again.";
        }

        header("Location: ingredients-view.php");
        exit;
    }
}
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Stock Out - <?php echo htmlspecialchars($ingredient['name']); ?></h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); // Display any session message ?>

            <p><strong>Ingredient Name:</strong> <?php echo htmlspecialchars($ingredient['name']); ?></p>

            <!-- Display Available Quantity -->
            <?php
            $converted_quantity = $stock['quantity'] * $stock['unit_ratio']; // Convert to base unit
            $unit_name = $ingredient['unit_name'] ?: 'N/A'; // If no unit, show N/A
            ?>
            <p><strong>Available Quantity:</strong> <?php echo number_format($converted_quantity, 2) . " " . $unit_name; ?></p>

            <form id="stockOutForm" method="POST" action="">
                <div class="mb-3">
                    <label for="quantity" class="form-label">
                        Stock-out Quantity (<?php echo $unit_name; ?>):
                    </label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        class="form-control" 
                        required 
                        min="1" 
                        max="<?php echo $converted_quantity; ?>" 
                        step="0.01">
                </div>
                <div class="mb-3">
                    <label for="reason" class="form-label">Reason:</label>
                    <select id="reason" name="reason" class="form-control" required>
                        <option value="Expired">Expired</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Loss">Loss</option>
                        <option value="Production">Production</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
                <a href="ingredients-stock.php?id=<?php echo $ingredient['id']; ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<?php ob_end_flush(); // Flush output buffer ?> 