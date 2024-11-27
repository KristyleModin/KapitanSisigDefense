<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 mb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Print Order
                <a href="orders.php" class="btn btn-danger btn-sm float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
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

                        $orderQuery = "SELECT o.*, c.* FROM orders o, customers c
                            WHERE c.id=o.customer_id AND tracking_no='$trackingNo' LIMIT 1";
                        $orderQueryRes = mysqli_query($conn, $orderQuery);
                        
                        if(!$orderQueryRes){
                            echo "<h5>Something Went Wrong</h5>";
                            return false;
                        }

                        if(mysqli_num_rows($orderQueryRes) > 0)
                        {
                            $orderDataRow = mysqli_fetch_assoc($orderQueryRes);
                            
                            ?>
                            <table style="width: 100%; margin-bottom: 20px;">
                                <tbody> 
                                    <tr>
                                        <td style="text-align: center;" colspan="2">
                                            <h4 style="font-size: 23px; line-height: 30px; margin: 2px; padding: 0;">Kapitan Sisig</h4>
                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">
                                                    GDG Building Door 16, Purok 2, Barangay Lubogan Toril, Davao City, Philippines
                                            </p>
                                            <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">Food Business</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Customer Details</h5>
                                            <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= $orderDataRow['name']; ?></p>
                                            
                                        </td>
                                        <td align="end">
                                            <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No: <?= $orderDataRow['invoice_no']; ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: <?= $orderDataRow['order_date'] ?></p>
                                            <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: Davao City, Philippines</p>
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

                        $orderItemQuery = "SELECT oi.quantity as orderItemQuantity, oi.price as orderItemPrice, o.*, oi.*, p.*
                            FROM orders o, order_items oi, products p
                            WHERE oi.order_id=o.id AND p.id=oi.product_id AND o.tracking_no='$trackingNo' ";

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
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="5%">ID</th> 
                                                <th align="start" style="border-bottom: 1px solid #ccc;">Product Name</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                                <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $totalQuantity = 0;
                                            foreach($orderItemQueryRes as $key => $row) :
                                                $totalQuantity += $row['orderItemQuantity'];
                                            ?>
                                            <tr>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['productname']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;">Php <?= number_format($row['orderItemPrice'], 2); ?></td>
                                                <td style="border-bottom: 1px solid #ccc;"><?= $row['orderItemQuantity']; ?></td>
                                                <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                    Php <?= number_format($row['orderItemPrice'] * $row['orderItemQuantity'], 2); ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>

                                           <!-- Display Total Amount and Change -->
                                        <?php if (isset($_SESSION['totalAmount']) && isset($_SESSION['change_money'])): ?>
                                            <tr>
                                                <td colspan="4" align="end" style="font-weight: bold;">Total Amount:</td>
                                                <td colspan="1" style="font-weight: bold;">Php <?= number_format($_SESSION['totalAmount'], 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="end" style="font-weight: bold;">Amount Received:</td>
                                                <td colspan="1" style="font-weight: bold;">Php <?= number_format($_SESSION['amount_received'], 2); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="end" style="font-weight: bold;">Change:</td>
                                                <td colspan="1" style="font-weight: bold;">Php <?= number_format($_SESSION['change_money'], 2); ?></td>
                                            </tr>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5">
                                                    <h5 class="text-danger text-center">Order details are missing. Please go back and retry.</h5>
                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                            <tr>
                                                <td colspan="5">
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Total Quantity: <?= $totalQuantity ?></p>
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Payment Mode: <?= $row['payment_mode']; ?></p>
                                                    <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Order placed by: <?= $row['order_placed_by_id']; ?></p>
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