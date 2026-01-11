<?php
include 'config/database.php';

echo "<h2>Debugging Featured Products</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Product Name</th><th>Image Filename (from DB)</th><th>Featured</th></tr>";

$sql = "SELECT name, image, featured FROM products WHERE featured = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['image']) . "</td>";
        echo "<td>" . ($row['featured'] ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No featured products found in the database.</td></tr>";
}

echo "</table>";

// Also list all products to see if any have images but are not featured
echo "<h2>All Products (regardless of featured status)</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Product Name</th><th>Image Filename (from DB)</th><th>Featured</th></tr>";

$sql_all = "SELECT name, image, featured FROM products";
$result_all = $conn->query($sql_all);

if ($result_all->num_rows > 0) {
    while ($row_all = $result_all->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row_all['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row_all['image']) . "</td>";
        echo "<td>" . ($row_all['featured'] ? 'Yes' : 'No') . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No products found in the database.</td></tr>";
}

echo "</table>";

$conn->close();
?> 