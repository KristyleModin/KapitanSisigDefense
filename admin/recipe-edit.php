<?php 
include('includes/header.php'); 

if (isset($_GET['id'])) {
    $recipeId = $_GET['id'];

    // Fetch the recipe details
    $recipeQuery = "SELECT * FROM recipes WHERE id = '$recipeId' LIMIT 1";
    $recipeResult = mysqli_query($conn, $recipeQuery);
    $recipe = mysqli_fetch_assoc($recipeResult);

    if ($recipe) {
        $productId = $recipe['product_id'];

        // Fetch product details
        $productQuery = "SELECT * FROM products WHERE id = '$productId' LIMIT 1";
        $productResult = mysqli_query($conn, $productQuery);
        $product = mysqli_fetch_assoc($productResult);

        // Fetch recipe ingredients for this recipe
        $ingredientQuery = "
            SELECT ri.id AS recipe_ingredient_id, ri.ingredient_id, ri.unit_id, ri.quantity, i.name AS ingredient_name, u.uom_name AS unit_name
            FROM recipe_ingredients ri
            JOIN ingredients i ON ri.ingredient_id = i.id
            JOIN units_of_measure u ON ri.unit_id = u.id
            WHERE ri.recipe_id = '$recipeId'
        ";
        $ingredientResult = mysqli_query($conn, $ingredientQuery);

        // Fetch all ingredients and units for dropdowns
        $ingredients = mysqli_query($conn, "SELECT * FROM ingredients");
        $units = mysqli_query($conn, "SELECT * FROM units_of_measure"); // Changed to units_of_measure
    } else {
        echo "Recipe not found!";
        exit();
    }
}
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Recipe for <?= htmlspecialchars($product['productname']); ?>
                <a href="recipe-view.php?id=<?= $productId ?>" class="btn btn-secondary float-end me-2">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <h5>Description: <?= htmlspecialchars($product['description']); ?></h5>
            <h5>Price: <?= htmlspecialchars($product['price']); ?></h5>
            <hr />

            <form action="code.php" method="POST">

                <input type="hidden" name="recipe_id" value="<?= $recipeId ?>">
                <input type="hidden" name="product_id" value="<?= $productId ?>">

                <!-- Ingredients Table -->
                <table id="ingredientsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredients Name</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php if (mysqli_num_rows($ingredientResult) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($ingredientResult)): ?>
                                <tr>
                                    <td>
                                        <select name="ingredient_id[]" class="form-select" required>
                                            <option value="">Select Ingredient</option>
                                            <?php 
                                            mysqli_data_seek($ingredients, 0); // Reset ingredient pointer
                                            while ($ingredient = mysqli_fetch_assoc($ingredients)): ?>
                                                <option value="<?= $ingredient['id']; ?>" <?= ($ingredient['id'] == $row['ingredient_id']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($ingredient['name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="unit_id[]" class="form-select" required>
                                            <option value="">Select Unit</option>
                                            <?php 
                                            mysqli_data_seek($units, 0); // Reset unit pointer
                                            while ($unit = mysqli_fetch_assoc($units)): ?>
                                                <option value="<?= $unit['id']; ?>" <?= ($unit['id'] == $row['unit_id']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($unit['uom_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" class="form-control" value="<?= htmlspecialchars($row['quantity']); ?>" required>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="remove_recipe_ingredient_id[]" value="<?= $row['recipe_ingredient_id']; ?>" /> Remove Ingredient
                                    </td>
                                    <input type="hidden" name="recipe_ingredient_id[]" value="<?= $row['recipe_ingredient_id']; ?>"> <!-- Keep track of existing ID -->
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Add Row Button -->
                <button type="button" class="btn btn-outline-success addRow">Add Ingredient</button>
                
                <!-- Submit Button -->
                <button type="submit" name="updateRecipe" class="btn btn-outline-primary">Save Update</button>
            </form>
        </div>
    </div>
</div>

<!-- Store the options for reuse in JS -->
<div id="ingredientOptions" style="display: none;">
    <select name="ingredient_id[]" class="form-select" required>
        <option value="">Select Ingredient</option>
        <?php
        $ingredientResult = mysqli_query($conn, "SELECT * FROM ingredients");
        while ($ingredient = mysqli_fetch_assoc($ingredientResult)) {
            echo '<option value="' . $ingredient['id'] . '">' . htmlspecialchars($ingredient['name']) . '</option>';
        }
        ?>
    </select>
</div>

<div id="unitOptions" style="display: none;">
    <select name="unit_id[]" class="form-select" required>
        <option value="">Select Unit</option>
        <?php
        $unitResult = mysqli_query($conn, "SELECT * FROM units_of_measure"); // Changed to units_of_measure
        while ($unit = mysqli_fetch_assoc($unitResult)) {
            echo '<option value="' . $unit['id'] . '">' . htmlspecialchars($unit['uom_name']) . '</option>'; // Adjusted field
        }
        ?>
    </select>
</div>

<?php include('includes/footer.php'); ?>

<script>
// JavaScript for dynamically adding and removing rows
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const addRowButton = document.querySelector(".addRow");

    // Function to add a new row
    addRowButton.addEventListener("click", function () {
        const newRow = document.createElement("tr");

        // Use the stored ingredient and unit options from the hidden divs
        newRow.innerHTML = `
            <td>
                ${document.getElementById("ingredientOptions").innerHTML}
            </td>
            <td>
                ${document.getElementById("unitOptions").innerHTML}
            </td>
            <td>
                <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger removeRow">Remove</button>
            </td>
        `;
        tableBody.appendChild(newRow);

        // Add event listener to the new row's remove button
        newRow.querySelector(".removeRow").addEventListener("click", function () {
            newRow.remove();
        });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll(".removeRow").forEach(button => {
        button.addEventListener("click", function () {
            const row = button.closest("tr");
            row.remove();
        });
    });
});
</script>