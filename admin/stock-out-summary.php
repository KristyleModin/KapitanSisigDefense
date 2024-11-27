<?php include('includes/header.php'); 
if(!isset($_SESSION['soItems'])){
    echo '<script> window.location.href="stock-out-create.php"</script>';
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
                <a href="purchase-orders.php" class="btn btn-secondary">Close</a>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <h4 class="mb-0"> Stock Out Summary
                        <a href="stock-out-create.php" class= "btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <?php alertMessage(); ?>

                    <div id="myBillingArea">
                    <table style="width: 100%; margin-bottom: 20px;">
                        <tbody>
                            <tr>
                                <td style="text-align: center;" colspan="2">
                                    <h4 style="font-size: 23px; line-height: 30px; margin: 2px; padding: 0;">Kapitan Sisig</h4>
                                    <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">
                                        Davao City, Philippines
                                    </p>
                                    <p style="font-size: 16px; line-height: 24px; margin: 2px; padding: 0;">Food Business</p>
                                </td>
                            </tr>
                            <tr class="mb-4">
                                <td>
                                    <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Customer Details</h5>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Customer Name: <?= $iRowData['firstname']; ?> <?= $iRowData['lastname']; ?></p>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">
                                        Position: <?= $iRowData['position'] == 1 ? 'Owner' : 'Employee' ?>
                                    </p>

                                </td>
                                <td align="end">
                                    <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Invoice Details</h5>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice No: <?= $invoiceNo; ?></p>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Invoice Date: <?= date('M d, Y h:i A'); ?></p>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: Davao City, Philippines</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h5 style="font-size: 20px; line-height: 30px; margin: 0px; padding: 0;">Suppplier Details</h5>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Supplier Name: <?= $supplierData['firstname']; ?></p>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Phone Number: <?= $supplierData['phonenumber']; ?></p>
                                    <p style="font-size: 14px; line-height: 20px; margin: 0px; padding: 0;">Address: <?= $supplierData['address']; ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>       
                        <?php
                        if(isset($_SESSION['soItems'])){
                            $sessionso = $_SESSION['soItems'];
                            ?>
                            <div class="table-responsive mb-3">
                                <table style="width:100%;" cellpadding="5">
                                    <thead>
                                        <tr>
                                            <th align="start" style="border-bottom: 1px solid #ccc;">Ingredient Name</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Price</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="10%">Quantity</th>
                                            <th align="start" style="border-bottom: 1px solid #ccc;" width="15%">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $totalAmount = 0;
                                        $totalQuantity = 0;
                                        $totalIngredients = 0;
                                        foreach ($sessionso as $key => $row) :
                                            $totalAmount += $row['price'] * $row['quantity'];
                                            $totalQuantity += $row['quantity'];
                                            $totalIngredients++;
                                        ?>
                                        <tr>
                                            <td style="border-bottom: 1px solid #ccc;"><?= $row['name']; ?></td>
                                            <td style="border-bottom: 1px solid #ccc;">Php <?= number_format($row['price'], 2); ?></td>
                                            <td style="border-bottom: 1px solid #ccc;"><?= $row['quantity']; ?></td>
                                            <td style="border-bottom: 1px solid #ccc;" class="fw-bold">
                                                Php <?= number_format($row['price'] * $row['quantity'], 2); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="4" align="end" style="font-weight: bold;">Grand Total: </td> 
                                            <td colspan="1" style="font-weight: bold;">Php <?= number_format($totalAmount, 2); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Payment Mode: <?= $_SESSION['ingPayment_mode']; ?></p>
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">No. of Ingredients: <?= $totalIngredients ?></p>
                                                <p style="font-size: 16px; line-height: 20px; margin: 0px; padding: 0;">Total Quantity: <?= $totalQuantity ?></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        } else {
                            echo '<h5 class="text-center">No ingredient added</h5>';
                        }
                        ?>

                    </div> <!-- End of myBillingArea -->

                    <?php if(isset($_SESSION['soItems'])) : ?>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-primary px-4 mx-1" id="saveStockOut">Save</button>
                    </div>
                    <?php endif; ?>

                </div> <!-- End of card-body -->
            </div> <!-- End of card -->
        </div> <!-- End of col-md-12 -->
    </div> <!-- End of row -->
</div> <!-- End of container-fluid -->

<?php include('includes/footer.php'); ?>