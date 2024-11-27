<?php include('includes/header.php'); ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Customer
                <a href="customer-create.php" class = "btn btn-outline-primary float-end">Add Customer</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
        <?php 
            $customers = getAll('customers'); 
            if(!$customers) {
                 echo '<h4>Something went wrong.</h4>';
                 return false;
            }
            if(mysqli_num_rows($customers)>0) {
        ?>
            <div class="table-responsive">
                <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #000;">
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php 
                        $i = 0;
                        foreach($customers as $item) : 
                        $i++;
                        ?>
                        <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                            <td><?= $item['name'] ?></td>
                            <td>
                                <a href="customer-edit.php?id=<?= $item['id'];?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="customer-delete.php?id=<?= $item['id'];?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php 
                } else {
                    ?>
                        <h4 class="mb-0">No record found.</h4>
                    <?php
                } 
                ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>