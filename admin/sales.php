<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Sales Management</h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            
            <!-- Date Filter Form -->
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <div class="col-auto align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            
            <?php
                // Initialize total sales
                $totalSales = 0;
                $totalPurchaseOrders = 0; // Initialize total for purchase orders
                $salesData = []; // For chart data
                $purchaseOrderData = []; // For purchase order chart data
                $labels = []; // For chart labels

                // Check if the form is submitted
                if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                    $startDate = $_GET['start_date'];
                    $endDate = $_GET['end_date'];

                    // Prepare query to get total sales grouped by date for completed orders
                    $query = "SELECT DATE(order_date) as order_date, SUM(total_amount) as total_sales 
                              FROM orders 
                              WHERE order_status = 'Completed' 
                              AND order_date BETWEEN '$startDate' AND '$endDate' 
                              GROUP BY DATE(order_date) 
                              ORDER BY order_date ASC";
                    $salesResult = mysqli_query($conn, $query);

                    if ($salesResult) {
                        while ($row = mysqli_fetch_assoc($salesResult)) {
                            $labels[] = $row['order_date'];
                            $salesData[] = (float)$row['total_sales'];
                            $totalSales += $row['total_sales'];
                        }
                    }
                    
                    // Prepare the orders data for completed orders
                    $ordersQuery = "SELECT o.*, c.* FROM orders o, customers c 
                                    WHERE c.id = o.customer_id 
                                    AND o.order_status = 'Completed' 
                                    AND o.order_date BETWEEN '$startDate' AND '$endDate' 
                                    ORDER BY o.id DESC";
                    $orders = mysqli_query($conn, $ordersQuery);

                    if ($orders) {
                        if (mysqli_num_rows($orders) > 0) {
                            ?>
                            <div class="mt-4">
                                <h5>Completed Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th>Total Amount</th>
                                            <th style="width: 175px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $orderItem): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                <td><?= $orderItem['name']; ?></td>
                                                <td><?= $orderItem['order_date']; ?></td>
                                                <td><?= $orderItem['order_status']; ?></td>
                                                <td><?= $orderItem['payment_mode']; ?></td>
                                                <td><?= number_format($orderItem['total_amount'], 2); ?></td>
                                                <td><a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <h5>Total Sales: <?= number_format($totalSales, 2); ?></h5>
                            </div>
                            <?php
                        } else {
                            echo "<h5>No Completed Orders Available for Selected Dates</h5>";
                        }
                    } else {
                        echo "<h5>Something went wrong</h5>";
                    }

                   // Now fetching delivered purchase orders
                    $purchaseOrdersQuery = "SELECT po.*, a.* FROM purchaseOrders po, admins a 
                    WHERE a.id = po.customer_id 
                    AND po.order_status = 'Delivered' 
                    AND po.order_date >= '$startDate' 
                    AND po.order_date < DATE_ADD('$endDate', INTERVAL 1 DAY)
                    ORDER BY po.order_date DESC";
                    $purchaseOrders = mysqli_query($conn, $purchaseOrdersQuery);

                    if ($purchaseOrders) {
                        if (mysqli_num_rows($purchaseOrders) > 0) {
                            ?>
                            <div class="mt-4">
                                <h5>Delivered Purchase Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th style="width: 250px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchaseOrders as $ingredientItem): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                                <td><?= $ingredientItem['firstname']; ?></td>
                                                <td><?= $ingredientItem['order_date']; ?></td>
                                                <td><?= $ingredientItem['order_status']; ?></td>
                                                <td><?= $ingredientItem['ingPayment_mode']; ?></td>
                                                <td>
                                                    <a href="purchase-orders-view.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-info mb-0 px-2 btn-sm w-100">View</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- Total Sales for Purchase Orders -->
                                <?php
                                // Calculate total for purchase orders
                                $totalPurchaseOrdersQuery = "SELECT SUM(total_amount) as total_purchase FROM purchaseOrders 
                                                  WHERE order_status = 'Delivered' 
                                                  AND order_date >= '$startDate' 
                                                  AND order_date < DATE_ADD('$endDate', INTERVAL 1 DAY)";
                                $totalPurchaseOrdersResult = mysqli_query($conn, $totalPurchaseOrdersQuery);
                                if ($totalPurchaseOrdersResult) {
                                    $totalPurchaseRow = mysqli_fetch_assoc($totalPurchaseOrdersResult);
                                    $totalPurchaseOrders = $totalPurchaseRow['total_purchase'] ? (float)$totalPurchaseRow['total_purchase'] : 0;
                                }
                                ?>
                                <h5>Total Expenses: <?= number_format($totalPurchaseOrders, 2); ?></h5>
                            </div>
                            <?php
                        } else {
                            echo "<h5>No Delivered Purchase Orders Available for Selected Dates</h5>";
                        }
                    } else {
                        echo "<h5>Something went wrong fetching purchase orders</h5>";
                    }
                }
            ?>
            
            <!-- Chart for Total Sales -->
            <div class="mt-4">
                <canvas id="salesChart"></canvas>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar', // You can change this to 'bar', 'pie', etc.
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [
                {
                    label: 'Total Sales from Orders',
                    data: <?= json_encode($salesData); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                },
                {
                    label: 'Total Expenses from Purchase Orders',
                    data: <?= json_encode(array_fill(0, count($labels), $totalPurchaseOrders)); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Sales (in your currency)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
</script>

<?php include('includes/footer.php'); ?>