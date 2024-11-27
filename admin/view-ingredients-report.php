<?php
include('includes/header.php');

// Get the ID of the report to view
$report_id = $_GET['id'] ?? null;

if (!$report_id) {
    echo "<div class='alert alert-danger'>No report selected. Please go back and select a report to view.</div>";
    echo "<a href='inventory-management-view.php' class='btn btn-secondary'>Back to Reports</a>";
    exit;
}

// Fetch the report details
$query = "SELECT * FROM saved_ingredients_reports WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    echo "<div class='alert alert-danger'>Report not found. Please try again.</div>";
    echo "<a href='inventory-management-view.php' class='btn btn-secondary'>Back to Reports</a>";
    exit;
}

// Decode the report data
$report_data = json_decode($report['report_data'], true); // Decode into an associative array

if (!$report_data || !is_array($report_data)) {
    echo "<div class='alert alert-danger'>Failed to load report data. The data might be corrupted.</div>";
    echo "<a href='inventory-management-view.php' class='btn btn-secondary'>Back to Reports</a>";
    exit;
}

// Categorize the report data
$remaining_ingredients = array_filter($report_data, fn($item) => $item['type'] === 'Remaining');
$used_ingredients = array_filter($report_data, fn($item) => $item['type'] === 'Used');
$stockin_ingredients = array_filter($report_data, fn($item) => $item['type'] === 'Stock In');
$stockout_ingredients = array_filter($report_data, fn($item) => $item['type'] === 'Stock Out');
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <div class="card-body">
        <h3>Ingredients Report</h3>
        <p>Report Date: <?= $report['report_date']; ?></p>
        <p>Report Time: <?= $report['report_time']; ?></p>
        <p>Date Range: <?= $report['start_date']; ?> - <?= $report['end_date'] ?? 'N/A'; ?></p>

        <!-- Remaining Ingredients -->
        <h4>Remaining Ingredients</h4>
        <?php if (!empty($remaining_ingredients)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ingredient Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($remaining_ingredients as $ingredient): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['ingredient_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['quantity_used']); ?></td>
                            <td><?= htmlspecialchars($ingredient['unit_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No remaining ingredients data available.</p>
        <?php endif; ?>

        <!-- Used Ingredients -->
        <h4>Used Ingredients</h4>
        <?php if (!empty($used_ingredients)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ingredient Name</th>
                        <th>Quantity Used</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($used_ingredients as $ingredient): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['ingredient_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['quantity_used']). ' ' . htmlspecialchars($ingredient['unit_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No used ingredients data available.</p>
        <?php endif; ?>

        <!-- Stock-In Ingredients -->
        <h4>Stock-In Ingredients</h4>
        <?php if (!empty($stockin_ingredients)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ingredient Name</th>
                        <th>Stock-In Quantity</th>
                        <th>Total Quantity</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stockin_ingredients as $ingredient): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['ingredient_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['quantity_used']). ' ' . htmlspecialchars($ingredient['unit_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['total_quantity']); ?></td>
                            <td><?= htmlspecialchars($ingredient['expiry_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stock-in ingredients data available.</p>
        <?php endif; ?>

        <!-- Stock-Out Ingredients -->
        <h4>Stock-Out Ingredients</h4>
        <?php if (!empty($stockout_ingredients)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Ingredient Name</th>
                        <th>Stock-Out Quantity</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stockout_ingredients as $ingredient): ?>
                        <tr>
                            <td><?= htmlspecialchars($ingredient['batch_number']); ?></td>
                            <td><?= htmlspecialchars($ingredient['ingredient_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['quantity_used']). ' ' . htmlspecialchars($ingredient['unit_name']); ?></td>
                            <td><?= htmlspecialchars($ingredient['reason']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No stock-out ingredients data available.</p>
        <?php endif; ?>

        <a href="inventory-management-view.php" class="btn btn-secondary mt-3">Back to Reports</a>
    </div>
        </div>
