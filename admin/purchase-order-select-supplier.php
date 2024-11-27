<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

unset($_SESSION['ingredientItems']);
unset($_SESSION['ingredientItemIds']);
?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Purchase Order</h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <form action="purchase-orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Select Supplier</label>
                        <select name="supplier_id" id="supplierName" class="form-select mySelect2">
                            <option value="">-- Select Supplier --</option>
                            <?php
                                $suppliers = getAll('suppliers');
                                if($suppliers) {
                                    if(mysqli_num_rows($suppliers) > 0) {
                                        foreach($suppliers as $supplierItems) {
                                            ?>
                                                <option value="<?= $supplierItems['id']; ?>"><?= $supplierItems['firstname']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No Suppliers found!</option>';
                                    }
                                } else {
                                    echo '<option value="">Something went wrong!</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="button" id="selectSupplierBtn" class="btn btn-outline-primary selectSupplier">Select Supplier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
    document.getElementById('selectSupplierBtn').addEventListener('click', function() {
        var supplierId = document.getElementById('supplierName').value;
        if (supplierId) {
            window.location.href = 'purchase-order-create.php?track=' + supplierId;
        } else {
            alert('Please select a supplier!');
        }
    });
</script>

<?php include('includes/footer.php'); ?>