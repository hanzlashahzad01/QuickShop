<?php
session_start();
include 'config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_id'])) {
    $user_id = $_SESSION['user_id'];
    $cart_id = intval($_POST['cart_id']);
    
    // Verify cart item belongs to user
    $check_sql = "SELECT * FROM cart WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $cart_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo json_encode(['success' => false, 'message' => 'Cart item not found']);
        exit;
    }
    
    // Remove item from cart
    $delete_sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $cart_id, $user_id);
    
    if ($delete_stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error removing item from cart']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?> 