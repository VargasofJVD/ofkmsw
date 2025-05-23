<?php
require_once '../config/database.php';

try {
    $db = getDbConnection();
    
    // Drop the existing testimonials table
    $db->exec("DROP TABLE IF EXISTS testimonials");
    
    // Create the new testimonials table
    $db->exec("CREATE TABLE testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        role VARCHAR(50) NOT NULL,
        content TEXT NOT NULL,
        rating INT NOT NULL DEFAULT 5,
        image VARCHAR(255),
        created_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
    )");
    
    echo "Testimonials table has been updated successfully!";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 