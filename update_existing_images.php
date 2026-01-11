<?php
include 'config/database.php';

// Explicit mapping of product names to their actual image filenames in images/products
$product_image_mapping = [
    'Smartphone X' => 'Smartphone X.avif',
    'Laptop Pro' => 'Laptop Pro.webp',
    'Designer T-Shirt' => 'Designer T-Shirt.jpg',
    'Coffee Maker' => 'Coffee Maker.webp',
    'Gaming Headset' => 'gaming-headset.jpg',
    'Action Camera' => 'Action Camera.jpeg',
    'Casual Sneakers' => 'Casual Sneakers.jpeg',
    'Children\'s Storybook' => 'Children\'s Storybook.jpeg',
    'Wireless Mouse' => 'wireless-mouse.jpg',
    'Mechanical Keyboard' => 'mechanical-keyboard.jpg',
    'External SSD 1TB' => 'external-ssd.jpg',
    'Portable Speaker' => 'portable-speaker.jpg',
    'Drone with Camera' => 'drone.jpg',
    'Smart Home Hub' => 'smart-hub.jpg',
    'Men\'s Hoodie' => 'mens-hoodie.jpg',
    'Women\'s Jeans' => 'womens-jeans.jpg',
    'Winter Jacket' => 'winter-jacket.jpg',
    'Sport Socks 5-Pack' => 'sport-socks.jpg',
    'Summer Dress' => 'summer-dress.jpg',
    'Espresso Machine' => 'espresso-machine.jpg',
    'Toaster Oven' => 'toaster-oven.jpg',
    'Cookware Set' => 'cookware-set.jpg',
    'Robot Vacuum' => 'robot-vacuum.jpg',
    'Water Filter Pitcher' => 'water-pitcher.jpg',
    'Desk Lamp' => 'desk-lamp.jpg',
    'Science Fiction Anthology' => 'scifi-anthology.jpg',
    'Fantasy Epic Novel' => 'fantasy-novel.jpg',
    'Self-Help Guide' => 'self-help.jpg',
    'History of Art' => 'art-history.jpg',
    'QuickShop Logo 2' => 'quickshope.png'
];

$success_count = 0;
$error_count = 0;

// Update products in the database with the correct image filename and set as featured
foreach ($product_image_mapping as $product_name => $image_filename) {
    $sql = "UPDATE products SET image = ?, featured = 1 WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $image_filename, $product_name);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Updated image and set as featured for product: <b>{$product_name}</b> with image: <b>{$image_filename}</b><br>";
            $success_count++;
        } else {
            echo "Product <b>{$product_name}</b> not found or no change needed.<br>";
        }
    } else {
        echo "Error updating product <b>{$product_name}</b>: " . $conn->error . "<br>";
        $error_count++;
    }
}

echo "<br>--- Image Update Summary ---";
echo "<br>Successfully updated {$success_count} product images.";
if ($error_count > 0) {
    echo "<br>Failed to update {$error_count} product images due to errors.";
}

$conn->close();
?> 