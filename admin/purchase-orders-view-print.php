<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Print Purchase Order
                <a href="purchase-orders.php" class="btn btn-danger btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body" style="padding: 20px;">
            <div id="myBillingArea">
                <?php
                    if(isset($_GET['track']))
                    {
                        $trackingNo = validate($_GET['track']);
                        if($trackingNo == ''){
                            ?>
                            <div class="text-center py-5">
                                <h5>Please provide tracking number.</h5>
                                <div>
                                    <a href="orders.php" class="btn btn-primary mt-4 w-25">Go back to order </a>
                                </div>
                            </div>
                            <?php
                        }

                        $orderQuery = "SELECT po.*, a.* FROM purchaseOrders po, admins a WHERE 
                        a.id = po.customer_id AND tracking_no='$trackingNo' LIMIT 1";
                        $orderQueryRes = mysqli_query($conn, $orderQuery);
                        
                        if(!$orderQueryRes){
                            echo "<h5>Something Went Wrong</h5>";
                            return false;
                        }

                        if(mysqli_num_rows($orderQueryRes) > 0)
                        {
                            

                            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                            
                            $supplierName = $orderDataRow['supplierName'];
                            $supplierQuery = mysqli_query($conn, "SELECT * FROM suppliers WHERE id = '$supplierName' LIMIT 1");

                            $supplierData = mysqli_fetch_assoc($supplierQuery);
                            
                            ?>
                            <table style="width: 100%; margin-bottom: 20px;">
                                <tbody> 
                                    <tr>
                                        <td style="text-align: left;">
                                            <h4 style="font-size: 28px; line-height: 30px; margin-bottom: 5px; padding: 0;">Kapitan Sisig</h4>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">
                                                    GDG Building Door 16, Purok 2, Barangay Lubogan
                                            </p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">
                                                    Toril, Davao City, Philippines
                                            </p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Food Business</p>
                                        </td>
                                        <td align="end">
                                            <h5 style="font-size: 35px; line-height: 30px; letter-spacing: 3px; margin: 0px; margin-bottom: 5px; padding: 0;"><b>PURCHASE ORDER</b></h5>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;"><b>PURCHASE ORDER NO.: <?= $orderDataRow['tracking_no']; ?></b></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;"><b>DATE: <?= $orderDataRow['order_date']; ?></b></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 20px;">
                                            <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Customer Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= $orderDataRow['firstname']; ?> <?= $orderDataRow['lastname']; ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">
                                                Position: <?= $orderDataRow['position'] == 1 ? 'Owner' : 'Employee' ?>
                                            </p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: Davao City, Philippines</p>
                                            
                                        </td>
                                        <td align="end"  style="padding-top: 20px;">
                                        <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Suppplier Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Supplier Name: <?= $supplierData['firstname'] ?> <?= $supplierData['lastname'] ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Phone Number: <?= $supplierData['phonenumber'] ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: <?= $supplierData['address'] ?></p>                        
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                                            
                            <?php 
                        }
                        else
                        {
                            echo "<h5>No data found</h5>";
                            return false;
                        }

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

                        $orderItemQueryRes = mysqli_query($conn, $orderItemQuery);
                        if($orderItemQueryRes)
                        {
                            if(mysqli_num_rows($orderItemQueryRes) > 0)
                            {
                                ?>
                                <div class="table-responsive mb-3">
                                    <table style="width:100%;" cellpadding="5">
                                        <thead>
                                            <tr>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Item No.</th> 
                                                <th align="start" style="border-bottom: 1px solid #ccc;">Product Name</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Unit</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $totalQuantity = 0;
                                            $totalIngredients = 0;
                                            foreach($orderItemQueryRes as $key => $row) :
                                                $totalQuantity += $row['orderItemQuantity'];
                                                $totalIngredients++;
                                            ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['ingredientName']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;">Php <?= number_format($row['orderItemPrice'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['unit_name']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['orderItemQuantity']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    Php <?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 2); ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>

                                            <tr>
                                                <td colspan="5" align="end" style="font-weight: bold;">Grand Total: </td> 
                                                <td colspan="1" style="font-weight: bold;">Php <?= number_format($orderDataRow['total_amount'], 2); ?></td>
                                                
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Payment Mode: <?= $orderDataRow['ingPayment_mode']; ?></p>
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">No. of Ingredients: <?= $totalIngredients ?></p>
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Total Quantity: <?= $totalQuantity ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            }
                            else
                            {
                                echo "<h5>No data found</h5>";
                                return false;
                            }
                        }
                        else
                        {
                            echo "<h5>Something Went Wrong</h5>";
                            return false;
                        }


                    }
                    else
                    {
                        ?>
                        <div class="text-center py-5">
                            <h5>No tracking number Parameter found!</h5>
                            <div>
                                <a href="orders.php" class="btn btn-primary mt-4 w-25">Return to Orders View</a>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div class="mt-4 text-end">
                <button class="btn btn-info px-4 mx-1" onclick="printMyBillingArea()">Print</button>
                <button class="btn btn-primary px-4 mx-1" onclick="downloadPDF('<?= $orderDataRow['invoice_no']; ?>')">Download PDF</button>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>