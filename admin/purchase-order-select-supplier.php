<?php
include('includes/header.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Unset session items related to ingredients
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
                        <label for="supplierName">Select Supplier</label>
                        <select name="supplier_id" id="supplierName" class="form-select mySelect2">
                            <option value="">-- Select Supplier --</option>
                            <?php
                                // Fetch only active suppliers
                                $query = "SELECT id, firstname, lastname 
                                          FROM suppliers 
                                          WHERE status = 'active'";
                                $suppliers = mysqli_query($conn, $query);

                                if ($suppliers && mysqli_num_rows($suppliers) > 0) {
                                    while ($supplierItems = mysqli_fetch_assoc($suppliers)) {
                                        ?>
                                        <option value="<?= $supplierItems['id']; ?>">
                                            <?= $supplierItems['firstname'] . ' ' . $supplierItems['lastname']; ?>
                                        </option>
                                        <?php
                                    }
                                } else {
                                    echo '<option value="">No Active Suppliers found!</option>';
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
