<?php
session_start();
include 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id']) && isset($_POST['quantity'])) {
    $user_id = $_SESSION['user_id'];
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    
    // Verify cart item belongs to user
    $check_sql = "SELECT c.*, p.stock 
                  FROM cart c 
                  JOIN products p ON c.product_id = p.id 
                  WHERE c.id = ? AND c.user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $cart_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit;
    }
    
    $cart_item = $result->fetch_assoc();
    
    // Check stock availability
    if ($quantity > $cart_item['stock']) {
        echo json_encode(['success' => false, 'message' => 'Not enough stock available']);
        exit;
    }
    
    // Update quantity
    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cart updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 