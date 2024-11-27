<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Saved Reports</h4>
        </div>
        <div class="card-body">
            <!-- Add Bootstrap Tabs -->
            <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="products-tab" data-bs-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">Products</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ingredients-tab" data-bs-toggle="tab" href="#ingredients" role="tab" aria-controls="ingredients" aria-selected="false">Ingredients</a>
                </li>
            </ul>
            <div class="tab-content mt-3" id="reportTabsContent">
                <!-- Products Tab -->
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <?php
                    // Fetch all saved reports for products
                    $query = "SELECT * FROM saved_products_reports WHERE report_type = 'products' ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                    ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Report Date</th>
                                    <th>Report Time</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($report = mysqli_fetch_assoc($result)): ?>
                                    <?php
                                    // Decode the report data to extract start and end date
                                    $reportData = json_decode($report['report_data'], true);
                                    
                                    $startDate = $report['start_date'] ?? 'N/A';
                                    $endDate = $report['end_date'] ?? 'N/A';
                                    ?>
                                    <tr>
                                        <td><?= $report['report_date']; ?></td>
                                        <td><?= $report['report_time']; ?></td>
                                        <td><?= ($startDate != 'N/A') ? $startDate : 'N/A'; ?></td>
                                        <td><?= ($endDate != 'N/A') ? $endDate : 'N/A'; ?></td>
                                        <td>
                                            <!-- View Report Link -->
                                            <a href="view-products-report.php?id=<?= $report['id']; ?>" class="btn btn-info btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo '<div class="alert alert-warning">No saved product reports found.</div>';
                    }
                    ?>
                </div>

                <!-- Ingredients Tab -->
                <div class="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                    <?php
                    // Fetch all saved reports for ingredients
                    $query = "SELECT * FROM saved_ingredients_reports WHERE report_type = 'ingredients' ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                    ?>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Report Date</th>
                                    <th>Report Time</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($report = mysqli_fetch_assoc($result)): ?>
                                    <?php
                                    // Decode the report data to extract start and end date
                                    $reportData = json_decode($report['report_data'], true);
                                    
                                    $startDate = $report['start_date'] ?? 'N/A';
                                    $endDate = $report['end_date'] ?? 'N/A';
                                    ?>
                                    <tr>
                                        <td><?= $report['report_date']; ?></td>
                                        <td><?= $report['report_time']; ?></td>
                                        <td><?= ($startDate != 'N/A') ? $startDate : 'N/A'; ?></td>
                                        <td><?= ($endDate != 'N/A') ? $endDate : 'N/A'; ?></td>
                                        <td>
                                            <!-- Correct View Report Link -->
                                            <a href="view-ingredients-report.php?id=<?= $report['id']; ?>" class="btn btn-info btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo '<div class="alert alert-warning">No saved ingredient reports found.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Include Bootstrap JS and dependencies (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"></script>
