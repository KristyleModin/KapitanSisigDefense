$(document).ready(function(){

    // Set alertify notification position
    alertify.set('notifier', 'position', 'top-right');

    $(document).on('click', '.prod-increment', function(){
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());
    
        if(!isNaN(currentValue)){
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });
    
    // Decrement Product Quantity
    $(document).on('click', '.prod-decrement', function(){
        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();
        var currentValue = parseInt($quantityInput.val());
    
        if(!isNaN(currentValue) && currentValue > 1){
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });
    
    
        // Quantity Increment/Decrement with AJAX
    function quantityIncDec(prodId, qty) {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productIncDec': true,
                'product_id': prodId,
                'quantity': qty
            },
            success: function(response) {
                var res = JSON.parse(response);
    
                if (res.status == 200) {
                    // Load updated product area content
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
    
                    // Re-enable increment button since quantity is valid
                    var $quantityInput = $('.qtyBox').find('.prodId[value="' + prodId + '"]').closest('.qtyBox').find('.prod-increment');
                    $quantityInput.prop('disabled', false);
                } else if (res.status == 500) {
                    alertify.error(res.message);
                    var $quantityInput = $('.qtyBox').find('.prodId[value="' + prodId + '"]').closest('.qtyBox').find('.prod-increment');
                    $quantityInput.prop('disabled', true); // Disable increment button
                }
            }
        });
    }
    
    


    $(document).on('click', '.proceedToPlace', function () {
        var cname = $('#cname').val().trim();
        var payment_mode = $('#payment_mode').val();
        var amount_received = parseFloat($('#amount_received').val());
        var order_status = $('#order_status').val();
        var totalAmount = parseFloat($('#totalAmount').val());
        var change_money = parseFloat($('#change_money').val());
    
        // Validate required fields
        if (!cname) {
            swal("Enter Customer Name", "Please enter a valid customer name.", "warning");
            return false;
        }
    
        if (!payment_mode) {
            swal("Select Payment Method", "Please select your payment method.", "warning");
            return false;
        }
    
        if (isNaN(amount_received) || amount_received <= 0) {
            swal("Enter Valid Amount Received", "Amount received must be greater than zero.", "warning");
            return false;
        }
    
        if (amount_received < totalAmount) {
            swal("Insufficient Amount", "Amount received cannot be less than the total amount.", "warning");
            return false;
        }
    
        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                proceedToPlaceBtn: true,
                cname: cname,
                payment_mode: payment_mode,
                order_status: order_status,
                totalAmount: totalAmount,
                change_money: change_money,
                amount_received: amount_received
            },
            success: function (response) {
                console.log('Response from server:', response);
                try {
                    var res = JSON.parse(response);
                    if (res.status === 200) {
                        window.location.href = "order-summary.php";
                    } else if (res.status === 404) {
                        swal(res.message, res.message, res.status_type, {
                            buttons: {
                                catch: { text: "Add Customer", value: "catch" },
                                cancel: "Cancel"
                            }
                        }).then((value) => {
                            console.log('User selected:', value);
                            if (value === "catch") {
                                $('#cname').val(cname);
                                $('#addCustomerModal').modal('show');
                            }
                        });
                    } else {
                        swal(res.message, res.message, res.status_type);
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    swal("Error", "Invalid response from the server.", "error");
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error);
                swal('Error', 'Failed to process the request. Please try again.', 'error');
            }
        });
    });
    
    
    
    


    $(document).on('click', '.proceedToUpdate', function() {
        var order_status = $('#order_status').val();
        var order_id = $('#order_id').val(); // Fetch the hidden order_id
    
        // Prepare data to send via AJAX
        var data = {
            'proceedToUpdateBtn': true,
            'order_id': order_id, // Include order_id
            'order_status': order_status
        };
    
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function(response) {
                console.log(response); // Log response for debugging
                try {
                    var res = JSON.parse(response);
                    if (res.status == 200) {
                        window.location.href = "orders.php"; // Redirect on success
                    } else {
                        swal(res.message, res.message, res.status_type); // Display error
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e, response);
                    swal('Error', 'Failed to process the response', 'error');
                }
            }
        });
    });

    $(document).on('click', '.proceedToComplete', function() {
        var order_track = $(this).closest('tr').find('input[name="order_track"]').val(); // Get the tracking number from the current row
        var order_status = 'Completed'; // Set the status directly to 'Completed'
    
        console.log('Order Track:', order_track); // Log for debugging
    
        // Prepare data to send via AJAX
        var data = {
            'proceedToCompleteBtn': true,
            'order_track': order_track,
            'order_status': order_status
        };
    
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function(response) {
                console.log(response); // Log response for debugging
                try {
                    var res = JSON.parse(response);
                    if (res.status == 200) {
                        window.location.href = "orders.php"; // Redirect on success
                    } else {
                        swal(res.message, res.message, res.status_type); // Display error
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e, response);
                    swal('Error', 'Failed to process the response', 'error');
                }
            }
        });
    });
    
    
    $(document).on('click', '.selectSupplier', function() {
        var supplierName = $('#supplierName').val(); // Fetch the hidden order_id
    
        // Prepare data to send via AJAX
        var data = {
            'selectSupplierBtn': true,
            'supplierName': supplierName
        };
    
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: data,
            success: function(response) {
                console.log(response); // Log response for debugging
                try {
                    var res = JSON.parse(response);
                    if (res.status == 200) {
                        window.location.href = "purchase-order-create.php"; // Redirect on success
                    } else {
                        swal(res.message, res.message, res.status_type); // Display error
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e, response);
                    swal('Error', 'Failed to process the response', 'error');
                }
            }
        });
    });
    
    
    

    // Save Customer
    $(document).on('click', '.saveCustomer', function() {
        var c_name = $('#c_name').val();

        if (c_name !== '') {
            var data = {
                'saveCustomerBtn': true,
                'name': c_name
            };

            $.ajax({
                type: "POST",
                url: "orders-code.php",
                data: data,
                success: function(response){
                    var res = JSON.parse(response);
                    swal(res.message, res.message, res.status_type);
                    if (res.status == 200) {
                        $('#addCustomerModal').modal('hide');
                    }
                }
            });
        } else {
            swal("Please fill required fields", "", "warning");
        }
    });

    // Save Order
    $(document).on('click', '#saveOrder', function() {
        console.log("Saving order...");  // Add this to verify the button click
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: { 'saveOrder': true },
            success: function(response) {
                console.log("AJAX success response: ", response);  // Debug the response
                try {
                    var res = JSON.parse(response);
                    if (res.status == 200) {
                        swal(res.message, res.message, res.status_type);
                        $('#orderPlaceSuccessMessage').text(res.message);
                        $('#orderSuccessModal').modal('show');
                    } else {
                        swal(res.message, res.message, res.status_type);
                    }
                } catch (error) {
                    console.error("Failed to parse JSON:", error, response);  // Add error handling
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX error: ", status, error);  // More detailed error logging
                swal("Error", "Failed to process order", "error");
            }
        });
    });

// Increment Ingredient Quantity
$(document).on('click', '.ing-increment', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue)){
        var qtyVal = currentValue + 1;
        $quantityInput.val(qtyVal);
        ingredientIncDec(ingredientId, qtyVal);
    }
});

// Decrement Ingredient Quantity
$(document).on('click', '.ing-decrement', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue) && currentValue > 1){
        var qtyVal = currentValue - 1;
        $quantityInput.val(qtyVal);
        ingredientIncDec(ingredientId, qtyVal);
    }
});


    // Ingredient Increment/Decrement AJAX
    function ingredientIncDec(ingId, qty) {
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: {
                'ingredientIncDec': true,
                'ingredient_id': ingId,
                'quantity': qty
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    $('#ingredientArea').load(' #ingredientContent');
                    alertify.success(res.message);
                } else if (res.status == 500) {
                    alertify.error(res.message);
                    $('.ing-increment').prop('disabled', true);
                }
            }
        });
    }

    // Increment Ingredient Quantity
$(document).on('click', '.si-increment', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue)){
        var qtyVal = currentValue + 1;
        $quantityInput.val(qtyVal);
        siIncDec(ingredientId, qtyVal);
    }
});

// Decrement Ingredient Quantity
$(document).on('click', '.si-decrement', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue) && currentValue > 0){
        var qtyVal = currentValue - 1;
        $quantityInput.val(qtyVal);
        siIncDec(ingredientId, qtyVal);
    }
});


    // Ingredient Increment/Decrement AJAX
    function siIncDec(ingId, qty) {
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: {
                'siIncDec': true,
                'ingredient_id': ingId,
                'quantity': qty
            },
            success: function(response) {
                console.log(response);
                try {
                    var res = JSON.parse(response);
                    if (res.status == 200) {
                        $('#siArea').load(' #siContent');
                        alertify.success(res.message);
                    } else if (res.status == 500) {
                        alertify.error(res.message);
                        $('.si-increment').prop('disabled', true);
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e, response);
                    swal('Error', 'Failed to process the response', 'error');
                }
            }
        });
    }
    
    $(document).on('click', '.proceedToPlaceIng', function() {
        var adminName = $('#adminName').val();
        var ingPayment_mode = $('#ingPayment_mode').val();
        var supplierName = $('#supplierName').val();
        var order_status = $('#order_status').val();
    
        // Log for debugging
        console.log('Order Status:', order_status);
    
        // Validate Form Fields
        if (!ingPayment_mode || !supplierName) {
            swal("Complete Form", "Please fill in all required fields", "warning");
            return false;
        }
    
        var data = {
            'proceedToPlaceIng': true,
            'adminName': adminName,
            'order_status': order_status,
            'ingPayment_mode': ingPayment_mode,
            'supplierName': supplierName
        };
    
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    window.location.href = "purchase-order-summary.php";
                } else {
                    swal(response.message, response.message, response.status_type);
                }
            },
            error: function() {
                swal('Error', 'Failed to process the request', 'error');
            }
        });
    });

    $(document).on('click', '.proceedToUpdateIng', function() {
        var order_status = $('#order_status').val();
        var order_id = $('#order_id').val(); // Fetch the hidden order_id
    
        // Log for debugging
        console.log('Order Status:', order_status);
    
        var data = {
            'proceedToUpdateIng': true,
            'order_id': order_id,
            'order_status': order_status
        };
    
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    window.location.href = "purchase-orders.php";
                } else {
                    swal(response.message, response.message, response.status_type);
                }
            },
            error: function() {
                swal('Error', 'Failed to process the request', 'error');
            }
        });
    });
    

    $(document).on('click', '.stockInBtn', function() {
        var order_track = $('#order_track').val()
        var order_status = 'Delivered';
    
        console.log('Order Track:', order_track); // Log for debugging
    
        // Prepare data to send via AJAX
        var data = {
            'stockInBtn': true,
            'order_track': order_track,
            'order_status': order_status
        };
    
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: data,
            success: function(response) {
                console.log("Raw response:", response);  // Log the raw response to the console
                try {
                    var res = JSON.parse(response);  // Try parsing JSON
                    if (res.status == 200) {
                        window.location.href = "purchase-orders.php";  // Redirect on success
                    } else {
                        swal(res.message, res.message, res.status_type);  // Display error
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e, response);
                    swal('Error', 'Failed to process the response', 'error');
                }
            }
        });        
    });

    // Save Purchase Order
    $(document).on('click', '#savePurchaseOrder', function() {
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: { 'savePurchaseOrder': true },
            success: function(response){
                var res = JSON.parse(response);
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function() {
                swal("Error", "Failed to process order", "error");
            }
        });
    });

// Increment Ingredient Quantity
$(document).on('click', '.so-increment', function() {
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue)) {
        var qtyVal = currentValue + 1;
        $quantityInput.val(qtyVal);
        soIncDec(ingId, qtyVal); // Call the ingredient increment function
    }
});

// Decrement Ingredient Quantity
$(document).on('click', '.so-decrement', function() {
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if (!isNaN(currentValue) && currentValue > 1) {
        var qtyVal = currentValue - 1;
        $quantityInput.val(qtyVal);
        soIncDec(ingId, qtyVal); // Call the ingredient decrement function
    }
});

// Quantity Increment/Decrement with AJAX for Ingredients
function soIncDec(ingId, qty) {
    $.ajax({
        type: "POST",
        url: "code.php", // Ensure this is the correct file for ingredients
        data: {
            'soIncDec': true,
            'ingredient_id': ingId,
            'quantity': qty
        },
        success: function(response) {
            var res = JSON.parse(response);
        
            // Get the specific row for the ingredient
            var $row = $('.qtyBox').find('.ingId[value="' + ingId + '"]').closest('tr');
            var pricePerUnit = parseFloat($row.find('td:eq(4)').text().replace('Php ', '')); // Extract the price per unit
            
            if (res.status == 200) {
                alertify.success(res.message);
        
                // Update the total price for the specific ingredient
                var totalPrice = pricePerUnit * qty;
        
                // Update the total price column
                $row.find('td:eq(6)').text('Php ' + totalPrice.toFixed(2)); // Column 6 contains the total price
        
                // Re-enable the increment button since quantity is valid
                var $incrementBtn = $('.qtyBox').find('.ingId[value="' + ingId + '"]').closest('.qtyBox').find('.so-increment');
                $incrementBtn.prop('disabled', false);
        
            } else if (res.status == 500) {
                alertify.error(res.message);
        
                // Calculate the total price based on the maximum allowed quantity
                var maxQuantity = qty; // This should be the maximum available quantity
                var totalPrice = pricePerUnit * maxQuantity;
        
                // Update the total price column
                $row.find('td:eq(6)').text('Php ' + totalPrice.toFixed(2)); // Column 6 contains the total price
        
                // Disable the increment button since the maximum quantity is reached
                var $incrementBtn = $('.qtyBox').find('.ingId[value="' + ingId + '"]').closest('.qtyBox').find('.so-increment');
                $incrementBtn.prop('disabled', true);
            }
        }
        
        
    });
}

// Proceed to Place Order
$(document).on('click', '.proceedToPlaceSo', function(){
    var reason = $('#reason').val();

        // Validate reason
        if (reason === '') {
            swal("Select Reason", "Select reason for stock out", "warning");
            return false;
        }

    // Place Order via AJAX
    var data = {
        'proceedToPlaceSoBtn': true,
        'reason': reason
    };

    $.ajax({
        type: "POST",
        url: "code.php",
        data: data,
        success: function(response) {
            if (response.status == 200) {
                window.location.href = "stock-out-summary.php";
            } else {
                swal(response.message, response.message, response.status_type);
            }
        },
        error: function() {
            swal('Error', 'Failed to process the request', 'error');
        }
    });
});

    



});

// Print Billing Area
function printMyBillingArea() {
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>Kapitan Sisig</title>');
    a.document.write('<body style="font-family: fangsong;">' + divContents + '</body></html>');
    a.document.close();
    a.print();
}

// Download PDF
window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo) {
    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html(elementHTML, {
        callback: function() {
            docPDF.save(invoiceNo + '.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });
}