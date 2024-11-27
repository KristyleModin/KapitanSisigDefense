<?php include('includes/header.php'); ?>


<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Supplier
            <a href="suppliers.php" class="btn btn-outline-danger float-end">Back</a>

            </h4>
        </div>
        <div class="card-body">
           <?php alertMessage();?>
           <form action="code.php" method= "POST">
            <div class="row">
                <div class="col-md-12 mb-3" >
                    <label for="">First Name * </label>
                    <input type="text" name="firstname" required class="form-control">
                </div>
                <div class="col-md-6 mb-3" >
                    <label for="">Last Name * </label>
                    <input type="text" name="lastname" required class="form-control">
                </div>
                <div class="col-md-6 mb-3" >
                    <label for="">Phone Number * </label>
                    <input type="phonenumber" name="phonenumber" required class="form-control">
                </div>
                <div class="col-md-3 mb-3" >
                    <label for="">Address * </label>
                    <input type="address" name="address" required class="form-control">
                </div>
                <div class="col-md-12 mb-3 text-end" >
                    <button type="submit" name="saveSupplier" class="btn btn-outline-primary">Save</button>
                </div>
                
            </div>

           </form>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>