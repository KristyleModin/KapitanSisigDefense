<?php 
include('includes/header.php'); 

// Get the ingredient ID from the URL parameter
$ingredientId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($ingredientId > 0) {
    // Query to fetch ingredient details (name, quantity, remarks)
    $ingredientQuery = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name 
                        FROM ingredients 
                        LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id
                        WHERE ingredients.id = $ingredientId";
    $ingredientResult = mysqli_query($conn, $ingredientQuery);
    $ingredient = mysqli_fetch_assoc($ingredientResult);
    
    if ($ingredient) {
        // Query to fetch the stock batches for the ingredient
        $stockQuery = "SELECT si.*, uom.uom_name AS unit_name, uom.ratio AS unit_ratio 
                       FROM stockin_ingredients si
                       JOIN units_of_measure uom ON uom.id = si.unit_id
                       WHERE si.ingredient_id = $ingredientId AND si.quantity > 0";
        $stockResult = mysqli_query($conn, $stockQuery);
    }
}
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">
                Stock for Ingredient: <?php echo htmlspecialchars($ingredient['name']); ?>
                <a href="ingredients-view.php" class="btn btn-outline-primary float-end">Back to Ingredients</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <!-- Display Stock Table -->
            <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #000;">
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Best By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($stockResult) > 0) {
                        while ($stock = mysqli_fetch_assoc($stockResult)) :
                    ?>
                        <tr style="background-color: #f9f9f9; border: 1px solid #dee2e6;">
                            <td width="25%"><?php echo htmlspecialchars($stock['stockin_id']); ?></td>
                            <td width="25%">
                                <?php echo number_format($stock['quantity'] * $stock['unit_ratio'], 2); ?> 
                                <?php echo htmlspecialchars($ingredient['unit_name']); ?>
                            </td>
                            <td width="20%"><?php echo htmlspecialchars($stock['expiryDate']); ?></td>
                            <td width="30%">
                                <!-- Fixed Stock Out Link -->
                                <a href="ingredients-stock-out.php?stockin_id=<?php echo $stock['stockin_id']; ?>&ingredient_id=<?php echo $ingredientId; ?>" 
                                   class="btn btn-outline-secondary btn-sm">Stock Out</a>
                            </td>
                        </tr>
                    <?php 
                        endwhile;
                    } else {
                        echo "<tr><td colspan='4'>No stock information available.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>