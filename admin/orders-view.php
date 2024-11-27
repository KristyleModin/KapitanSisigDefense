<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Order View
                <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
                <a href="orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Print</a>
            </h4>
        </div>
        <div class="card-body">

        <?php alertMessage(); ?>

        <?php
        if (isset($_GET['track'])) {
            if ($_GET['track'] == '') {
                ?>
                <div class="text-center py-5">
                    <h5>No tracking number found!</h5>
                    <div>
                        <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Orders View</a>
                    </div>
                </div>
                <?php
                return false;
            }

            $trackingNo = validate($_GET['track']);

            $query = "SELECT o.*, c.* FROM orders o 
                      INNER JOIN customers c ON c.id = o.customer_id 
                      WHERE o.tracking_no = '$trackingNo' ORDER BY o.id DESC";
            $orders = mysqli_query($conn, $query);

            $orderItemQuery = "SELECT oi.quantity AS orderItemQuantity, oi.price AS orderItemPrice, p.*, o.* 
                               FROM orders o 
                               INNER JOIN order_items oi ON oi.order_id = o.id 
                               INNER JOIN products p ON p.id = oi.product_id 
                               WHERE o.tracking_no = '$trackingNo'";
            $orderItemsRes = mysqli_query($conn, $orderItemQuery);

            if ($orders && mysqli_num_rows($orders) > 0) {
                $orderData = mysqli_fetch_assoc($orders);  
                $orderId = $orderData['id'];
                $totalQuantity = 0;

                // Calculate total quantity
                if ($orderItemsRes && mysqli_num_rows($orderItemsRes) > 0) {
                    while ($orderItemRow = mysqli_fetch_assoc($orderItemsRes)) {
                        $totalQuantity += $orderItemRow['orderItemQuantity'];
                    }
                }
                
                // Reset result pointer for order items for rendering
                mysqli_data_seek($orderItemsRes, 0);
                ?>
                <div class="card card-body shadow border-1 mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Order Details</h4>
                            <label class="mb-1">
                                Tracking No: <span class="fw-bold"><?= htmlspecialchars($orderData['tracking_no'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <label class="mb-1">
                                Order Date: <span class="fw-bold"><?= htmlspecialchars($orderData['order_date'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <label class="mb-1">
                                Order Status: <span class="fw-bold"><?= htmlspecialchars($orderData['order_status'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <label class="mb-1">
                                Order placed by: <span class="fw-bold"><?= htmlspecialchars($orderData['order_placed_by_id'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <?php if ($orderData['order_status'] === 'Cancelled'): ?>
                                <label class="mb-1">
                                    Order cancelled by: <span class="fw-bold"><?= htmlspecialchars($orderData['order_placed_by_id'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                            <?php endif; ?>
                            <label class="mb-1">
                                Total Quantity: <span class="fw-bold"><?= htmlspecialchars($totalQuantity, ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                        </div>

                        <div class="col-md-6">
                            <h4>Customer Details</h4>
                            <label class="mb-1">
                                Name: <span class="fw-bold"><?= htmlspecialchars($orderData['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <label class="mb-1">
                                Invoice No: <span class="fw-bold"><?= htmlspecialchars($orderData['invoice_no'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                            <label class="mb-1">
                                Payment Method: <span class="fw-bold"><?= htmlspecialchars($orderData['payment_mode'], ENT_QUOTES, 'UTF-8'); ?></span>
                            </label>
                            <br/>
                        </div>
                    </div>
                </div>

                <h4 class="my-3">Order Items Details</h4>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalAmount = 0;
                        while ($orderItemRow = mysqli_fetch_assoc($orderItemsRes)) {
                            $itemTotal = $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'];
                            $totalAmount += $itemTotal;
                            ?>
                            <tr>
                                <td>
                                    <img src="<?= $orderItemRow['image'] != '' ? '../'.$orderItemRow['image'] : '../pics/uploads/no-img.jpg'; ?>"
                                         style="width:50px;height:50px;" alt="Img"/>
                                    <?= htmlspecialchars($orderItemRow['productname'], ENT_QUOTES, 'UTF-8'); ?>
                                </td>
                                <td class="text-center">Php <?= number_format($orderItemRow['orderItemPrice'], 2); ?></td>
                                <td class="text-center"><?= $orderItemRow['orderItemQuantity']; ?></td>
                                <td class="fw-bold text-center">Php <?= number_format($itemTotal, 2); ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                        <!-- Display Total, Amount Received, and Change -->
                        <tr>
                            <td class="text-end fw-bold">Total Price: </td>
                            <td colspan="3" class="text-end fw-bold">Php <?= number_format($orderData['total_amount'], 2); ?></td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Amount Received: </td>
                            <td colspan="3" class="text-end fw-bold">Php <?= number_format($orderData['amount_received'], 2); ?></td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Change: </td>
                            <td colspan="3" class="text-end fw-bold">Php <?= number_format($orderData['change_money'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<h5>No record found!</h5>';
            }
        } else {
            ?>
            <div class="text-center py-5">
                <h5>No tracking number found!</h5>
                <div>
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Orders View</a>
                </div>
            </div>
            <?php
        }
        ?>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>