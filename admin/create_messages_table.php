<?php
/**
 * Create Messages Table
 * 
 * This script creates the messages table in the database if it doesn't exist.
 */

// Include database connection
require_once '../config/database.php';

try {
    $db = getDbConnection();
    
    // Create messages table
    $sql = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        subject VARCHAR(200) NOT NULL,
        message TEXT NOT NULL,
        status ENUM('read', 'unread') DEFAULT 'unread',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($sql);
    
    echo "Messages table created successfully!";
    
} catch (PDOException $e) {
    echo "Error creating messages table: " . $e->getMessage();
    error_log('Create messages table error: ' . $e->getMessage());
} 