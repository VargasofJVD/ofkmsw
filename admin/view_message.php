<?php
/**
 * View Message Details
 * 
 * This script handles viewing individual message details and marks them as read.
 */

// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check if message ID is provided
if (!isset($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Message ID is required']);
    exit;
}

try {
    $db = getDbConnection();
    
    // Get message details
    $stmt = $db->prepare("SELECT * FROM messages WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $message = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$message) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Message not found']);
        exit;
    }
    
    // Mark message as read if it's unread
    if ($message['status'] === 'unread') {
        $update_stmt = $db->prepare("UPDATE messages SET status = 'read' WHERE id = ?");
        $update_stmt->execute([$_GET['id']]);
    }
    
    // Return message details
    header('Content-Type: application/json');
    echo json_encode($message);
    
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    error_log('View message database error: ' . $e->getMessage());
} 