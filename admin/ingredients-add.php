<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Ingredient
                <a href="ingredients-view.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">
                <!-- Ingredient Name -->
                <div class="mb-3">
                    <label for="name">Ingredient Name:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <!-- Category Selection -->
                <div class="mb-3">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="Meat & Poultry">Meat & Poultry</option>
                        <option value="Vegetables">Vegetables</option>
                        <option value="Condiments">Condiments</option>
                        <option value="Spices & Herbs">Spices & Herbs</option>
                        <option value="Others">Others</option>
                        <option value="Cutlery">Cutlery</option>
                    </select>
                </div>


                <!-- Unit Selection -->
                <!-- <div class="mb-3">
                    <label for="unit_id">Select Unit:</label>
                    <select id="unit_id" name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        <?php
                        // Fetch all active units from the database
                        $unitResult = getAll('units_of_measure'); // Adjust this function as needed
                        if ($unitResult && mysqli_num_rows($unitResult) > 0) {
                            while ($uItem = mysqli_fetch_assoc($unitResult)) {
                                echo '<option value="'.htmlspecialchars($uItem['id']).'">'.htmlspecialchars($uItem['uom_name']).'</option>';
                            }
                        } else {
                            echo '<option value="">No units found!</option>';
                        }
                        ?>
                    </select>
                </div> -->
                
                <!-- Unit Selection -->
                <div class="mb-3">
                    <label for="unit_id">Select Unit:</label>
                    <select id="unit_id" name="unit_id" class="form-select" required>
                        <option value="">Select Unit</option>
                        <?php
                        // Fetch all active units with type 'reference' from the database
                        $unitResult = mysqli_query($conn, "SELECT id, uom_name FROM units_of_measure WHERE type = 'reference' AND active = 1");
                        if ($unitResult && mysqli_num_rows($unitResult) > 0) {
                            while ($uItem = mysqli_fetch_assoc($unitResult)) {
                                echo '<option value="'.htmlspecialchars($uItem['id']).'">'.htmlspecialchars($uItem['uom_name']).'</option>';
                            }
                        } else {
                            echo '<option value="">No reference units found!</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="reorder_point">Reorder Point:</label>
                    <input type="decimal" id="reorder_point" name="reorder_point" class="form-control" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="saveIngredient" class="btn btn-outline-primary">Save Ingredient</button>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>