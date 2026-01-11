<?php
session_start();
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$sql = "SELECT c.*, p.name, p.price, p.image, p.stock 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - QuickShop</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <!-- Cart Section -->
    <div class="container mt-4">
        <h2>Shopping Cart</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $result->fetch_assoc()): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/products/<?php echo $item['image']; ?>" 
                                             alt="<?php echo $item['name']; ?>" 
                                             class="img-thumbnail me-3" style="width: 80px;">
                                        <div>
                                            <h5 class="mb-0"><?php echo $item['name']; ?></h5>
                                        </div>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <div class="input-group" style="width: 120px;">
                                        <button class="btn btn-outline-secondary update-quantity" 
                                                data-cart-id="<?php echo $item['id']; ?>" 
                                                data-action="decrease">-</button>
                                        <input type="number" class="form-control text-center quantity-input" 
                                               value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="<?php echo $item['stock']; ?>"
                                               data-cart-id="<?php echo $item['id']; ?>">
                                        <button class="btn btn-outline-secondary update-quantity" 
                                                data-cart-id="<?php echo $item['id']; ?>" 
                                                data-action="increase">+</button>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-item" 
                                            data-cart-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="products.php" class="btn btn-secondary">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="products.php">Continue shopping</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update quantity
        document.querySelectorAll('.update-quantity').forEach(button => {
            button.addEventListener('click', function() {
                const cartId = this.dataset.cartId;
                const action = this.dataset.action;
                const input = this.parentElement.querySelector('.quantity-input');
                let quantity = parseInt(input.value);
                
                if (action === 'increase') {
                    quantity = Math.min(quantity + 1, parseInt(input.max));
                } else {
                    quantity = Math.max(quantity - 1, 1);
                }
                
                updateCartItem(cartId, quantity);
            });
        });

        // Direct quantity input
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartId = this.dataset.cartId;
                let quantity = parseInt(this.value);
                quantity = Math.max(1, Math.min(quantity, parseInt(this.max)));
                this.value = quantity;
                updateCartItem(cartId, quantity);
            });
        });

        // Remove item
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item?')) {
                    const cartId = this.dataset.cartId;
                    removeCartItem(cartId);
                }
            });
        });

        function updateCartItem(cartId, quantity) {
            fetch('update_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating cart');
                }
            });
        }

        function removeCartItem(cartId) {
            fetch('remove_from_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error removing item from cart');
                }
            });
        }
    </script>
</body>
</html> 