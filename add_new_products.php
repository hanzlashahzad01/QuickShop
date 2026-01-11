<?php
include 'config/database.php';

// Array of new products to add
$new_products =[
    // Electronics Category (ID: 1)
    ['category_id' => 1, 'name' => 'Gaming Headset', 'description' => 'Immersive gaming headset with surround sound.', 'price' => 79.99, 'stock' => 50, 'image' => 'gaming-headset.jpg', 'featured' => 1],
    ['category_id' => 1, 'name' => 'Wireless Mouse', 'description' => 'Ergonomic wireless mouse for comfort and precision.', 'price' => 25.00, 'stock' => 120, 'image' => 'wireless-mouse.jpg', 'featured' => 0],
    ['category_id' => 1, 'name' => 'Mechanical Keyboard', 'description' => 'Durable mechanical keyboard with RGB lighting.', 'price' => 89.99, 'stock' => 60, 'image' => 'mechanical-keyboard.jpg', 'featured' => 1],
    ['category_id' => 1, 'name' => 'External SSD 1TB', 'description' => 'Fast and portable 1TB external solid-state drive.', 'price' => 119.99, 'stock' => 40, 'image' => 'external-ssd.jpg', 'featured' => 0],
    ['category_id' => 1, 'name' => 'Portable Speaker', 'description' => 'Compact Bluetooth speaker with powerful bass.', 'price' => 45.00, 'stock' => 90, 'image' => 'portable-speaker.jpg', 'featured' => 1],
    ['category_id' => 1, 'name' => 'Action Camera', 'description' => 'Waterproof 4K action camera for adventures.', 'price' => 150.00, 'stock' => 30, 'image' => 'action-camera.jpg', 'featured' => 0],
    ['category_id' => 1, 'name' => 'Drone with Camera', 'description' => 'Easy-to-fly drone with HD camera and long flight time.', 'price' => 350.00, 'stock' => 20, 'image' => 'drone.jpg', 'featured' => 1],
    ['category_id' => 1, 'name' => 'Smart Home Hub', 'description' => 'Central hub for all your smart home devices.', 'price' => 100.00, 'stock' => 50, 'image' => 'smart-hub.jpg', 'featured' => 0],

    // Clothing Category (ID: 2)
    ['category_id' => 2, 'name' => 'Men\'s Hoodie', 'description' => 'Comfortable cotton hoodie for everyday wear.', 'price' => 35.50, 'stock' => 150, 'image' => 'mens-hoodie.jpg', 'featured' => 1],
    ['category_id' => 2, 'name' => 'Women\'s Jeans', 'description' => 'Stylish slim-fit jeans for women.', 'price' => 55.00, 'stock' => 100, 'image' => 'womens-jeans.jpg', 'featured' => 0],
    ['category_id' => 2, 'name' => 'Casual Sneakers', 'description' => 'Trendy and comfortable sneakers for casual outings.', 'price' => 65.00, 'stock' => 80, 'image' => 'casual-sneakers.jpg', 'featured' => 1],
    ['category_id' => 2, 'name' => 'Winter Jacket', 'description' => 'Warm and waterproof jacket for cold weather.', 'price' => 99.99, 'stock' => 70, 'image' => 'winter-jacket.jpg', 'featured' => 0],
    ['category_id' => 2, 'name' => 'Sport Socks 5-Pack', 'description' => 'Breathable sport socks for ultimate comfort.', 'price' => 12.00, 'stock' => 200, 'image' => 'sport-socks.jpg', 'featured' => 0],
    ['category_id' => 2, 'name' => 'Summer Dress', 'description' => 'Light and airy summer dress.', 'price' => 40.00, 'stock' => 110, 'image' => 'summer-dress.jpg', 'featured' => 1],

    // Home & Kitchen Category (ID: 3)
    ['category_id' => 3, 'name' => 'Espresso Machine', 'description' => 'Automatic espresso machine for your daily coffee.', 'price' => 250.00, 'stock' => 25, 'image' => 'espresso-machine.jpg', 'featured' => 1],
    ['category_id' => 3, 'name' => 'Toaster Oven', 'description' => 'Compact toaster oven for quick meals.', 'price' => 70.00, 'stock' => 60, 'image' => 'toaster-oven.jpg', 'featured' => 0],
    ['category_id' => 3, 'name' => 'Cookware Set', 'description' => 'Non-stick cookware set with multiple pots and pans.', 'price' => 180.00, 'stock' => 35, 'image' => 'cookware-set.jpg', 'featured' => 1],
    ['category_id' => 3, 'name' => 'Robot Vacuum', 'description' => 'Smart robot vacuum cleaner for automated cleaning.', 'price' => 299.99, 'stock' => 20, 'image' => 'robot-vacuum.jpg', 'featured' => 0],
    ['category_id' => 3, 'name' => 'Water Filter Pitcher', 'description' => 'Pitcher with advanced filter for clean drinking water.', 'price' => 30.00, 'stock' => 100, 'image' => 'water-pitcher.jpg', 'featured' => 0],
    ['category_id' => 3, 'name' => 'Desk Lamp', 'description' => 'Adjustable LED desk lamp with dimming features.', 'price' => 40.00, 'stock' => 70, 'image' => 'desk-lamp.jpg', 'featured' => 1],

    // Books Category (ID: 4)
    ['category_id' => 4, 'name' => 'Science Fiction Anthology', 'description' => 'Collection of classic sci-fi short stories.', 'price' => 15.00, 'stock' => 90, 'image' => 'scifi-anthology.jpg', 'featured' => 1],
    ['category_id' => 4, 'name' => 'Fantasy Epic Novel', 'description' => 'First book in a thrilling fantasy epic series.', 'price' => 22.00, 'stock' => 70, 'image' => 'fantasy-novel.jpg', 'featured' => 0],
    ['category_id' => 4, 'name' => 'Self-Help Guide', 'description' => 'Practical guide to improving productivity and well-being.', 'price' => 18.00, 'stock' => 100, 'image' => 'self-help.jpg', 'featured' => 0],
    ['category_id' => 4, 'name' => 'Childrens Storybook', 'description' => 'Illustrated storybook for young readers.', 'price' => 10.00, 'stock' => 150, 'image' => 'childrens-book.jpeg', 'featured' => 1],
    ['category_id' => 4, 'name' => 'History of Art', 'description' => 'Comprehensive overview of art history from ancient to modern.', 'price' => 30.00, 'stock' => 50, 'image' => 'art-history.jpg', 'featured' => 0]
    
];

// Insert new products
$success_count = 0;
$error_count = 0;

foreach ($new_products as $product) {
    // Check if product already exists to avoid duplicates
    $check_sql = "SELECT id FROM products WHERE name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $product['name']);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows == 0) {
        // Product does not exist, so insert it
        $sql = "INSERT INTO products (category_id, name, description, price, stock, image, featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdiis", 
            $product['category_id'],
            $product['name'],
            $product['description'],
            $product['price'],
            $product['stock'],
            $product['image'],
            $product['featured']
        );
        
        if ($stmt->execute()) {
            $success_count++;
        } else {
            echo "Error adding product {$product['name']}: " . $conn->error . "<br>";
            $error_count++;
        }
    } else {
        echo "Product '{$product['name']}' already exists. Skipping insertion.<br>";
    }
    $check_stmt->close();
}

echo "<br>Added $success_count new products successfully.";
if ($error_count > 0) {
    echo "<br>Failed to add $error_count products due to errors.";
}
?> 