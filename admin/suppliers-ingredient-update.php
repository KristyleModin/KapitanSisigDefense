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
        SELECT si.id AS supplier_ingredient_id, 
               ingredients.name AS ingredient_name, 
               si.price, 
               si.unit_id,
               uom.uom_name AS unit_name
        FROM supplier_ingredients si
        JOIN ingredients ON si.ingredient_id = ingredients.id
        LEFT JOIN units_of_measure uom ON si.unit_id = uom.id
        WHERE si.supplier_id = $supplierId
    ";
    $ingredientsResult = mysqli_query($conn, $ingredientsQuery);
    $hasIngredients = mysqli_num_rows($ingredientsResult) > 0;
} else {
    // Redirect or show an error if the ID is not set
    header('Location: suppliers.php');
    exit;
}

// Fetch all ingredients for the select dropdown
$allIngredientsQuery = "SELECT id, name FROM ingredients";
$allIngredientsResult = mysqli_query($conn, $allIngredientsQuery);
?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Update Supplier Ingredients
                <a href="suppliers.php" class="btn btn-outline-secondary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                <input type="hidden" name="supplier_id" value="<?= htmlspecialchars($supplierId); ?>">

                <h5>Supplier Name: <?= htmlspecialchars($supplier['firstname'] . ' ' . $supplier['lastname']); ?></h5>
                
                <!-- Existing Ingredients -->
                <h5>Existing Ingredients</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredient Name</th>
                            <th>Unit of Measure</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($hasIngredients): ?>
                            <?php while ($ingredient = mysqli_fetch_assoc($ingredientsResult)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($ingredient['ingredient_name']); ?></td>
                                    <td>
                                        <select name="unit_id[]" class="form-select" required>
                                            <?php
                                            // Fetch all UoMs from the database
                                            $uomQuery = "SELECT id, uom_name FROM units_of_measure";
                                            $uomResult = mysqli_query($conn, $uomQuery);

                                            if ($uomResult && mysqli_num_rows($uomResult) > 0) {
                                                while ($uom = mysqli_fetch_assoc($uomResult)) {
                                                    $selected = ($uom['id'] == $ingredient['unit_id']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($uom['id']) . '" ' . $selected . '>' . htmlspecialchars($uom['uom_name']) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No UoMs found</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="price[]" class="form-control" value="<?= htmlspecialchars($ingredient['price']); ?>" placeholder="Enter Price" min="0" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="hidden" name="ingredient_id[]" value="<?= htmlspecialchars($ingredient['supplier_ingredient_id']); ?>">
                                        <button type="button" class="btn btn-danger removeRow">Remove</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No ingredients found for this supplier.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- New Ingredients -->
                <h5 class="mt-4">Add New Ingredients</h5>
                <table class="table table-bordered" id="newIngredientsTable">
                    <thead>
                        <tr>
                            <th>Ingredient Name</th>
                            <th>Unit of Measure</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="new_ingredient_id[]" class="form-select">
                                    <option value="">Select Ingredient</option>
                                    <?php while ($ingredient = mysqli_fetch_assoc($allIngredientsResult)): ?>
                                        <option value="<?= htmlspecialchars($ingredient['id']); ?>"><?= htmlspecialchars($ingredient['name']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </td>
                            <td>
                                <select name="new_unit_id[]" class="form-select">
                                    <?php
                                    // Fetch all UoMs for new ingredients
                                    $uomQuery = "SELECT id, uom_name FROM units_of_measure";
                                    $uomResult = mysqli_query($conn, $uomQuery);

                                    if ($uomResult && mysqli_num_rows($uomResult) > 0) {
                                        while ($uom = mysqli_fetch_assoc($uomResult)) {
                                            echo '<option value="' . htmlspecialchars($uom['id']) . '">' . htmlspecialchars($uom['uom_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No UoMs found</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="new_price[]" class="form-control" placeholder="Enter Price" min="0" step="0.01">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger removeRow">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button type="button" class="btn btn-outline-success" id="addRow">Add New Ingredient</button>

                <!-- Submit Button -->
                <button type="submit" name="updateSupplierIngredient" class="btn btn-outline-primary">Update Supplier Ingredients</button>
            </form>
        </div>
    </div>
</div>

<script>
// Add new row for new ingredients
document.getElementById('addRow').addEventListener('click', function () {
    var table = document.getElementById('newIngredientsTable').getElementsByTagName('tbody')[0];
    var newRow = table.insertRow();
    newRow.innerHTML = `
        <td>
            <select name="new_ingredient_id[]" class="form-select">
                <?php
                // Fetch all ingredients again for the new row
                mysqli_data_seek($allIngredientsResult, 0);
                while ($ingredient = mysqli_fetch_assoc($allIngredientsResult)) {
                    echo '<option value="' . htmlspecialchars($ingredient['id']) . '">' . htmlspecialchars($ingredient['name']) . '</option>';
                }
                ?>
            </select>
        </td>
        <td>
            <select name="new_unit_id[]" class="form-select">
                <?php
                // Fetch all UoMs again for the new row
                mysqli_data_seek($uomResult, 0);
                while ($uom = mysqli_fetch_assoc($uomResult)) {
                    echo '<option value="' . htmlspecialchars($uom['id']) . '">' . htmlspecialchars($uom['uom_name']) . '</option>';
                }
                ?>
            </select>
        </td>
        <td>
            <input type="number" name="new_price[]" class="form-control" placeholder="Enter Price" min="0" step="0.01">
        </td>
        <td>
            <button type="button" class="btn btn-danger removeRow">Remove</button>
        </td>
    `;
});

// Remove row for both existing and new ingredients
document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('removeRow')) {
        var row = e.target.closest('tr');
        row.parentNode.removeChild(row);
    }
});
</script>

<?php include('includes/footer.php'); ?>