<?php
// Include header and functions
include('includes/header.php');

// Fetch date range parameters from GET request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;


// Initialize result variables
$ingredientsResult = null;
$usedIngredientsResult = null;
$stockInIngredientsResult = null;
$stockOutIngredientsResult = null; // Add variable for stock out

// Get today's date for comparison
$today_date = date('Y-m-d');

// Check if date range is set
if ($start_date || $end_date) {
    // Fetch ingredients from the database
    $query = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name 
              FROM ingredients 
              LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id";
    $ingredientsResult = mysqli_query($conn, $query);

    // Prepare the base query for used ingredients
    $usedIngredientsQuery = "
        SELECT 
            i.name AS ingredient_name,
            uom.uom_name AS unit_name,
            SUM(CAST(ri.quantity AS DECIMAL) * CAST(oi.quantity AS DECIMAL)) AS total_used
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id  -- Link to order_items
        JOIN products p ON oi.product_id = p.id    -- Link to products
        JOIN recipes r ON p.id = r.product_id
        JOIN recipe_ingredients ri ON r.id = ri.recipe_id
        JOIN ingredients i ON ri.ingredient_id = i.id
        LEFT JOIN units_of_measure uom ON i.unit_id = uom.id
        WHERE o.order_status = 'Completed' 
    ";

    // Add the date filter condition if start_date and end_date are provided
    if ($start_date && $end_date) {
        $usedIngredientsQuery .= " AND o.order_date BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
    } elseif ($start_date) {
        $usedIngredientsQuery .= " AND o.order_date >= '$start_date 00:00:00'";
    } elseif ($end_date) {
        $usedIngredientsQuery .= " AND o.order_date <= '$end_date 23:59:59'";
    }

    $usedIngredientsQuery .= " GROUP BY i.id";

    // Execute the query for used ingredients
    $usedIngredientsResult = mysqli_query($conn, $usedIngredientsQuery);

    // Fetch stock-in ingredients data based on the selected date
    $stockInIngredientsQuery = "
        SELECT 
            si.id AS stockin_id,
            si.stockin_date,
            i.name AS ingredient_name,
            si_ingredient.Quantity,
            si_ingredient.totalQuantity,
            si_ingredient.expiryDate,
            uom.uom_name AS unit_name
        FROM stockin si
        JOIN stockin_ingredients si_ingredient ON si.id = si_ingredient.stockin_id
        JOIN ingredients i ON si_ingredient.ingredient_id = i.id
        LEFT JOIN units_of_measure uom ON si_ingredient.unit_id = uom.id
        WHERE si.stockin_date >= '$start_date 00:00:00'
    ";

    if ($end_date) {
        $stockInIngredientsQuery .= " AND si.stockin_date <= '$end_date 23:59:59'";
    }

    // Execute the query for stock-in ingredients
    $stockInIngredientsResult = mysqli_query($conn, $stockInIngredientsQuery);

    // // Fetch stock-out ingredients data based on the selected date
    // $stockOutIngredientsQuery = "
    //     SELECT 
    //         so.id AS stockout_id,
    //         so.stockin_id,
    //         i.name AS ingredient_name,
    //         so.quantity,
    //         so.reason,
    //         uom.uom_name AS unit_name,
    //         so.created_at
    //     FROM stock_out so
    //     JOIN ingredients i ON so.ingredient_id = i.id
    //     LEFT JOIN units_of_measure uom ON i.unit_id = uom.id
    //     WHERE so.created_at >= '$start_date 00:00:00'
    // ";

    // Fetch stock-out ingredients data based on the selected date
        $stockOutIngredientsQuery = "
        SELECT 
            so.id AS stockout_id,
            so.stockin_id,  -- This is the batch_number
            i.name AS ingredient_name,
            so.quantity,
            so.reason,
            uom.uom_name AS unit_name,
            so.created_at
        FROM stock_out so
        JOIN ingredients i ON so.ingredient_id = i.id
        LEFT JOIN units_of_measure uom ON i.unit_id = uom.id
        WHERE so.created_at >= '$start_date 00:00:00'
        ";

            if ($end_date) {
            $stockOutIngredientsQuery .= " AND so.created_at <= '$end_date 23:59:59'";
        }

        $stockOutIngredientsResult = mysqli_query($conn, $stockOutIngredientsQuery);
     }

     // Determine if there is no data for the selected date range
    $noData = true;

    if ($ingredientsResult && mysqli_num_rows($ingredientsResult) > 0) {
        $noData = false;
    }

    if ($usedIngredientsResult && mysqli_num_rows($usedIngredientsResult) > 0) {
        $noData = false;
    }

    if ($stockInIngredientsResult && mysqli_num_rows($stockInIngredientsResult) > 0) {
        $noData = false;
    }

    if ($stockOutIngredientsResult && mysqli_num_rows($stockOutIngredientsResult) > 0) {
        $noData = false;
    }
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Ingredients Inventory Report</h4>
        </div>
        <div class="card-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="products-tab" href="inventory-management-report.php" role="tab" aria-controls="products" aria-selected="false">Products</a>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="true">
                        Ingredients
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content mt-3" id="inventoryTabsContent">
                <div class="tab-pane fade show active" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                <h5>Ingredients Management</h5>
                    <!-- Date Picker Form -->
                    <form method="GET" class="mb-3">
                        <label for="start_date" class="form-label">Select Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control mb-3" value="<?= htmlspecialchars($start_date); ?>" />

                        <label for="end_date" class="form-label">Select End Date (Optional):</label>
                        <input type="date" name="end_date" id="end_date" class="form-control mb-3" value="<?= htmlspecialchars($end_date); ?>" />

                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <?php if ($noData) : ?>
                        <!-- <p class="text-center text-muted">No data available for the selected dates.</p> -->
                    <?php else : ?>

                    <!-- Check if tables should be displayed -->
                    <?php if ($start_date || $end_date) : ?>
                        <!-- Remaining Ingredients Table -->
                        <?php if ($start_date && !$end_date) : ?>
                            <?php if ($start_date === $today_date) : ?>
                                <h5 class="mt-4">Remaining Ingredients</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($ingredientsResult)) : ?>
                                                <tr>
                                                    <td>
                                                        <?= htmlspecialchars($row['name']); ?>
                                                        <div class="text-muted" style="font-size: 0.9rem;">
                                                            <?= htmlspecialchars($row['category']); ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($row['quantity']); ?> <?= htmlspecialchars($row['unit_name'] ?? 'N/A'); ?>
                                                    </td>
                                                    <td>
                                                        <a href="ingredients-stock.php?id=<?= $row['id']; ?>" class="btn btn-outline-secondary btn-sm">
                                                            View Stock
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Used Ingredients Table -->
                        <h5 class="mt-4">Used Ingredients</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity Used</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($usedIngredientsResult) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($usedIngredientsResult)) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['ingredient_name']); ?></td>
                                                <td><?= htmlspecialchars($row['total_used']); ?> <?= htmlspecialchars($row['unit_name'] ?? 'N/A'); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No data available for the selected dates.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Stock In Ingredients Table -->
                        <h5 class="mt-4">Stock-In Ingredients</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Stock-in Quantity</th>
                                        <th>Total Quantity</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($row = mysqli_fetch_assoc($stockInIngredientsResult) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($stockInIngredientsResult)) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['ingredient_name']); ?></td>
                                            <td><?= htmlspecialchars($row['Quantity']); ?> <?= htmlspecialchars($row['unit_name'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($row['totalQuantity']); ?></td>
                                            <td><?= htmlspecialchars($row['expiryDate']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="2" class="text-center">No data available for the selected dates.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Stock Out Ingredients Table -->
                        <h5 class="mt-4">Stock-Out Ingredients</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Batch Number</th>
                                        <th>Name</th>
                                        <th>Stock-Out Quantity</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($row = mysqli_fetch_assoc($stockOutIngredientsResult) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($stockOutIngredientsResult)) :?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['stockin_id']); ?></td>
                                            <td><?= htmlspecialchars($row['ingredient_name']); ?></td>
                                            <td><?= htmlspecialchars($row['quantity']); ?> <?= htmlspecialchars($row['unit_name'] ?? 'N/A'); ?></td>
                                            <td><?= htmlspecialchars($row['reason']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="2" class="text-center">No data available for the selected dates.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Save Report Form -->
                        <form action="code.php" method="POST">
                            <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date); ?>" />
                            <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date); ?>" />
                            <button type="submit" name="saved_ingredients_report" class="btn btn-success">Save Ingredients Report</button>
                        </form>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

