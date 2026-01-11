<?php
include 'config/database.php';

// Array of product images and their corresponding product names
$product_images = [
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

// Update each product with its corresponding image
foreach ($product_images as $product_name => $image_name) {
    $sql = "UPDATE products SET image = ? WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $image_name, $product_name);
    
    if ($stmt->execute()) {
        echo "Updated image for product: $product_name with image: $image_name<br>";
    } else {
        echo "Error updating product: $product_name<br>";
    }
}

echo "<br>All product images have been updated!";
?> 