<?php include('includes/header.php'); ?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .card-footer {
        font-size: 0.9rem;
    }

    h4, h2 {
        margin: 0;
    }

    .text-primary, .text-warning, .text-success {
        font-weight: bold;
    }

    .chart-container {
        position: relative;
        height: 315px;
    }

    /* Custom styling for scrollable table */
    .table-container {
        max-height: 350px; /* Adjust based on your preference */
        overflow-y: auto;
    }
</style>

<div class="container-fluid px-4 pb-4">
    <div class="pt-4">
        <?php alertMessage(); ?>
    </div>
    <h1 class="mt-4">Dashboard</h1>
    <!-- <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol> -->

    <div class="row">
        <!-- Total Sales for the Month Section -->
        <div class="col-xl-6 col-md-12 mb-4" id="sales-section">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <?php
                    // Total sales query
                    $currentMonth = date('Y-m');
                    $startDate = $currentMonth . '-01';
                    $endDate = date('Y-m-t', strtotime($startDate));

                    $salesQuery = "SELECT DATE(order_date) AS order_day, SUM(total_amount) AS daily_sales 
                                   FROM orders 
                                   WHERE order_status = 'Completed' 
                                   AND order_date BETWEEN '$startDate' AND '$endDate'
                                   GROUP BY DATE(order_date)";
                    $salesResult = mysqli_query($conn, $salesQuery);

                    $salesData = [];
                    $days = [];
                    while ($row = mysqli_fetch_assoc($salesResult)) {
                        $salesData[] = $row['daily_sales'];
                        $days[] = $row['order_day'];
                    }

                    // Calculate total sales for the month
                    $totalSalesQuery = "SELECT COALESCE(SUM(total_amount), 0) AS total_sales 
                                        FROM orders 
                                        WHERE order_status = 'Completed' 
                                        AND order_date BETWEEN '$startDate' AND '$endDate'";
                    $totalSalesResult = mysqli_query($conn, $totalSalesQuery);
                    $totalSales = $totalSalesResult ? mysqli_fetch_assoc($totalSalesResult)['total_sales'] : 0;
                    ?>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="text-warning">Total Sales This Month</h4>
                            <h2 class="mb-0">Php <?= number_format($totalSales, 2); ?></h2>
                        </div>
                        <i class="fas fa-chart-line fa-2x text-warning"></i>
                    </div>
                    <div class="chart-container mt-4">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 d-flex align-items-center justify-content-between">
                    <a class="text-warning text-decoration-none" href="sales.php">View Details</a>
                    <i class="fas fa-arrow-circle-right text-warning"></i>
                </div>
            </div>
        </div>

        <!-- Ingredients Overview Section -->
        <div class="col-xl-6 col-md-12 mb-4" id="ingredients-section">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <?php
                    // Ingredients query to show all ingredients, regardless of stock
                    $query = "SELECT ingredients.*, units_of_measure.uom_name AS unit_name 
                              FROM ingredients 
                              LEFT JOIN units_of_measure ON ingredients.unit_id = units_of_measure.id";
                    $result = mysqli_query($conn, $query);
                    ?>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="text-success">Ingredients Overview</h4>
                        </div>
                        <i class="fas fa-leaf fa-2x text-success"></i>
                    </div>
                    <div class="mt-4 table-container">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td>
                                            <?= htmlspecialchars($row['quantity']); ?> 
                                            <?= htmlspecialchars($row['unit_name'] ?: 'N/A'); ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['quantity'] == 0) {
                                                echo '<span class="badge bg-danger">No Stock</span>';
                                            } elseif ($row['quantity'] <= $row['reorder_point']) {
                                                echo '<span class="badge bg-warning">Low Stock</span>';
                                            } else {
                                                echo '<span class="badge bg-success">In Stock</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 d-flex align-items-center justify-content-between">
                    <a class="text-success text-decoration-none" href="ingredients-view.php">View Details</a>
                    <i class="fas fa-arrow-circle-right text-success"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Prepare data for the chart
    const days = <?= json_encode($days); ?>;
    const salesData = <?= json_encode($salesData); ?>;

    // Initialize Chart.js
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: days,
            datasets: [{
                label: 'Daily Sales',
                data: salesData,
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 2,
                tension: 0.4,
                pointBackgroundColor: 'rgba(255, 193, 7, 1)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Sales Amount (Php)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // Adjust the height of both sections to match
    window.onload = function() {
    const salesSection = document.getElementById('sales-section');
    const ingredientsSection = document.getElementById('ingredients-section');
    
    // Get the height of both sections
    const salesHeight = salesSection.offsetHeight;
    const ingredientsHeight = ingredientsSection.offsetHeight;
    
    // Set the height of both sections to match the taller one
    const maxHeight = Math.max(salesHeight, ingredientsHeight);
    salesSection.style.height = `${maxHeight}px`;
    ingredientsSection.style.height = `${maxHeight}px`;
};

</script>

<?php include('includes/footer.php'); ?>