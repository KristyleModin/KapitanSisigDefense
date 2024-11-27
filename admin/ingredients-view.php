<?php include('includes/header.php'); ?>

<?php
// Updated SQL query to join the ingredients and units_of_measure tables
$query = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name 
          FROM ingredients 
          LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Ingredients
                <a href="ingredients-add.php" class="btn btn-outline-primary float-end">Add Ingredient</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #000;">
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) : 
                    $i++;
                    ?>
                        <tr style="background-color: #f9f9f9; border: 1px solid #dee2e6;">
                            <td width="25%" style="font-size: 18px; line-height: 30px;">
                                <?php echo htmlspecialchars($row['name']); ?>
                                <div style="font-size: 16px; line-height: 24px; color: #555;">
                                    <?php echo htmlspecialchars($row['category']); ?>
                                </div>
                            </td>
                            <td width="25%">
                                <div>
                                    <?php echo htmlspecialchars($row['quantity']); ?> <?php echo htmlspecialchars($row['unit_name'] ? $row['unit_name'] : 'N/A'); ?>
                                </div>
                            </td>
                            <td width="20%">
                                <?php 
                                    if($row['quantity'] == 0) {
                                        echo '<span class="badge bg-danger">NO STOCK</span>';
                                    } else if($row['quantity'] <= $row['reorder_point']) {
                                        echo '<span class="badge bg-warning">Low Stock</span>';
                                    } else {
                                        echo '<span class="badge bg-primary">In Stock</span>';
                                    }
                                ?>
                            </td>
                            <td width="30%">
                                <a href="ingredients-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="ingredients-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a>
                                <!-- Change to a link that directs to ingredients-stock.php -->
                                <a href="ingredients-stock.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">
                                    View Stock
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?><?php include('includes/header.php'); ?>

<?php
// Updated SQL query to join the ingredients and units_of_measure tables
$query = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name 
          FROM ingredients 
          LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id";
$result = mysqli_query($conn, $query);
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Ingredients
                <a href="ingredients-add.php" class="btn btn-outline-primary float-end">Add Ingredient</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #000;">
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    while ($row = mysqli_fetch_assoc($result)) : 
                    $i++;
                    ?>
                        <tr style="background-color: #f9f9f9; border: 1px solid #dee2e6;">
                            <td width="25%" style="font-size: 18px; line-height: 30px;">
                                <?php echo htmlspecialchars($row['name']); ?>
                                <div style="font-size: 16px; line-height: 24px; color: #555;">
                                    <?php echo htmlspecialchars($row['category']); ?>
                                </div>
                            </td>
                            <td width="25%">
                                <div>
                                    <?php echo htmlspecialchars($row['quantity']); ?> <?php echo htmlspecialchars($row['unit_name'] ? $row['unit_name'] : 'N/A'); ?>
                                </div>
                            </td>
                            <td width="20%">
                                <?php 
                                    if($row['quantity'] == 0) {
                                        echo '<span class="badge bg-danger">NO STOCK</span>';
                                    } else if($row['quantity'] <= $row['reorder_point']) {
                                        echo '<span class="badge bg-danger">Low Stock</span>';
                                    } else {
                                        echo '<span class="badge bg-primary">In Stock</span>';
                                    }
                                ?>
                            </td>
                            <td width="30%">
                                <a href="ingredients-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="ingredients-delete.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a>
                                <!-- Change to a link that directs to ingredients-stock.php -->
                                <a href="ingredients-stock.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-secondary btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">
                                    View Stock
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include('includes/footer.php'); ?>