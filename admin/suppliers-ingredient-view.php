<?php
include('includes/header.php');

// Check if the supplier ID is set
if (isset($_GET['id'])) {
    $supplierId = intval($_GET['id']);

    // Fetch supplier details
    $supplierQuery = "SELECT * FROM suppliers WHERE id = $supplierId";
    $supplierResult = mysqli_query($conn, $supplierQuery);
    $supplier = mysqli_fetch_assoc($supplierResult);

    // Fetch ingredients associated with the supplier
    $ingredientsQuery = "
        SELECT ingredients.name AS ingredient_name, 
            uom.uom_name AS unit_name, 
            supplier_ingredients.price
        FROM supplier_ingredients
        JOIN ingredients ON supplier_ingredients.ingredient_id = ingredients.id
        LEFT JOIN units_of_measure uom ON supplier_ingredients.unit_id = uom.id
        WHERE supplier_ingredients.supplier_id = $supplierId
    ";

    $ingredientsResult = mysqli_query($conn, $ingredientsQuery);
    $hasIngredients = mysqli_num_rows($ingredientsResult) > 0; // Check if any ingredients are found

    $hasIngredients = mysqli_num_rows($ingredientsResult) > 0; // Check if any ingredients are found
} else {
    // Redirect or show an error if the ID is not set
    header('Location: suppliers.php');
    exit;
}
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Supplier Details
                <a href="suppliers.php" class="btn btn-outline-secondary float-end me-2">Back</a>
                <a href="suppliers-ingredient-update.php?id=<?= urlencode($supplierId); ?>" class="btn btn-outline-success float-end me-2 <?= !$hasIngredients ? 'disabled' : ''; ?>">Update</a>
            </h4>
        </div>
        <div class="card-body">
            <?php if ($supplier): ?>
                <h5>Supplier Name: <?= htmlspecialchars($supplier['firstname'] . ' ' . $supplier['lastname']) ?></h5>
                <h6>Address: <?= htmlspecialchars($supplier['address']) ?></h6>
                <h6>Phone: <?= htmlspecialchars($supplier['phonenumber']) ?></h6>

                <h5 class="mt-4">Associated Ingredients</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredient Name</th>
                            <th>Unit of Measure</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($hasIngredients): ?>
                            <?php while ($ingredient = mysqli_fetch_assoc($ingredientsResult)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($ingredient['ingredient_name']) ?></td>
                                    <td><?= htmlspecialchars($ingredient['unit_name']) ?></td>
                                    <td><?= htmlspecialchars(number_format($ingredient['price'], 2)) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No ingredients found for this supplier.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <h6 class="text-danger">Supplier not found.</h6>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

