<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Recipe
                <a href="products.php" class="btn btn-outline-secondary float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">

                <!-- Recipe Selection -->
                <div class="col-md-12 mb-3">
                    <label>Select a Dish</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">Select Dish</option>
                        <?php
                        $products = getAll('products');
                        if ($products) {
                            if (mysqli_num_rows($products)) {
                                foreach ($products as $prodItem) {
                                    echo '<option value="' . $prodItem['id'] . '">' . $prodItem['productname'] . '</option>';
                                }
                            } else {
                                echo '<option value="">No categories found!</option>';
                            }
                        } else {
                            echo '<option value="">Something went wrong.</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- Ingredients Table -->
                <table id="ingredientsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Ingredients Name</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Default Row -->
                        <tr>
                            <td>
                                <select name="ingredient_id[]" class="form-select" required>
                                    <option value="">Select Ingredient</option>
                                    <?php
                                    $ingredients = getAll('ingredients');
                                    if ($ingredients && mysqli_num_rows($ingredients) > 0) {
                                        while ($cItem = mysqli_fetch_assoc($ingredients)) {
                                            echo '<option value="' . $cItem['id'] . '">' . htmlspecialchars($cItem['name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No ingredients found</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select name="unit_id[]" class="form-select" required>
                                    <option value="">Select Unit</option>
                                    <?php
                                    $units = getAll('units_of_measure'); // Changed to the correct table
                                    if ($units && mysqli_num_rows($units) > 0) {
                                        while ($uItem = mysqli_fetch_assoc($units)) {
                                            echo '<option value="' . htmlspecialchars($uItem['id']) . '">' . htmlspecialchars($uItem['uom_name']) . '</option>'; // Adjusted field
                                        }
                                    } else {
                                        echo '<option value="">No units found</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger removeRow">Remove Ingredient</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Add Row Button (moved here) -->
                <button type="button" class="btn btn-outline-success addRow">Add Ingredient</button>

                <!-- Submit Button -->
                <button type="submit" name="saveRecipe" class="btn btn-outline-primary">Save Recipe</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
// JavaScript for dynamically adding rows
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("tableBody");
    const addRowButton = document.querySelector(".addRow");

    // Function to add a new row
    addRowButton.addEventListener("click", function () {
        const newRow = document.createElement("tr");

        newRow.innerHTML = `
            <td>
                <select name="ingredient_id[]" class="form-select" required>
                    <option value="">Select Ingredient</option>
                    <?php
                    $ingredients = getAll('ingredients');
                    if ($ingredients && mysqli_num_rows($ingredients) > 0) {
                        while ($cItem = mysqli_fetch_assoc($ingredients)) {
                            echo '<option value="' . $cItem['id'] . '">' . htmlspecialchars($cItem['name']) . '</option>';
                        }
                    } else {
                        echo '<option value="">No ingredients found</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="unit_id[]" class="form-select" required>
                    <option value="">Select Unit</option>
                    <?php
                    $units = getAll('units_of_measure'); // Changed to the correct table
                    if ($units && mysqli_num_rows($units) > 0) {
                        while ($uItem = mysqli_fetch_assoc($units)) {
                            echo '<option value="' . htmlspecialchars($uItem['id']) . '">' . htmlspecialchars($uItem['uom_name']) . '</option>'; // Adjusted field
                        }
                    } else {
                        echo '<option value="">No units found</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <input type="number" name="quantity[]" class="form-control" placeholder="Quantity" required>
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
});
</script>