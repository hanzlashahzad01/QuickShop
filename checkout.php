<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Get user information
$user_sql = "SELECT * FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get cart items
$cart_sql = "SELECT c.*, p.name, p.price, p.stock 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$total = 0;
$items = [];

while ($item = $cart_result->fetch_assoc()) {
    $subtotal = $item['price'] * $item['quantity'];
    $total += $subtotal;
    $items[] = $item;
}

// Process order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate stock
    $stock_valid = true;
    foreach ($items as $item) {
        if ($item['quantity'] > $item['stock']) {
            $stock_valid = false;
            $error = "Some items are out of stock. Please update your cart.";
            break;
        }
    }
    
    if ($stock_valid) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Create order
            $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address) 
                         VALUES (?, ?, ?)";
            $order_stmt = $conn->prepare($order_sql);
            $shipping_address = $user['address']; // You might want to get this from a form
            $order_stmt->bind_param("ids", $user_id, $total, $shipping_address);
            $order_stmt->execute();
            $order_id = $conn->insert_id;
            
            // Create order items and update stock
            foreach ($items as $item) {
                // Add order item
                $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                            VALUES (?, ?, ?, ?)";
                $item_stmt = $conn->prepare($item_sql);
                $item_stmt->bind_param("iiid", $order_id, $item['product_id'], 
                                     $item['quantity'], $item['price']);
                $item_stmt->execute();
                
                // Update stock
                $new_stock = $item['stock'] - $item['quantity'];
                $update_stock_sql = "UPDATE products SET stock = ? WHERE id = ?";
                $update_stock_stmt = $conn->prepare($update_stock_sql);
                $update_stock_stmt->bind_param("ii", $new_stock, $item['product_id']);
                $update_stock_stmt->execute();
            }
            
            // Clear cart
            $clear_cart_sql = "DELETE FROM cart WHERE user_id = ?";
            $clear_cart_stmt = $conn->prepare($clear_cart_sql);
            $clear_cart_stmt->bind_param("i", $user_id);
            $clear_cart_stmt->execute();
            
            // Commit transaction
            $conn->commit();
            $success = "Order placed successfully!";

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $error = "Error processing order. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - QuickShop</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=USD"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">QuickShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="btn btn-light me-2">
                        <i class="fas fa-shopping-cart"></i> Cart
                    </a>
                    <a href="logout.php" class="btn btn-outline-light">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Checkout Section -->
    <div class="container mt-4">
        <h2>Checkout</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <div class="mt-3">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <!-- Order Summary -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): 
                                            $subtotal = $item['price'] * $item['quantity'];
                                        ?>
                                            <tr>
                                                <td><?php echo $item['name']; ?></td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                <td><?php echo $item['quantity']; ?></td>
                                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Shipping Information</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo $user['full_name']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="<?php echo $user['email']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" value="<?php echo $user['phone']; ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Shipping Address</label>
                                    <textarea class="form-control" rows="3" readonly><?php echo $user['address']; ?></textarea>
                                </div>
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary">Place Order (Cash on Delivery)</button>
                                </div>
                                <hr>
                                <div class="d-grid">
                                    <div id="paypal-button-container"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        paypal.Buttons({
            // Set up the transaction
            createOrder: function(data, actions) {
                // PHP variable for total amount
                let totalAmount = <?php echo json_encode(number_format($total, 2, '.', '')); ?>;
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: totalAmount
                        }
                    }]
                });
            },

            // Finalize the transaction
            onApprove: function(data, actions) {
                // This function is called when the buyer approves the payment
                return actions.order.capture().then(function(details) {
                    // Call your server-side script to process the payment and order
                    // Pass the order details (data.orderID, details) to your server
                    fetch('process_paypal_payment.php', {
                        method: 'post',
                        headers: {
                            'content-type': 'application/json'
                        },
                        body: JSON.stringify({
                            orderID: data.orderID,
                            payerID: data.payerID,
                            payment_details: details,
                            // Pass other order details from your PHP variables
                            user_id: <?php echo json_encode($user_id); ?>,
                            total_amount: <?php echo json_encode(number_format($total, 2, '.', '')); ?>
                        })
                    }).then(function(response) {
                        return response.json();
                    }).then(function(response_data) {
                        if (response_data.success) {
                            alert('Transaction completed by ' + details.payer.name.given_name + '!');
                            // Redirect to a success page or update UI
                            window.location.href = 'checkout.php?status=success&order_id=' + response_data.order_id;
                        } else {
                            alert('Payment successful, but there was an error processing your order on our side: ' + response_data.message);
                            // Optionally redirect to an error page or show a message
                            window.location.href = 'checkout.php?status=error&message=' + encodeURIComponent(response_data.message);
                        }
                    }).catch(function(error) {
                        console.error('Error sending payment details to server:', error);
                        alert('An error occurred while processing your payment. Please contact support.');
                    });
                });
            },

            // Handle errors
            onError: function(err) {
                console.error('An error occurred during the PayPal payment:', err);
                alert('An error occurred during the PayPal payment. Please try again or contact support.');
            }
        }).render('#paypal-button-container'); // Render the buttons

        // Handle success/error messages from redirect after PayPal payment
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        const message = urlParams.get('message');
        const orderId = urlParams.get('order_id');

        if (status === 'success') {
            document.querySelector('.container.mt-4').innerHTML = `
                <h2>Checkout</h2>
                <div class="alert alert-success">
                    Order placed successfully with PayPal! Your Order ID is: ${orderId}.
                    <div class="mt-3">
                        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            `;
        } else if (status === 'error') {
            document.querySelector('.container.mt-4').innerHTML = `
                <h2>Checkout</h2>
                <div class="alert alert-danger">
                    Payment was processed, but there was an error with your order: ${decodeURIComponent(message || 'Unknown error')}. Please contact support.
                </div>
            `;
        }
    </script>
</body>
</html> 