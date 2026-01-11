<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickShop - Online Shopping</title>
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
                    <a href="login.php" class="btn btn-outline-light">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1>Welcome to QuickShop</h1>
                    <p>Your one-stop destination for all your shopping needs</p>
                    <a href="products.php"class="btn btn-primary btn-lg">Shop Now</a>
                </div>
                <div class="col-md-6">
                    <video src="vedios/vedio1.mp4" autoplay loop muted style="width: 100%; max-width: 1000px; height: auto; border-radius: 50px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);"></video>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <section class="featured-products py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row">
                <?php
                include 'config/database.php';
                $sql = "SELECT * FROM products WHERE featured = 1 LIMIT 4";
                $result = $conn->query($sql);
                
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3 mb-4">';
                    echo '<div class="card product-card h-100">';
                    if (!empty($row['image'])) {
                        echo '<img src="images/products/' . htmlspecialchars($row['image']) . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '" style="height: 200px; object-fit: cover;">';
                    } else {
                        echo '<img src="images/products/no-image.jpg" class="card-img-top" alt="No image available" style="height: 200px; object-fit: cover;">';
                    }
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                    echo '<p class="card-text">$' . number_format($row['price'], 2) . '</p>';
                    echo '<a href="product.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-primary">View Details</a>';
                    echo '</div></div></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
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
                        <li><i class="fas fa-phone"></i>03287299206</li>
                        <li><i class="fas fa-envelope"></i>hanzlagmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>

<?php
function getCategoryIcon($categoryName) {
    $icons = [
        'Electronics' => 'laptop',
        'Clothing' => 'tshirt',
        'Books' => 'book',
        'Home' => 'home',
        'Sports' => 'futbol',
        'Beauty' => 'spa',
        'Toys' => 'gamepad',
        'Food' => 'utensils'
    ];
    
    return $icons[$categoryName] ?? 'shopping-bag';
}
?> 