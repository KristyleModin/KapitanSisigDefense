<?php
include('includes/header.php'); 
?>

<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Enter Name</label>
                    <input type="text" class="form-control" id="c_name"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveCustomer">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Order
                <a href="orders.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
                <form action="orders-code.php" method="POST" enctype="multipart/form-data">
                <?php 
                    $trackingNo = validate($_GET['track']);

                    $query = "SELECT o.id AS order_id, o.customer_id, o.payment_mode, o.order_status 
                        FROM orders o 
                        WHERE o.tracking_no = '$trackingNo' 
                        LIMIT 1";

                    $orders = mysqli_query($conn, $query);
                    $orderData = mysqli_fetch_assoc($orders);  

                        ?>
                        <?php
                            if ($orders) {
                                $orderId = $orderData['order_id'];
                                $customerId = $orderData['customer_id'];
                            ?>
                                <input type="hidden" name="order_id" id="order_id" value="<?= $orderId; ?>">
                            <?php
                            } else {
                                echo "Order not found with tracking number: " . htmlspecialchars($trackingNo);
                            }
                            $customerQuery = "SELECT name FROM customers WHERE id = '$customerId' LIMIT 1";
                            $customerResult = mysqli_query($conn, $customerQuery);
                            $customerData = mysqli_fetch_assoc($customerResult);


                            ?>
            
                        <div class="card card-body shadow border-1 mb-4 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Customer Details</h4>
                                    <label class="mb-1">
                                        Name: <span class="fw-bold"><?= htmlspecialchars($customerData['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </label>
                                    <br/>
                                    <label class="mb-1">
                                        Payment Method: <span class="fw-bold"><?= htmlspecialchars($orderData['payment_mode'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label>Select Order Status</label>
                                    <select id="order_status" class="form-select" name="order_status">
                                        <option value="Placed" <?= $orderData['order_status'] == 'Placed' ? 'selected' : ''; ?>>Placed</option>
                                        <option value="Preparing" <?= $orderData['order_status'] == 'Preparing' ? 'selected' : ''; ?>>Preparing</option>
                                        <option value="Cancelled" <?= $orderData['order_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                            
                </form>

            <!-- Display existing order items -->
            <?php
            $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
            FROM orders as o, order_items as oi, products as p 
            WHERE  oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo' ";

            $orderItemsRes = mysqli_query($conn, $orderItemQuery);

            if($orderItemsRes): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">Products</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItemsRes as $item): ?>
                                    <tr>
                                        <td>
                                        <img src="<?= $item['image'] != '' ? '../'.$item['image'] : '../pics/uploads/no-img.jpg'; ?>"

                                            style="width:50px;height:50px;"
                                            alt="Img"/>

                                            <?= $item['productname'];?>
                                        </td>
                                        
                                        <td width="15%" class="text-center">
                                            Php <?= number_format($item['orderItemPrice'], 2); ?>
                                        </td>
                                        <td width="15%" class="text-center">
                                            <?= $item['orderItemQuantity']; ?>
                                        </td>
                                        <td width="15%" class="fw-bold text-center">
                                            Php <?= number_format($item['orderItemPrice'] * $item['orderItemQuantity'], 2); ?>
                                        </td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                        </div>
                        <?php 
                            $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.* 
                            FROM orders as o, order_items as oi, products as p 
                            WHERE  oi.order_id = o.id AND p.id = oi.product_id AND o.tracking_no='$trackingNo' ";
        
                            $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                            $orderData = mysqli_fetch_assoc($orders);  

                            $customer_id = $item['customer_id'];
                            $checkCustomer = mysqli_query($conn, "SELECT * FROM customers WHERE id='$customer_id' LIMIT 1");
                            $customerData = mysqli_fetch_assoc($checkCustomer);
                            
                        ?>
                        <div>
                            <div class="row d-flex justify-content-end">
                                <div class="col-md-4">
                                    <br/>
                                    <button type="button" class="btn btn-warning w-100 proceedToUpdate">Proceed to Update Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                
            <?php else: ?>
                <h5>No items added to the order yet.</h5>
            <?php endif; ?>
        
    </div>
</div>

<?php include('includes/footer.php'); ?>