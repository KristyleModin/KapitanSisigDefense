<?php 
include('includes/header.php'); 

// Display success or error messages if any
if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['message']); ?>
    </div>
    <?php unset($_SESSION['message']); // Clear the message after displaying it ?>
<?php endif; ?>

<?php 
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details
    $productQuery = "SELECT * FROM products WHERE id = '$productId' LIMIT 1";
    $productResult = mysqli_query($conn, $productQuery);
    $product = mysqli_fetch_assoc($productResult);

    // Fetch the recipe associated with this product
    $recipeQuery = "SELECT r.id AS recipe_id 
                    FROM recipes r 
                    WHERE r.product_id = '$productId' 
                    LIMIT 1";
    $recipeResult = mysqli_query($conn, $recipeQuery);
    
    if ($recipeResult && mysqli_num_rows($recipeResult) > 0) {
        $recipe = mysqli_fetch_assoc($recipeResult);
        $recipeId = $recipe['recipe_id'];

        // Fetch recipe ingredients for this recipe, sorted by category
        $ingredientQuery = "
            SELECT i.name AS ingredient_name, ri.quantity, u.uom_name AS unit_name, i.category
            FROM recipe_ingredients ri
            JOIN ingredients i ON ri.ingredient_id = i.id
            JOIN units_of_measure u ON ri.unit_id = u.id
            WHERE ri.recipe_id = '$recipeId'
            ORDER BY i.category, i.name
            ";
        $ingredientResult = mysqli_query($conn, $ingredientQuery);
    } else {
        // If no recipe is found, set $ingredientResult to null
        $ingredientResult = null;
    }
}
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Recipe for <?= htmlspecialchars($product['productname']); ?>
                <a href="products.php" class="btn btn-outline-secondary float-end me-2">Back</a>
                <?php if (isset($recipeId)): ?>
                    <a href="recipe-edit.php?id=<?= urlencode($recipeId); ?>" class="btn btn-outline-primary float-end me-2">Update Recipe</a>
                <?php endif; ?>
            </h4>
        </div>
        <div class="card-body">
            <h5>Description: <?= htmlspecialchars($product['description']); ?></h5>
            <h5>Price: <?= htmlspecialchars($product['price']); ?></h5>
            <hr />

            <h5>Ingredients</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($ingredientResult) && mysqli_num_rows($ingredientResult) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($ingredientResult)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['ingredient_name']); ?></td>
                                <td><?= htmlspecialchars($row['unit_name']); ?></td> <!-- Updated to use uom_name -->
                                <td><?= htmlspecialchars($row['quantity']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No ingredients found for this product. <?= isset($recipeId) ? "A recipe exists, but no ingredients have been added." : "No recipe exists for this product."; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>