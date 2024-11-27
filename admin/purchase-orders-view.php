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

            $query = "SELECT po.*, a.*, po.id as purchaseorders_id, po.order_status as purchaseorder_status FROM purchaseOrders po, admins a WHERE 
                        a.id = po.customer_id AND tracking_no='$trackingNo' 
                        ORDER BY po.id DESC";

            $orders = mysqli_query($conn, $query);

            if($orders){

                if(mysqli_num_rows($orders) > 0){
                    $orderData = mysqli_fetch_assoc($orders);
                    $supplierName = $orderData['supplierName'];
                    $supplier = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");
                    $supplierData = mysqli_fetch_assoc($supplier);
                    $orderId = $orderData['purchaseorders_id'];

                    ?>
                    <div class="card card-body shadow border-1 mb-4 ">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Order Details</h4>
                                <label class="mb-1">
                                    Purchase Order No: <span class="fw-bold"><?= htmlspecialchars($orderData['tracking_no'], ENT_QUOTES, 'UTF-8'); ?></span>
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
                                    Payment Method: <span class="fw-bold"><?= htmlspecialchars($orderData['ingPayment_mode'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                            </div>
                            <div class="col-md-6">
                                <h4>Customer Details</h4>
                                <label class="mb-1">
                                    Name: <span class="fw-bold"><?= htmlspecialchars($orderData['firstname'], ENT_QUOTES, 'UTF-8'); ?> <?= htmlspecialchars($orderData['lastname'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </label>
                                <br/>
                                <label class="mb-1">
                                    Position: 
                                    <span class="fw-bold">
                                        <?= $orderData['position'] == 1 ? 'Owner' : 'Employee'?>
                                    </span>
                                </label>
                                <br/>
                                <br/>
                            </div>
                            <div class="col-md-6 pt-3">
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
                        </div>
                    </div>


                    <?php 
                        if($orderData['purchaseorder_status'] == 'Delivered') {
                    ?>
                        <?php
                       $stockInQuery = "
                       SELECT 
                           i.name as ingredient_name,
                           sii.quantity as stockin_quantity,
                           uom.uom_name as unit_name,
                           sii.expiryDate as stockin_expirydate,
                           sii.totalPrice as stockin_totalPrice
                       FROM stockin si
                       JOIN stockin_ingredients sii ON si.id = sii.stockin_id
                       JOIN ingredients i ON sii.ingredient_id = i.id
                       JOIN units_of_measure uom ON uom.id = sii.unit_id
                       WHERE si.purchaseorder_id = '$orderId'
                        ";                   
    

                        $stockInItemsRes = mysqli_query($conn, $stockInQuery);
                        $i = 1;
                        if($stockInItemsRes){
                            if(mysqli_num_rows($stockInItemsRes) > 0){
                                $totalAmount = 0;
                                ?>
                                    <h4 class="my-3">Stock In Details</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item No. </th>
                                                <th>Ingredient</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Expiry Date</th>
                                                <th>Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($orderItemRow = mysqli_fetch_assoc($stockInItemsRes)): ?>
                                                <tr>
                                                    <td width="5%" class=" text-center">
                                                        <?= $i++; ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['ingredient_name'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        <?= number_format($orderItemRow['stockin_quantity'], 2); ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['unit_name'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        <?= $orderItemRow['stockin_expirydate']; ?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['stockin_totalPrice'], 2); ?>
                                                    </td> 
                                                </tr>
                                                <?php $totalAmount += $orderItemRow['stockin_totalPrice']; ?>
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
                        }
                    ?>

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
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($orderItemRow = mysqli_fetch_assoc($orderItemsRes)): ?>
                                                <tr>
                                                    <td width="5%" class=" text-center">
                                                        <?= $i++; ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['ingredientName'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        <?= $orderItemRow['orderItemQuantity']; ?>
                                                    </td>
                                                    <td width="15%" class=" text-center">
                                                        <?= $orderItemRow['unit_name'];?>
                                                    </td>
                                                    <td width="15%" class="text-center">
                                                        Php <?= number_format($orderItemRow['orderItemPrice'], 2); ?>/<?= $orderItemRow['unit_name'];?>
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
                    <a href="purchase-orders.php" class="btn btn-primary mt-4 w-25">Return to Purchase Orders View</a>
                    </div>
                    
                </div>
            <?php
        }
        ?>

       

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>