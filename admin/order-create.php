<?php include('includes/header.php'); ?>

<div class="modal fade" id="addCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Enter Name</label>
            <input type="text" class="form-control" id="c_name"/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary saveCustomer">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid px-4 pb-4">
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h4 class="mb-0">Create Order
                <a href="orders.php" class="btn btn-outline-danger float-end">Back</a> 
            </h4>
        </div>
        <div class="card-body">
        <?php alertMessage(); ?>
            <form action="orders-code.php" method="POST">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="">Select Products</label>
                        <select name="product_id" class="form-select mySelect2">
                            <option value="">-- Select Product --</option>
                            <?php
                                // Show products that have >= 1 quantity
                                $productsQuery = "SELECT * FROM products WHERE quantity >= 1";
                                $productsResult = mysqli_query($conn, $productsQuery);
                                
                                if($productsResult) {
                                    if(mysqli_num_rows($productsResult) > 0) {
                                        foreach($productsResult as $prodItem) {
                                            ?>
                                            <option value="<?= $prodItem['id']; ?>"><?= $prodItem['productname']; ?></option>
                                            <?php
                                        }
                                    } else {
                                        echo '<option value="">No product found!</option>';
                                    }
                                } else {
                                    echo '<option value="">Something went wrong!</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <br/>
                        <button type="submit" name="addItem" class="btn btn-outline-primary">Add Item</button>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <input type="hidden" id="order_status" name="order_status" value="Placed">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card mt-3 mb-4">
        <div class="card-header">
             <h4 class="mb-0">Products</h4>
        </div>
        <div class="card-body">
        <div class="mb-3" id="productArea">
             <?php
              if (isset($_SESSION['productItems']) && !empty($_SESSION['productItems'])) {
                $sessionProducts = $_SESSION['productItems'];
                ?>
                <div class="mb-3" id="productContent">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th> 
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($sessionProducts as $key => $item) : 
                            ?>
                                <tr>
                                <td><?= $item['name']; ?></td>
                                <td>Php <?= $item['price']; ?></td>
                                <td>
                                     <div class="input-group qtyBox">
                                        <input type="hidden" value="<?= $item['product_id'];?>" class="prodId" >
                                        <button class="input-group-text prod-decrement">-</button>
                                        <input type="text" value="<?= $item['quantity']; ?>" class="qty quantityInput" />
                                        <button class="input-group-text prod-increment">+</button>
                                     </div>
                                </td>
                                <td>Php <?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                <a href="order-item-delete.php?index=<?= $key; ?>" class="btn btn-danger">Remove</a>
                                </td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <div class="mt-2">
            <hr>
            <div class="row">
                <div class="col-md-4">
                    <label>Select Payment Method</label>
                    <select id="payment_mode" class="form-select" name="payment_mode">
                        <option value="">-- Select Payment --</option>
                        <option value="Cash Payment">Cash Payment</option>
                        <option value="Online Payment">Online Payment</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Enter Customer Name</label>
                    <input type="text" id="cname" class="form-control" name="cname" value="" />
                    </div>
                <div class="col-md-4">
                <br/>
                    <button type="button" id="calculateButton" class="btn btn-outline-secondary w-100">Calculate</button>
                </div>
                

                
            </div>
        </div>

        <div id="paymentDetails" style="display: none; margin-top: 20px;">
                    <div class="row">
                        <!-- Total Amount (Read-only) -->
                        <div class="col-md-6">
                            <strong>Total Amount:</strong> 
                            <input type="text" class="form-control" id="totalAmount" value="0.00" readonly />
                        </div>
                        <!-- Amount Received (Input field) -->
                        <div class="col-md-6">
                            <strong>Amount Received:</strong> 
                            <input type="number" id="amount_received" class="form-control" min="0" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <!-- Change (Read-only) -->
                        <div class="col-md-6">
                            <strong>Change:</strong> 
                            <input type="text" class="form-control" id="change_money" value="0.00" readonly />
                        </div>
                        
                        <div class="col-md-6">
                        <br/>
                            <button type="submit" class="btn btn-warning w-100 proceedToPlace" form="orderForm">Proceed to place order</button>
                        </div>
                    </div>
                </div>
        <?php
              } else {
                 echo '<h5>No items added</h5>';
              }
             ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ensure the DOM is loaded before attaching event listeners

    // Calculate the total amount when the "Calculate" button is clicked
    const calculateButton = document.getElementById('calculateButton');
    if (calculateButton) {
        calculateButton.addEventListener('click', function () {
            let totalAmount = 0;

            // Loop through all rows in the product table
            const productRows = document.querySelectorAll('#productContent tbody tr');
            productRows.forEach(row => {
                const priceElement = row.querySelector('td:nth-child(2)');
                const quantityInput = row.querySelector('.quantityInput');

                if (priceElement && quantityInput) {
                    const price = parseFloat(priceElement.textContent.replace('Php ', '').trim());
                    const quantity = parseInt(quantityInput.value);

                    if (!isNaN(price) && !isNaN(quantity)) {
                        totalAmount += price * quantity;
                    }
                }
            });

            // Update the total amount field
            document.getElementById('totalAmount').value = totalAmount.toFixed(2);

            // Show the payment details section
            document.getElementById('paymentDetails').style.display = 'block';
        });
    }

    // Update the change when the amount received is entered
    const amountReceived = document.getElementById('amount_received');
    if (amountReceived) {
        amountReceived.addEventListener('input', function () {
            const amount_received = parseFloat(this.value);
            const totalAmount = parseFloat(document.getElementById('totalAmount').value);
            const change_money = amount_received - totalAmount;

            // Only show change if it's a valid number and >= 0
            if (isNaN(change_money) || change_money < 0) {
                document.getElementById('change_money').value = '0.00';
            } else {
                document.getElementById('change_money').value = change_money.toFixed(2);
            }
        });
    }
});

</script>