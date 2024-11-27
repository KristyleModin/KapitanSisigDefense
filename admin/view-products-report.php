<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">View Products Saved Report</h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php
                // Check if report ID is passed in the URL
                if (isset($_GET['id'])) {
                    $reportId = $_GET['id'];

                    // Fetch the saved report from the database
                    $query = "SELECT * FROM saved_products_reports WHERE id = '$reportId'";
                    $result = mysqli_query($conn, $query);
                    
                    if (mysqli_num_rows($result) > 0) {
                        $report = mysqli_fetch_assoc($result);
                        $reportData = json_decode($report['report_data'], true);

                        // Get report details
                        $reportDate = $report['report_date'];
                        $reportTime = $report['report_time'];
                        $startDate = $report['start_date'];
                        $endDate = $report['end_date'];

                        // Get the report data (completed orders, cancelled orders, remaining products)
                        $completedOrders = $reportData['completed_orders'];
                        $cancelledOrders = $reportData['cancelled_orders'];
                        $remainingProducts = $reportData['remaining_products'];
                    } else {
                        echo "<div class='alert alert-danger'>Report not found.</div>";
                        exit;
                    }
                } else {
                    echo "<div class='alert alert-danger'>No report ID provided.</div>";
                    exit;
                }

                // Logic to decide whether to show remaining products
                // Show remaining products only if both start and end dates are not selected
                $showRemainingProducts = true;  // Default to show remaining products

                if ($startDate && $endDate) {
                    $showRemainingProducts = false;  // Hide remaining products if both start and end date are selected
                }

                // If today's date is selected, show remaining products
                $todayDate = date('Y-m-d');
                if ($startDate == $todayDate || $endDate == $todayDate || ($startDate && !$endDate)) {
                    $showRemainingProducts = true;
                }
            ?>

            <!-- Report Date Details -->
            <div class="mb-3">
                <p><strong>Report Date:</strong> <?= $reportDate; ?></p>
                <p><strong>Report Time:</strong> <?= $reportTime; ?></p>

                <?php if ($startDate && $endDate): ?>
                    <p><strong>Date Range:</strong> <?= $startDate; ?> - <?= $endDate; ?></p>
                <?php elseif ($startDate): ?>
                    <p><strong>Date:</strong> <?= $startDate; ?></p>
                <?php else: ?>
                    <p><strong>Date:</strong> N/A</p>
                <?php endif; ?>
            </div>

            <!-- Display Remaining Products if applicable -->
            <?php if ($showRemainingProducts && !empty($remainingProducts)): ?>
                <h5>Remaining Products</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Remaining Quantity</th>
                            <th style="width: 175px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($remainingProducts as $product): ?>
                            <tr>
                                <td><?= $product['productname']; ?></td>
                                <td><?= $product['quantity']; ?></td>
                                <td><a href="orders-view.php?product=<?= $product['productname']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (!$showRemainingProducts): ?>
                <p></p>
            <?php else: ?>
                <p></p>
            <?php endif; ?>

            <!-- Display Completed Orders -->
            <?php if (!empty($completedOrders)): ?>
                <h5>Completed Orders</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th style="width: 175px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completedOrders as $order): ?>
                            <tr>
                                <td><?= $order['tracking_no']; ?></td>
                                <td><?= $order['productname']; ?></td>
                                <td><?= $order['quantity']; ?></td>
                                <td><a href="orders-view.php?track=<?= $order['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No completed orders in this report.</p>
            <?php endif; ?>

            <!-- Display Cancelled Orders -->
            <?php if (!empty($cancelledOrders)): ?>
                <h5>Cancelled Orders</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th style="width: 175px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cancelledOrders as $order): ?>
                            <tr>
                                <td><?= $order['tracking_no']; ?></td>
                                <td><?= $order['productname']; ?></td>
                                <td><?= $order['quantity']; ?></td>
                                <td><a href="orders-view.php?track=<?= $order['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No cancelled orders in this report.</p>
            <?php endif; ?>

            <!-- Back to Reports Button -->
            <a href="inventory-management-view.php" class="btn btn-secondary mt-4">Back to Reports</a>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
