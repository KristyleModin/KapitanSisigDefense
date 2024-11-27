<?php include('includes/header.php');

unset($_SESSION['siItems']);
unset($_SESSION['siItemIds']);

?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Purchase Orders</h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
        <?php
            $query = "
                SELECT po.*, a.*, s.firstname AS supplierName 
                FROM purchaseOrders po
                JOIN admins a ON a.id = po.customer_id
                JOIN suppliers s ON s.id = po.supplierName
                ORDER BY po.order_date ASC
            ";
            $purchaseOrders = mysqli_query($conn, $query);
        

            if($purchaseOrders){
                if(mysqli_num_rows($purchaseOrders) > 0){

                    // Prepare arrays to hold orders by their status
                    $placedPurchaseOrders = [];
                    $pendingPurchaseOrders = [];
                    $deliveredPurchaseOrders = [];
                    $cancelledPurchaseOrders = [];

                    // Categorize orders based on their status
                    foreach($purchaseOrders as $orderItem) {
                        switch ($orderItem['order_status']) {
                            case 'Placed':
                                $placedPurchaseOrders[] = $orderItem;
                                break;
                            case 'Pending':
                                $pendingPurchaseOrders[] = $orderItem;
                                break;
                            case 'Delivered':
                                $deliveredPurchaseOrders[] = $orderItem;
                                break;
                            case 'Cancelled':
                                $cancelledPurchaseOrders[] = $orderItem;
                                break;
                        }
                    }
                    ?>

                     <!-- Nav Tabs -->
                     <ul class="nav nav-tabs" id="purchaseOrderTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="placed-purchase-orders-tab" data-bs-toggle="tab" href="#placedPurchaseOrders" role="tab" aria-controls="placedPurchaseOrders" aria-selected="true">Placed</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pending-purchase-orders-tab" data-bs-toggle="tab" href="#pendingPurchaseOrders" role="tab" aria-controls="pendingPurchaseOrders" aria-selected="false">Pending</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="delivered-purchase-orders-tab" data-bs-toggle="tab" href="#deliveredPurchaseOrders" role="tab" aria-controls="deliveredPurchaseOrders" aria-selected="false">Delivered</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="cancelled-purchase-orders-tab" data-bs-toggle="tab" href="#cancelledPurchaseOrders" role="tab" aria-controls="cancelledPurchaseOrders" aria-selected="false">Cancelled</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="purchaseOrderTabsContent">
                        <!-- Placed Purchase Orders -->
                        <div class="tab-pane mt-3 fade show active" id="placedPurchaseOrders" role="tabpanel" aria-labelledby="placed-purchase-orders-tab">
                            <h5>Placed Purchase Orders</h5>
                            <table class="table table-striped table-bordered align-items-center justify-content-center">
                                <thead>
                                    <tr>
                                        <th>Purchase Order No.</th>
                                        <th>Supplier</th>
                                        <th>Order Date</th>
                                        <th>Order Status</th>
                                        <th>Payment Method</th>
                                        <th style="width: 250px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($placedPurchaseOrders as $ingredientItem): ?>
                                        <tr>
                                            <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                            <td><?= $ingredientItem['supplierName']; ?></td>
                                            <td><?= $ingredientItem['order_date']; ?></td>
                                            <td><?= $ingredientItem['order_status']; ?></td>
                                            <td><?= $ingredientItem['ingPayment_mode']; ?></td>
                                            <td style="display: flex; justify-content: space-evenly; gap: 5px;">
                                                <input type="hidden" name="order_track" value="<?= $ingredientItem['tracking_no']; ?>">
                                                <a href="purchase-order-edit.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-primary btn-sm w-100">Edit</a>
                                                <a href="purchase-orders-view.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-info mb-0 px-2 btn-sm w-100">View</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <!-- Pending Purchase Orders -->
                    <div class="tab-pane mt-3 fade" id="pendingPurchaseOrders" role="tabpanel" aria-labelledby="pending-purchase-orders-tab">
                        <h5>Pending Purchase Orders</h5>
                        <table class="table table-striped table-bordered align-items-center justify-content-center">
                            <thead>
                                <tr>
                                    <th>Purchase Order No.</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Payment Method</th>
                                    <th style="width: 250px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($pendingPurchaseOrders as $ingredientItem): ?>
                                    <tr>
                                        <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                        <td><?= $ingredientItem['supplierName']; ?></td>
                                        <td><?= $ingredientItem['order_date']; ?></td>
                                        <td><?= $ingredientItem['order_status']; ?></td>
                                        <td><?= $ingredientItem['ingPayment_mode']; ?></td>
                                        <td style="display: flex; justify-content: space-evenly; gap: 5px;">
                                            <input type="hidden" name="order_track" id="order_track" value="<?= $ingredientItem['tracking_no']; ?>">
                                            <a href="purchase-order-stock-in.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-warning btn-sm complete-btn w-100">Delivered</a>
                                            <a href="purchase-orders-view.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-info mb-0 px-2 btn-sm w-100">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Delivered Purchase Orders -->
                    <div class="tab-pane mt-3 fade" id="deliveredPurchaseOrders" role="tabpanel" aria-labelledby="delivered-purchase-orders-tab">
                        <h5>Delivered Purchase Orders/Stock Ins</h5>
                        <table class="table table-striped table-bordered align-items-center justify-content-center">
                            <thead>
                                <tr>
                                    <th>Purchase Order No.</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Order Delivered</th>
                                    <th>Order Status</th>
                                    <th>Payment Method</th>
                                    <th style="width: 250px;">Action</th>
                                </tr>
                            </thead>
                                <tbody>
                                <?php foreach($deliveredPurchaseOrders as $ingredientItem): 
                                    // Fetch the purchase order ID using the tracking number
                                    $tracking_no = $ingredientItem['tracking_no'];
                                    $poQuery = mysqli_query($conn, "SELECT id FROM purchaseorders WHERE tracking_no='$tracking_no'");
                                    $poResult = mysqli_fetch_assoc($poQuery);
                                    $purchaseOrderId = $poResult['id']; // This is the actual purchase order ID
                                    
                                    // Now fetch the stockin_date using the purchase order ID
                                    $stockInQuery = mysqli_query($conn, "SELECT stockin_date FROM stockin WHERE purchaseorder_id='$purchaseOrderId'");
                                    $stockInResult = mysqli_fetch_assoc($stockInQuery);
                                ?>
                                    <tr>
                                        <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                        <td><?= $ingredientItem['supplierName']; ?></td>
                                        <td><?= $ingredientItem['order_date']; ?></td>
                                        <td><?= $stockInResult['stockin_date'] ?? 'N/A'; ?></td> <!-- Display stockin_date or 'N/A' if not found -->
                                        <td><?= $ingredientItem['order_status']; ?></td>
                                        <td><?= $ingredientItem['ingPayment_mode']; ?></td>
                                        <td>
                                            <a href="purchase-orders-view.php?track=<?= $ingredientItem['tracking_no']; ?>" class="btn btn-info mb-0 px-2 btn-sm w-100">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Cancelled Purchase Orders -->
                    <div class="tab-pane mt-3 fade" id="cancelledPurchaseOrders" role="tabpanel" aria-labelledby="cancelled-purchase-orders-tab">
                        <h5>Cancelled Purchase Orders</h5>
                        <table class="table table-striped table-bordered align-items-center justify-content-center">
                            <thead>
                                <tr>
                                    <th>Purchase Order No.</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Payment Method</th>
                                    <th style="width: 250px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($cancelledPurchaseOrders as $ingredientItem): ?>
                                    <tr>
                                        <td class="fw-bold"><?= $ingredientItem['tracking_no']; ?></td>
                                        <td><?= $ingredientItem['supplierName']; ?></td>
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
                    </div>
                    </div>
                    <?php
                } else {
                    echo "<h5>No Record Available</h5>";
                }
            } else {
                echo "<h5>Something went wrong</h5>";
            }
        ?>

        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.stockInBtn').forEach(button => {
    button.addEventListener('click', function() {
        const orderTrackInput = this.closest('td').querySelector('#order_track');
        const order_track = orderTrackInput ? orderTrackInput.value : null;
        
        if (order_track) {
            window.location.href = 'purchase-order-stock-in.php?track=' + order_track;
        } else {
            alert('Error!');
        }
    });
});
</script>

<?php include('includes/footer.php'); ?>