<?php
session_start();
include 'config/database.php';

// Get categories for filter
$categories_sql = "SELECT * FROM categories ORDER BY name ASC";
$categories_result = $conn->query($categories_sql);

// Get filter parameters
$category_id = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Trim whitespace
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name_asc';

// Build query parts using prepared statements for security and correctness
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1";
$params = [];
$types = "";

if ($category_id) {
    $sql .= " AND p.category_id = ?";
    $params[] = intval($category_id);
    $types .= "i";
}

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $search_param = '%' . $search . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

// Add sorting
switch ($sort) {
    case 'price_asc':
        $sql .= " ORDER BY p.price ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY p.price DESC";
        break;
    case 'name_desc':
        $sql .= " ORDER BY p.name DESC";
        break;
    default:
        $sql .= " ORDER BY p.name ASC";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

if (!empty($params)) {
    // Use call_user_func_array to bind parameters dynamically
    // The first argument is the statement, followed by the types string, then the parameters array
    $bind_names = [$types];
    for ($i = 0; $i < count($params); $i++) {
        $bind_name = 'p' . $i;
        $bind_names[] = &$params[$i]; // Pass by reference
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error executing statement: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - QuickShop</title>
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

    <!-- Products Section -->
    <div class="container mt-4">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php while ($category = $categories_result->fetch_assoc()): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="sort" class="form-select">
                            <option value="name_asc" <?php echo $sort == 'name_asc' ? 'selected' : ''; ?>>Name (A-Z)</option>
                            <option value="name_desc" <?php echo $sort == 'name_desc' ? 'selected' : ''; ?>>Name (Z-A)</option>
                            <option value="price_asc" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Price (Low to High)</option>
                            <option value="price_desc" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Price (High to Low)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="products.php" class="btn btn-secondary">Clear Filters</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card product-card h-100">
                            <?php if (!empty($product['image'])): ?>
                                <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <img src="images/products/no-image.jpg" 
                                     class="card-img-top" alt="No image available"
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <p class="card-text text-muted"><?php echo $product['category_name']; ?></p>
                                <p class="card-text price">$<?php echo number_format($product['price'], 2); ?></p>
                                <div class="d-grid gap-2">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" 
                                       class="btn btn-primary">View Details</a>
                                    <button class="btn btn-outline-primary add-to-cart" 
                                            data-product-id="<?php echo $product['id']; ?>">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No products found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add to cart functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
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
        });
    </script>
</body>
</html> 