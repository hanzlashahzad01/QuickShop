<?php
session_start();
include 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);
    
    // Check if product exists and is in stock
    $check_sql = "SELECT stock FROM products WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }
    
    $product = $result->fetch_assoc();
    if ($product['stock'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Product out of stock']);
        exit;
    }
    
    // Check if product already in cart
    $check_cart_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $check_cart_stmt = $conn->prepare($check_cart_sql);
    $check_cart_stmt->bind_param("ii", $user_id, $product_id);
    $check_cart_stmt->execute();
    $cart_result = $check_cart_stmt->get_result();
    
    if ($cart_result->num_rows > 0) {
        // Update quantity
        $cart_item = $cart_result->fetch_assoc();
        $new_quantity = $cart_item['quantity'] + 1;
        
        $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cart updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating cart']);
        }
    } else {
        // Add new item to cart
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $product_id);
        
        if ($insert_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added to cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding to cart']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 