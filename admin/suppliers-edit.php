<?php include('includes/header.php'); ?>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Supplier
                <a href="suppliers.php" class="btn btn-outline-danger float-end">Back</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>
            <form action="code.php" method="POST">

            <?php 
            if (isset($_GET['id']) && $_GET['id'] != '') {
                $supplierID = $_GET['id'];
            } else {
                echo '<h5>No ID Found</h5>';
                return false;
            }

            $supplierData = getByID('suppliers', $supplierID);

            if ($supplierData && $supplierData['status'] == 200) {
            ?>

                <input type="hidden" name="supplierId" value="<?= $supplierData['data']['id']; ?>">

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">First Name *</label>
                        <input type="text" name="firstname" required value="<?= $supplierData['data']['firstname']; ?>" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="">Last Name *</label>
                        <input type="text" name="lastname" required value="<?= $supplierData['data']['lastname']; ?>" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="">Phone Number *</label>
                        <input type="text" name="phonenumber" required value="<?= $supplierData['data']['phonenumber']; ?>" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="">Address *</label>
                        <input type="text" name="address" required value="<?= $supplierData['data']['address']; ?>" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3 text-end">
                        <button type="submit" name="updateSupplier" class="btn btn-outline-primary">Update</button>
                    </div>
                </div>
            <?php
            } else {
                echo '<h5>' . ($supplierData['message'] ?? 'Something Went Wrong!') . '</h5>';
                exit();
            }
            ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>