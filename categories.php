<?php
session_start();
include 'config/database.php';

// Fetch all categories
$categories_sql = "SELECT id, name, description FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - QuickShop</title>
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
                        <a class="nav-link active" href="categories.php">Categories</a>
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

    <!-- Categories Section -->
    <div class="container mt-5">
        <h2 class="mb-4">Product Categories</h2>
        <div class="row">
            <?php if ($categories_result->num_rows > 0): ?>
                <?php while($category = $categories_result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($category['description']); ?></p>
                                <a href="products.php?category=<?php echo htmlspecialchars($category['id']); ?>" 
                                   class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No categories found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
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
                        <li><i class="fas fa-phone"></i>03287299206</li>
                        <li><i class="fas fa-envelope"></i>hanzlagmail.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 