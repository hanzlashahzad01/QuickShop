<?php
session_start();
include 'config/database.php';

$product = null;
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $sql = "SELECT p.*, c.name as category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE p.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

if (!$product) {
    // Product not found, redirect or show an error
    header("Location: products.php"); // Redirect to products page if ID is invalid or product not found
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - QuickShop</title>
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
                        <a class="nav-link active" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categories.php">Categories</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="cart.php" class="btn btn-light me-2">
                        <i class="fas fa-shopping-cart"></i> Cart
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="btn btn-outline-light">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Details Section -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($product['image'])): ?>
                    <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                         class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <?php else: ?>
                    <img src="images/products/no-image.jpg" 
                         class="img-fluid rounded" alt="No image available">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-muted">Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                <h2 class="price text-primary">$<?php echo number_format($product['price'], 2); ?></h2>
                <p class="mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                <p><strong>Stock:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-primary btn-lg add-to-cart" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                    <a href="products.php" class="btn btn-outline-secondary btn-lg">Back to Products</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer (include from a separate file if possible for consistency) -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>QuickShop</h5>
                    <p>Your trusted online shopping destination.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-light">About Us</a></li>
                        <li><a href="contact.php" class="text-light">Contact Us</a></li>
                        <li><a href="privacy_policy.php" class="text-light">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> +1 234 567 890</li>
                        <li><i class="fas fa-envelope"></i> info@quickshop.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add to cart functionality (similar to products.php)
        document.querySelector('.add-to-cart').addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product added to cart!');
                } else {
                    alert('Error adding product to cart');
                }
            });
        });
    </script>
</body>
</html> 