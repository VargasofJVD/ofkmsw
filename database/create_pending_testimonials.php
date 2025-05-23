<?php
require_once '../config/database.php';

try {
    $db = getDbConnection();
    
    // Read the SQL file
    $sql = file_get_contents(__DIR__ . '/create_pending_testimonials.sql');
    
    // Execute the SQL
    $db->exec($sql);
    
    echo "Pending testimonials table created successfully!";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
} 