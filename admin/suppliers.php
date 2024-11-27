<?php include('includes/header.php');
// include('functions.php');  // Make sure functions.php is included ?>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Suppliers
            <a href="suppliers-ingredient-add.php" class="btn btn-outline-success float-end me-2">Manage Supplier</a>
                <a href="suppliers-create.php" class="btn btn-outline-primary float-end me-2">Add Supplier</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php 
                $suppliers = getAll('suppliers');
                if (mysqli_num_rows($suppliers) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table" style="width: 100%; margin-bottom: 1rem; color: #000; border: 1px solid #dee2e6;">
                    <thead>
                        <tr style="background-color: #f8f9fa; color: #000;">
                            <!-- <th>ID</th> -->
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $i = 0;
                    foreach ($suppliers as $suppliersItem) : 
                        $i++;
                    ?>
                    <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>; border: 1px solid #dee2e6;">
                        <!-- <td><?= $suppliersItem['id'] ?></td> -->
                        <td><?= htmlspecialchars($suppliersItem['firstname']) ?></td>
                        <td><?= htmlspecialchars($suppliersItem['lastname']) ?></td>
                        <td><?= htmlspecialchars($suppliersItem['phonenumber']) ?></td>
                        <td><?= htmlspecialchars($suppliersItem['address']) ?></td>
                        <td>
                            <a href="suppliers-edit.php?id=<?= $suppliersItem['id']; ?>" class="btn btn-outline-success btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Edit</a>
                            <!-- <a href="suppliers-delete.php?id=<?= $suppliersItem['id']; ?>" class="btn btn-outline-danger btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">Delete</a> -->
                            <a href="suppliers-ingredient-view.php?id=<?= $suppliersItem['id']; ?>" class="btn btn-outline-primary btn-sm" style="margin: 0; padding: 0.25rem 0.5rem;">View Supplier</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
            <?php 
                } else {
            ?>
            <h4 class="mb-0">No Record Found</h4>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

