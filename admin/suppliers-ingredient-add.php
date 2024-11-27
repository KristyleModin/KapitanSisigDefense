<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Supplier Ingredients
                <a href="suppliers.php" class="btn btn-outline-secondary float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                
                <!-- Supplier Selection -->
                <div class="col-md-12 mb-3">
                    <label>Select Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select Supplier</option>
                        <?php
                        // Fetch all suppliers
                        $suppliersQuery = "SELECT id, firstname FROM suppliers";
                        $suppliers = mysqli_query($conn, $suppliersQuery);
                        
                        if ($suppliers && mysqli_num_rows($suppliers) > 0) {
                            while ($supItem = mysqli_fetch_assoc($suppliers)) {
                                echo '<option value="' . htmlspecialchars($supItem['id']) . '">' . htmlspecialchars($supItem['firstname']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No suppliers found</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Ingredients Table -->
                <table id="ingredientsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredient</th>
                            <th>Unit of Measure</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Default Row -->
                        <tr>
                            <td>
                                <select name="ingredient_id[]" class="form-select ingredient-select" required>
                                    <option value="">Select Ingredient</option>
                                    <?php
                                    // Fetch all ingredients
                                    $ingredientsQuery = "SELECT id, name FROM ingredients";
                                    $ingredientsResult = mysqli_query($conn, $ingredientsQuery);
                                    
                                    if ($ingredientsResult && mysqli_num_rows($ingredientsResult) > 0) {
                                        while ($ingredient = mysqli_fetch_assoc($ingredientsResult)) {
                                            echo '<option value="' . htmlspecialchars($ingredient['id']) . '">' . htmlspecialchars($ingredient['name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No ingredients found</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="unit_id[]" class="form-select uom-select" required>
                                    <option value="">Select UoM</option>
                                    <?php
                                    // Fetch all UoMs from the database
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
                                <input type="number" name="price[]" class="form-control" placeholder="Enter Price" min="0" step="0.01" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger removeRow">Remove Ingredient</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Add Row Button -->
                <button type="button" class="btn btn-outline-success addRow mt-3">Add Ingredient</button>

                <!-- Submit Button -->
                <button type="submit" name="saveSupplierIngredient" class="btn btn-outline-primary mt-3">Save Supplier</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const addRowButton = document.querySelector(".addRow");

    // Store ingredient options and UoM options in variables
    const ingredientOptions = `<?php
        $ingredientsResult = mysqli_query($conn, "SELECT id, name FROM ingredients");
        $optionsHtml = '<option value=\"\">Select Ingredient</option>';
        if ($ingredientsResult && mysqli_num_rows($ingredientsResult) > 0) {
            while ($ingredient = mysqli_fetch_assoc($ingredientsResult)) {
                $optionsHtml .= '<option value=\"' . htmlspecialchars($ingredient['id']) . '\">' . htmlspecialchars($ingredient['name']) . '</option>';
            }
        } else {
            $optionsHtml .= '<option value=\"\">No ingredients found</option>';
        }
        echo $optionsHtml;
    ?>`;

    const uomOptions = `<?php
        $uomResult = mysqli_query($conn, "SELECT id, uom_name FROM units_of_measure");
        $uomHtml = '<option value=\"\">Select UoM</option>';
        if ($uomResult && mysqli_num_rows($uomResult) > 0) {
            while ($uom = mysqli_fetch_assoc($uomResult)) {
                $uomHtml .= '<option value=\"' . htmlspecialchars($uom['id']) . '\">' . htmlspecialchars($uom['uom_name']) . '</option>';
            }
        } else {
            $uomHtml .= '<option value=\"\">No UoMs found</option>';
        }
        echo $uomHtml;
    ?>`;

    // Function to dynamically add a new row
    addRowButton.addEventListener("click", function () {
        const newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td>
                <select name="ingredient_id[]" class="form-select" required>
                    ${ingredientOptions}
                </select>
            </td>
            <td>
                <select name="unit_id[]" class="form-select" required>
                    ${uomOptions}
                </select>
            </td>
            <td>
                <input type="number" name="price[]" class="form-control" placeholder="Enter Price" min="0" step="0.01" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger removeRow">Remove Ingredient</button>
            </td>
        `;
        tableBody.appendChild(newRow);

        // Add event listener to the new row's remove button
        newRow.querySelector(".removeRow").addEventListener("click", function () {
            newRow.remove();
        });
    });

    // Remove row functionality for initial row
    document.querySelectorAll(".removeRow").forEach(function(button) {
        button.addEventListener("click", function () {
            this.closest("tr").remove();
        });
    });
});
</script>

