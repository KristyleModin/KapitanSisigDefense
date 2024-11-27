<?php include('includes/header.php'); 
if(!isset($_SESSION['productItems'])){
    echo '<script> window.location.href="order-create.php"</script>';
}
?>

<div class="modal fade" id="orderSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-3 p-4">
                    <h5 id="orderPlaceSuccessMessage"></h5>
                </div>
            </div>
            <div class="modal-footer">
                <a href="orders.php" class="btn btn-secondary">Close</a>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid px-4 pb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Order Summary
                        <a href="order-create.php" class= "btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>

                    <div id="myBillingArea">

                        <?php 
                        if(isset($_SESSION['cname'])){
                            $name = validate($_SESSION['cname']);
                            $invoiceNo = validate($_SESSION['invoice_no']);

                            $customerQuery = mysqli_query($conn, "SELECT * FROM customers WHERE name = '$name' LIMIT 1");
                            if($customerQuery){
                                if(mysqli_num_rows($customerQuery) >0){

                                    $cRowData =mysqli_fetch_assoc($customerQuery);

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
                                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= $cRowData['name']; ?></p>
                                                </td>
                                                <td align="end">
                                                    <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No: <?= $invoiceNo; ?></p>
                                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: <?= date('M d, Y h:i A'); ?></p>
                                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: Davao City, Philippines</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php

                                
                                } else {
                                    echo "<h5>No customer found</h5>";
                                    return;
                                }
                            }
                        }
                        ?>

                        <?php
                        if(isset($_SESSION['productItems'])){
                            $sessionProducts = $_SESSION['productItems'];
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
                                        $totalAmount = 0;
                                        $totalQuantity = 0;
                                        foreach ($sessionProducts as $key => $row) :
                                            $totalAmount += $row['price'] * $row['quantity'];
                                            $totalQuantity += $row['quantity'];
                                        ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid #ccc;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                                            <td style="border-bottom: 1px solid #ccc;">Php <?= number_format($row['price'], 2); ?></td>
                                            <td style="border-bottom: 1px solid #ccc;"><?= $row['quantity']; ?></td>
                                            <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                Php <?= number_format($row['price'] * $row['quantity'], 2); ?>
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



                                            <td colspan="5">
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Total Quantity: <?= $totalQuantity ?></p>
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Payment Mode: <?= $_SESSION['payment_mode']; ?></p>
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Order placed by: <?= $_SESSION['loggedInUser']['firstname']; ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            echo '<h5 class="text-center">No items added</h5>';
                        }
                        ?>

                    </div> <!-- End of myBillingArea -->

                    <?php if(isset($_SESSION['productItems'])) : ?>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary px-4 mx-1" id="saveOrder">Save</button>
                    </div>
                    <?php endif; ?>

                </div> <!-- End of card-body -->
            </div> <!-- End of card -->
        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container-fluid -->

<?php include('includes/footer.php'); ?>
