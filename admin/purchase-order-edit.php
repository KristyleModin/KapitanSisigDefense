<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Purchase Order View
                <a href="purchase-orders.php" class="btn btn-danger mx-2 btn-sm float-end" style="text-decoration: none;">Back</a>
                <a href="purchase-orders-view-print.php?track=<?= $_GET['track'] ?>" class="btn btn-info mx-2 btn-sm float-end">Print</a>
            </h4>
        </div>
        <div class="card-body">

        <?php alertMessage();?>
        <form action="purchase-orders-code.php" method="POST" enctype="multipart/form-data">

        <?php

        if(isset($_GET['track'])){

            if($_GET['track']== ''){
                ?>
                <div class="text-center py-5">
                    <h5>No tracking number found!</h5>
                    <div>
                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Purchase Orders View</a>
                    </div>
                    
                    </div>
                <?php

                return false;
            }
            $trackingNo = validate($_GET['track']);

            $query = "SELECT po.*
                FROM purchaseOrders po 
                WHERE po.tracking_no = '$trackingNo' 
                ORDER BY po.id DESC";

            $customerQuery = "SELECT po.*, a.* FROM purchaseOrders po, admins a WHERE 
                a.id = po.customer_id AND tracking_no='$trackingNo' 
                ORDER BY po.id DESC";

            $orders = mysqli_query($conn, $query);
            $customers = mysqli_query($conn, $customerQuery);

            if($orders){

                if(mysqli_num_rows($orders) > 0){
                    $orderData = mysqli_fetch_assoc($orders);
                    $customerData = mysqli_fetch_assoc($customers);

                    $supplierName = $orderData['supplierName'];
                    $supplier = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");
                    $supplierData = mysqli_fetch_assoc($supplier);

                    $orderId = $orderData['id'];

                    ?>
                    <input type="hidden" name="order_id" id="order_id" value="<?= $orderId; ?>">
                    <div class="card card-body shadow border-1 mb-4 pb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Supplier Details</h4>
                                <label class="mb-1">
                                    Supplier Name: <span class="fw-bold"><?= htmlspecialchars($supplierData['firstname'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($supplierData['lastname'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Phone Number: <span class="fw-bold"><?= htmlspecialchars($supplierData['phonenumber'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Address: <span class="fw-bold"><?= htmlspecialchars($supplierData['address'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <br/>
                            </div>
                            <div class="col-md-6">
                                <h4>Customer Details</h4>
                                <label class="mb-1">
                                    Name: <span class="fw-bold"><?= htmlspecialchars($customerData['firstname'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($customerData['lastname'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Position: 
                                    <span class="fw-bold">
                                        <?= $customerData['position'] == 1 ? 'Owner' : 'Employee'?>
                                    </span>
                                </label>
                                <br />
                                <br />
                                <label>Select Order Status</label>
                                    <select id="order_status" class="form-select">
                                        <option value="Placed" <?= $orderData['order_status'] == 'Placed' ? 'selected' : ''; ?>>Placed</option>
                                        <option value="Pending" <?= $orderData['order_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Cancelled" <?= $orderData['order_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                            </div>
                        </div>
                    </div>


                    <?php
                       $orderItemQuery = "
                        SELECT 
                            ii.quantity as orderItemQuantity, 
                            ii.price as orderItemPrice, 
                            i.name as ingredientName,
                            uom.uom_name as unit_name
                        FROM purchaseOrders po 
                        JOIN ingredients_items ii ON ii.order_id = po.id 
                        JOIN ingredients i ON i.id = ii.ingredient_id 
                        JOIN units_of_measure uom ON uom.id = ii.unit_id
                        WHERE po.tracking_no = '$trackingNo'
                            ";   
    

                        $orderItemsRes = mysqli_query($conn, $orderItemQuery);
                        $i = 1;
                        if($orderItemsRes){
                            if(mysqli_num_rows($orderItemsRes) > 0){
                                $totalAmount = 0;
                                ?>
                                    <h4 class="my-3">Order Items Details</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item No. </th>
                                                <th>Ingredient</th>
                                                <th>Price</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($orderItemRow = mysqli_fetch_assoc($orderItemsRes)): ?>
                                                <tr>
                                                    <td width="5%" class=" text-center">
                                                        <?= $i++; ?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                    <?= $orderItemRow['ingredientName'];?>
                                                    </td>
                                                    
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'], 2); ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['unit_name'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        <?= $orderItemRow['orderItemQuantity']; ?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity'], 2); ?>
                                                    </td> 
                                                </tr>
                                                <?php $totalAmount += $orderItemRow['orderItemPrice'] * $orderItemRow['orderItemQuantity']; ?>
                                            <?php endwhile; ?>

                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Price: </td>
                                                <td colspan="1" class="text-end fw-bold">Php <?= number_format($totalAmount, 2); ?></td>
                                            </tr>
                                        </tbody>

                                    </table>
                                <?php
                            }else{
                                echo ' <h5>No record Found!</h5>';
                                return false;
                            }
                        }else{
                            echo ' <h5>No record Found!</h5>';
                            return false;
                        }
                    ?>
                    <?php
                }else{
                    echo ' <h5>No record Found!</h5>';
                    return false;
                }

            }else{
                echo ' <h5>Something Went Wrong!</h5>';
            }
        }else{
            ?>
                <div class="text-center py-5">
                    <h5>No tracking number found!</h5>
                    <div>
                    <a href="purchaseorders.php" class="btn btn-primary mt-4 w-25">Return to Purchase Orders View</a>
                    </div>
                    
                </div>
            <?php
        }
        ?>
            <div>
                <div class="row d-flex justify-content-end">
                    <div class="col-md-4">
                        <br/>
                        <button type="button" class="btn btn-warning w-100 proceedToUpdateIng">Proceed to Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>