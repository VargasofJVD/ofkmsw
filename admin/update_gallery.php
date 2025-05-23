<?php
require_once '../config/database.php';

try {
    $db = getDbConnection();
    
    // First, create gallery_categories table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS gallery_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Insert default categories if they don't exist
    $db->exec("INSERT IGNORE INTO gallery_categories (name, description) VALUES
        ('Classrooms', 'Photos of our Montessori classrooms and learning environments'),
        ('Events', 'School events, celebrations, and special occasions'),
        ('Activities', 'Student activities and learning experiences'),
        ('Facilities', 'School facilities and infrastructure')
    ");
    
    // Ensure users table exists (it should already exist from the main database setup)
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    
    // Now drop and recreate the gallery table
    $db->exec("DROP TABLE IF EXISTS gallery");
    
    $db->exec("CREATE TABLE gallery (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        file_path VARCHAR(255) NOT NULL,
        file_type ENUM('image', 'video') NOT NULL,
        category_id INT,
        is_featured BOOLEAN DEFAULT FALSE,
        uploaded_by INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES gallery_categories(id) ON DELETE SET NULL,
        FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
    )");
    
    // Create uploads directory if it doesn't exist
    $upload_dir = '../uploads/gallery';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    echo "Gallery table structure updated successfully!";
    
} catch (PDOException $e) {
    die("Error updating gallery table: " . $e->getMessage());
}
?> 