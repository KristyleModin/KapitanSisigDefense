<?php include('includes/header.php'); ?>


<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Add Admin
            <a href="admins.php" class="btn btn-outline-danger float-end">Back</a>

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
                    <label for="">Username * </label>
                    <input type="username" name="username" required class="form-control">
                </div>
                <div class="col-md-3 mb-3" >
                    <label for="">Password * </label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                <label for="">Position</label>
                <div>
                    <input type="radio" name="position" value="1">
                    <label>Owner</label>
                </div>
                <div>
                    <input type="radio" name="position" value="0">
                    <label>Employee</label>
                </div>
                 </div>
                <div class="col-md-12 mb-3 text-end" >
                    <button type="submit" name="saveAdmin" class="btn btn-outline-primary">Save</button>
                </div>
                
            </div>

           </form>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>