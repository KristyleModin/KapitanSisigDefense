<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Products Inventory Report</h4>
        </div>
        <div class="card-body">
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab" aria-controls="products" aria-selected="true">
                        Products
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="ingredients-tab" href="inventory-management-report-ingredients.php" role="tab" aria-controls="ingredients" aria-selected="false">
                        Ingredients
                    </a>
                </li>
            </ul>
            
            <!-- Tabs Content -->
            <div class="tab-content mt-3" id="inventoryTabsContent">
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <h5>Products Management</h5>
                    <?php alertMessage(); ?>

                    <?php
                        // Initialize the selected date range or default to current day
                        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                        $selectedDate = isset($_GET['report_date']) ? $_GET['report_date'] : date('Y-m-d');

                        // Today's date for condition check
                        $todayDate = date('Y-m-d');

                        // Fetch Completed Orders for the selected date or range
                        function fetchOrders($status, $startDate = '', $endDate = '') {
                            global $conn;
                            $dateCondition = '';
                            if ($startDate && $endDate) {
                                $dateCondition = "AND DATE(o.order_date) BETWEEN '$startDate' AND '$endDate'";
                            } elseif ($startDate) {
                                $dateCondition = "AND DATE(o.order_date) = '$startDate'";
                            } elseif ($endDate) {
                                $dateCondition = "AND DATE(o.order_date) = '$endDate'";
                            }

                            $query = "
                                SELECT oi.*, o.tracking_no, p.productname, oi.quantity
                                FROM order_items oi
                                JOIN orders o ON o.id = oi.order_id
                                JOIN products p ON p.id = oi.product_id
                                WHERE o.order_status = '$status' $dateCondition
                            ";
                            return mysqli_query($conn, $query);
                        }

                        // Fetch Cancelled Orders for the selected date or range
                        function fetchCancelledOrders($startDate = '', $endDate = '') {
                            global $conn;
                            $dateCondition = '';
                            if ($startDate && $endDate) {
                                $dateCondition = "AND DATE(o.order_date) BETWEEN '$startDate' AND '$endDate'";
                            } elseif ($startDate) {
                                $dateCondition = "AND DATE(o.order_date) = '$startDate'";
                            } elseif ($endDate) {
                                $dateCondition = "AND DATE(o.order_date) = '$endDate'";
                            }

                            $query = "
                                SELECT oi.*, o.tracking_no, p.productname, oi.quantity
                                FROM order_items oi
                                JOIN orders o ON o.id = oi.order_id
                                JOIN products p ON p.id = oi.product_id
                                WHERE o.order_status = 'Cancelled' $dateCondition
                            ";
                            return mysqli_query($conn, $query);
                        }

                        // Fetch Remaining Products directly from the products table (just the quantity)
                        function fetchRemainingProducts() {
                            global $conn;
                            $query = "
                                SELECT productname, quantity
                                FROM products
                            ";
                            return mysqli_query($conn, $query);
                        }

                        // Fetch the data
                        $completedOrdersResult = fetchOrders('Completed', $startDate, $endDate);
                        if (mysqli_num_rows($completedOrdersResult) == 0) {
                            $noCompletedOrdersMessage = "No data available for the selected dates.";
                        }

                        $cancelledOrdersResult = fetchCancelledOrders($startDate, $endDate);
                        if (mysqli_num_rows($cancelledOrdersResult) == 0) {
                            $noCancelledOrdersMessage = "No data available for the selected dates.";
                        }

                        $remainingProductsResult = fetchRemainingProducts();
                        if (mysqli_num_rows($remainingProductsResult) == 0) {
                            $noRemainingProductsMessage = "No data available for the selected dates.";
                        }

                        // Determine if remaining products should be fetched
                        $saveRemainingProducts = false;

                        // If no start and end date are selected, or only start date is selected (for today's date)
                        if ((!$startDate && !$endDate) || ($startDate && !$endDate) || ($startDate == $todayDate)) {
                            $saveRemainingProducts = true;  // Only save remaining products if criteria match
                        }

                        // Handle saving the report if the form is submitted
                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_products_report'])) {
                            // Get the report date and time from the form
                            $reportDate = $_POST['report_date'];
                            $reportTime = date('H:i:s');  // Current time
                            $reportData = [
                                'remaining_products' => [],
                                'completed_orders' => [],
                                'cancelled_orders' => [],
                            ];

                            // Prepare the report data if remaining products should be saved
                            if ($saveRemainingProducts) {
                                while ($product = mysqli_fetch_assoc($remainingProductsResult)) {
                                    $reportData['remaining_products'][] = $product;
                                }
                            }

                            while ($order = mysqli_fetch_assoc($completedOrdersResult)) {
                                $reportData['completed_orders'][] = $order;
                            }

                            while ($order = mysqli_fetch_assoc($cancelledOrdersResult)) {
                                $reportData['cancelled_orders'][] = $order;
                            }

                            // Capture start and end dates for the report
                            $startDateForReport = $startDate ?: null;  // If no start date, set as NULL
                            $endDateForReport = $endDate ?: null;      // If no end date, set as NULL

                            // Save the report data into the database
                            $jsonReportData = json_encode($reportData);
                            $query = "
                                INSERT INTO saved_products_reports   (report_date, report_time, start_date, end_date, report_data)
                                VALUES ('$reportDate', '$reportTime', '$startDateForReport', '$endDateForReport', '$jsonReportData')
                            ";

                            if (mysqli_query($conn, $query)) {
                                $message = "Report saved successfully!";
                                echo "<div class='alert alert-success'>$message</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error saving the report: " . mysqli_error($conn) . "</div>";
                            }
                        }
                    ?>

                    <!-- Date Picker Form -->
                    <form method="GET" class="mb-3">
                        <label for="start_date" class="form-label">Select Start Date:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control mb-3" value="<?= $startDate ?>" />
                        
                        <label for="end_date" class="form-label">Select End Date (Optional):</label>
                        <input type="date" name="end_date" id="end_date" class="form-control mb-3" value="<?= $endDate ?>" />

                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>

                    <!-- Report Details -->
                    <div class="mb-3">
                        <p><strong>Report Date Range:</strong> <?= $startDate ? $startDate : "All"; ?> - <?= $endDate ? $endDate : "Current Day"; ?></p>
                    </div>

                    <!-- Display Remaining Products if the start date is today -->
                    <?php if (($startDate && !$endDate) && $startDate == $todayDate): ?>
                        <h5>Remaining Products</h5>
                        <?php if (isset($noRemainingProductsMessage)): ?>
                            <div class="alert alert-warning"><?= $noRemainingProductsMessage; ?></div>
                        <?php else: ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Menu Product Name</th>
                                        <th>Remaining Quantity</th>
                                        <!-- <th style="width: 175px;">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($product = mysqli_fetch_assoc($remainingProductsResult)): ?>
                                        <tr>
                                            <td><?= $product['productname']; ?></td>
                                            <td><?= $product['quantity']; ?></td>
                                            <!-- <td><a href="orders-view.php?product=<?= $product['productname']; ?>" class="btn btn-info btn-sm w-100">View</a></td> -->
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Display Completed Orders if either:
                        - A start date and no end date (but hide Remaining Products if not today)
                        - Both start and end dates are selected -->
                    <?php if (($startDate && !$endDate) || ($startDate && $endDate)): ?>
                        <h5>Completed Orders</h5>
                        <?php if (isset($noCompletedOrdersMessage)): ?>
                            <div class="alert alert-warning"><?= $noCompletedOrdersMessage; ?></div>
                        <?php else: ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tracking Number</th>
                                        <th>Menu Product Name</th>
                                        <th>Quantity</th>
                                        <!-- <th style="width: 175px;">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = mysqli_fetch_assoc($completedOrdersResult)): ?>
                                        <tr>
                                            <td class="text-center"><?= $order['tracking_no']; ?></td>
                                            <td><?= $order['productname']; ?></td>
                                            <td><?= $order['quantity']; ?></td>
                                            <!-- <td><a href="orders-view.php?id=<?= $order['id']; ?>" class="btn btn-info btn-sm w-100">View Order</a></td> -->
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Display Cancelled Orders if either:
                        - A start date and no end date (but hide Remaining Products if not today)
                        - Both start and end dates are selected -->
                    <?php if (($startDate && !$endDate) || ($startDate && $endDate)): ?>
                        <h5>Cancelled Orders</h5>
                        <?php if (isset($noCancelledOrdersMessage)): ?>
                            <div class="alert alert-warning"><?= $noCancelledOrdersMessage; ?></div>
                        <?php else: ?>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tracking Number</th>
                                        <th>Menu Product Name</th>
                                        <th>Quantity</th>
                                        <!-- <th style="width: 175px;">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($order = mysqli_fetch_assoc($cancelledOrdersResult)): ?>
                                        <tr>
                                            <td class="text-center"><?= $order['tracking_no']; ?></td>
                                            <td><?= $order['productname']; ?></td>
                                            <td><?= $order['quantity']; ?></td>
                                            <!-- <td><a href="orders-view.php?id=<?= $order['id']; ?>" class="btn btn-info btn-sm w-100">View Order</a></td> -->
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Save Products Report -->
                    <form method="POST">
                        <input type="hidden" name="report_date" value="<?= $selectedDate; ?>" />
                        <button type="submit" name="save_products_report" class="btn btn-success">Save Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
