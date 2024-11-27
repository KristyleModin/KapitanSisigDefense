<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Unit of Measure Category: 
                <?php 
                    // Fetch the category name from the database using the category ID from the URL
                    $category_id = $_GET['id'];
                    $category_sql = "SELECT category_unit_name FROM unit_categories WHERE id = $category_id";
                    $category_result = $conn->query($category_sql);
                    $category = $category_result->fetch_assoc();
                    echo htmlspecialchars($category['category_unit_name']);
                ?>
                <a href="units.php" class="btn btn-outline-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <form action="code.php" method="POST">
                <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Unit of Measure</th>
                                <th>Type</th>
                                <th>Ratio</th>
                                <th>Active</th>
                                <th>Rounding Precision</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch all UoMs for this category, regardless of active status
                            $uom_sql = "SELECT * FROM units_of_measure WHERE category_id = $category_id";
                            $uom_result = $conn->query($uom_sql);

                            if ($uom_result->num_rows > 0) {
                                while($uom = $uom_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($uom['uom_name']) . "</td>";

                                    // UoM type options
                                    echo "<td>
                                        <select name='uom_type[]' class='form-control'>
                                            <option value='reference' " . ($uom['type'] == 'reference' ? 'selected' : '') . ">Reference Unit of Measure</option>
                                            <option value='bigger' " . ($uom['type'] == 'bigger' ? 'selected' : '') . ">Bigger than Reference</option>
                                            <option value='smaller' " . ($uom['type'] == 'smaller' ? 'selected' : '') . ">Smaller than Reference</option>
                                        </select>
                                    </td>";

                                    // Ratio
                                    echo "<td><input type='text' name='ratio[]' value='" . htmlspecialchars($uom['ratio']) . "' class='form-control'></td>";

                                    // Active checkbox
                                    echo "<td><input type='checkbox' name='active[]' value='" . $uom['id'] . "' " . ($uom['active'] ? 'checked' : '') . "></td>";

                                    // Rounding Precision
                                    echo "<td><input type='text' name='rounding_precision[]' value='" . htmlspecialchars($uom['rounding_precision']) . "' class='form-control'></td>";

                                    // Store UoM ID for editing
                                    echo "<input type='hidden' name='uom_id[]' value='" . $uom['id'] . "'>";

                                    // Delete button
                                    echo "<td><a href='code.php?delete_uom=" . $uom['id'] . "' class='btn btn-sm btn-outline-danger'>Delete</a></td>";

                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No Units of Measure found for this category.</td></tr>";
                            }
                            ?>
                            <!-- Add new UoM -->
                            <tr>
                                <td><input type="text" name="new_uom_name" class="form-control" placeholder="Add New Unit of Measure"></td>
                                <td>
                                    <select name="new_uom_type" class="form-control">
                                        <option value="reference">Reference Unit of Measure</option>
                                        <option value="bigger">Bigger than Reference</option>
                                        <option value="smaller">Smaller than Reference</option>
                                    </select>
                                </td>
                                <td><input type="text" name="new_ratio" class="form-control" value="1.00000"></td>
                                <td><input type="checkbox" name="new_active" checked></td>
                                <td><input type="text" name="new_rounding_precision" class="form-control" value="0.00000"></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-end">
                    <button type="submit" name="save_uom" class="btn btn-outline-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>