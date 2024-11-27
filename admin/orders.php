<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Orders
                <a href="inventory-management-report.php" class="btn btn-outline-primary float-end">
                    Generate Inventory Report
                </a>
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>

        <?php
            // Query to fetch all orders
            $query = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id ORDER BY o.order_date ASC";
            $orders = mysqli_query($conn, $query);

            if ($orders) {
                if (mysqli_num_rows($orders) > 0) {
                    // Prepare arrays to hold orders by their status
                    $completedOrders = [];
                    $cancelledOrders = [];
                    $preparingOrders = [];
                    $placedOrders = [];

                    // Categorize orders based on their status
                    foreach ($orders as $orderItem) {
                        switch ($orderItem['order_status']) {
                            case 'Completed':
                                $completedOrders[] = $orderItem;
                                break;
                            case 'Cancelled':
                                $cancelledOrders[] = $orderItem;
                                break;
                            case 'Preparing':
                                $preparingOrders[] = $orderItem;
                                break;
                            case 'Placed':
                                $placedOrders[] = $orderItem;
                                break;
                        }
                    }

                    // Separate queries for "Placed Orders" and "Preparing Orders" in DESCending order
                    $placedOrdersQuery = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_status = 'Placed' ORDER BY o.order_date ASC";
                    $preparingOrdersQuery = "SELECT o.*, c.* FROM orders o, customers c WHERE c.id = o.customer_id AND o.order_status = 'Preparing' ORDER BY o.order_date ASC";


                    $placedOrdersResult = mysqli_query($conn, $placedOrdersQuery);
                    $preparingOrdersResult = mysqli_query($conn, $preparingOrdersQuery);
                    ?>

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="placed-orders-tab" data-bs-toggle="tab" href="#placedOrders" role="tab" aria-controls="placedOrders" aria-selected="true">Placed Orders</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="preparing-orders-tab" data-bs-toggle="tab" href="#preparingOrders" role="tab" aria-controls="preparingOrders" aria-selected="false">Preparing Orders</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="completed-orders-tab" data-bs-toggle="tab" href="#completedOrders" role="tab" aria-controls="completedOrders" aria-selected="false">Completed Orders</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cancelled-orders-tab" data-bs-toggle="tab" href="#cancelledOrders" role="tab" aria-controls="cancelledOrders" aria-selected="false">Cancelled Orders</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="orderTabsContent">
                        <!-- Placed Orders -->
                        <div class="tab-pane fade show active" id="placedOrders" role="tabpanel" aria-labelledby="placed-orders-tab">
                            <div class="mt-2">
                                <h5>Placed Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th style="width: 175px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($orderItem = mysqli_fetch_assoc($placedOrdersResult)): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                <td><?= $orderItem['name']; ?></td>
                                                <td><?= $orderItem['order_date']; ?></td>
                                                <td><?= $orderItem['order_status']; ?></td>
                                                <td><?= $orderItem['payment_mode']; ?></td>
                                                <td style="display: flex; justify-content: space-between; gap: 5px;">
                                                    <a href="order-edit.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-primary btn-sm w-100">Edit</a>
                                                    <a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Preparing Orders -->
                        <div class="tab-pane fade" id="preparingOrders" role="tabpanel" aria-labelledby="preparing-orders-tab">
                            <div class="mt-2">
                                <h5>Preparing Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th style="width: 175px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($orderItem = mysqli_fetch_assoc($preparingOrdersResult)): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                <td><?= $orderItem['name']; ?></td>
                                                <td><?= $orderItem['order_date']; ?></td>
                                                <td><?= $orderItem['order_status']; ?></td>
                                                <td><?= $orderItem['payment_mode']; ?></td>
                                                <td style="display: flex; justify-content: space-evenly; gap: 5px;">
                                                    <input type="hidden" name="order_track" value="<?= $orderItem['tracking_no']; ?>">
                                                    <button class="btn btn-warning btn-sm complete-btn w-100 proceedToComplete">Completed</button>
                                                    <a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Completed Orders -->
                        <div class="tab-pane fade" id="completedOrders" role="tabpanel" aria-labelledby="completed-orders-tab">
                            <div class="mt-2">
                                <h5>Completed Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th style="width: 175px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($completedOrders as $orderItem): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                <td><?= $orderItem['name']; ?></td>
                                                <td><?= $orderItem['order_date']; ?></td>
                                                <td><?= $orderItem['order_status']; ?></td>
                                                <td><?= $orderItem['payment_mode']; ?></td>
                                                <td><a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Cancelled Orders -->
                        <div class="tab-pane fade" id="cancelledOrders" role="tabpanel" aria-labelledby="cancelled-orders-tab">
                            <div class="mt-2">
                                <h5>Cancelled Orders</h5>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tracking No.</th>
                                            <th>Name</th>
                                            <th>Order Date</th>
                                            <th>Order Status</th>
                                            <th>Payment Method</th>
                                            <th style="width: 175px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cancelledOrders as $orderItem): ?>
                                            <tr>
                                                <td class="fw-bold"><?= $orderItem['tracking_no']; ?></td>
                                                <td><?= $orderItem['name']; ?></td>
                                                <td><?= $orderItem['order_date']; ?></td>
                                                <td><?= $orderItem['order_status']; ?></td>
                                                <td><?= $orderItem['payment_mode']; ?></td>
                                                <td><a href="orders-view.php?track=<?= $orderItem['tracking_no']; ?>" class="btn btn-info btn-sm w-100">View</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    echo "<p>No orders found.</p>";
                }
            }
        ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>