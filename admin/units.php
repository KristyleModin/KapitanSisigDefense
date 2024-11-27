<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Units of Measurement Categories
                <a href="units-category.php" class="btn btn-outline-primary float-end">Create New Unit Category</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Unit of Measurement Category</th>
                            <th>UoM</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Connect to the database
                        // $conn = new mysqli('localhost', 'root', '', 'units_db');
                        $sql = "SELECT * FROM unit_categories";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['category_unit_name']) . "</td>";

                                // Fetch only active UoMs for the category
                                $category_id = $row['id'];
                                $uom_sql = "SELECT * FROM units_of_measure WHERE category_id = $category_id AND active = 1"; // Only active UoMs
                                $uom_result = $conn->query($uom_sql);
                                $uoms = [];
                                if ($uom_result->num_rows > 0) {
                                    while ($uom_row = $uom_result->fetch_assoc()) {
                                        // Check if the UoM is the reference unit
                                        if ($uom_row['type'] == 'reference') {
                                            $uoms[] = "<strong>" . htmlspecialchars($uom_row['uom_name']) . "</strong>"; // Bold reference UoM
                                        } else {
                                            $uoms[] = htmlspecialchars($uom_row['uom_name']); // Regular UoM
                                        }
                                    }
                                }
                                echo "<td>" . implode(', ', $uoms) . "</td>";

                                echo "<td>
                                    <a href='units-view-category.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-info'>View</a>
                                    <a href='units-edit-category.php?id=" . $row['id'] . "' class='btn btn-sm btn-outline-warning'>Edit</a>
                                    <a href='code.php?delete=" . $row['id'] . "' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No categories found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>