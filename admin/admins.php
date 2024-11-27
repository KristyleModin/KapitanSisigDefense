<?php include('includes/header.php'); ?>

<style>
/* Inline CSS for table styling */
.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #000; /* Black Text */
}

.table thead th {
  background-color: #f8f9fa; /* Light Gray Background for Header */
  color: #000; /* Black Text */
}

.table tbody tr:nth-child(odd) {
  background-color: #f9f9f9; /* Very Light Gray Background for Odd Rows */
}

.table tbody tr:nth-child(even) {
  background-color: #fff; /* White Background for Even Rows */
}

.table-bordered {
  border: 1px solid #dee2e6; /* Border Color */
}

.table-bordered th,
.table-bordered td {
  border: 1px solid #dee2e6; /* Border Color */
}

.table-responsive {
  overflow-x: auto;
}

.table .btn {
  margin: 0;
  padding: 0.25rem 0.5rem;
}
</style>

<div class="container-fluid px-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Admins/Staff
                <a href="admins-create.php" class="btn btn-outline-primary float-end">Add Admin</a>
            </h4>
        </div>
        <div class="card-body">
            <?php alertMessage(); ?>

            <?php 
                $admins = getAll('admins');
                if(mysqli_num_rows($admins) > 0) {
            ?>
            <div class="table-responsive">
            <table class="table" style="color: #000; border: 1px solid #dee2e6;">
                <thead>
                    <tr style="background-color: #f8f9fa; color: #000;">
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $adminItem) : ?>
                    <tr style="background-color: <?= $i % 2 == 0 ? '#fff' : '#f9f9f9'; ?>;">
                        <td><?= $adminItem['id'] ?></td>
                        <td><?= $adminItem['firstname'] ?></td>
                        <td><?= $adminItem['lastname'] ?></td>
                        <td><?= $adminItem['username'] ?></td>
                        <td><?= $adminItem['position'] == 1 ? 'Owner' : 'Employee'; ?></td>
                        <td>
                            <a href="admins-edit.php?id=<?= $adminItem['id']; ?>" class="btn btn-outline-success btn-sm">Edit</a>
                            <a href="admins-delete.php?id=<?= $adminItem['id']; ?>" class="btn btn-outline-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            </div>
            <?php 
                } else {
            ?>
            <div class="alert alert-warning" role="alert">
                No Record Found
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>