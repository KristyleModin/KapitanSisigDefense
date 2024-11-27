
<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Edit Customer
                <a href="customers.php" class = "btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage();?>
            <form action="code.php" method="POST">
                
            <?php
            $paramValue = checkParam('id');
            if(!is_numeric($paramValue)){
                echo '<h5>'.$paramValue.'</h5>';
                return false;
            }
            $customers = getById('customers',$paramValue);
            if($customers['status'] == 200){
            ?>
            <input type="hidden" name="customerId" value="<?= $customers['data']['id']; ?>">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="">Name *</label>
                        <input type="text" name="name" value="<?= $customers['data']['name']; ?>" required class="form-control" />
                    </div>  
                    <div class="col-md-6 mb-3 text-end">
                        <br />
                        <button type="submit" name="updateCustomer" class="btn btn-outline-primary">Update</button>
                    </div>
                </div>
                <?php
                }
                else {
                    echo '<h5>'.$customers['message'].'</h5>';
                }
                ?>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>