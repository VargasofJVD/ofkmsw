<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'krisah_db');

try {
    // Connect to MySQL without selecting a database
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "Database checked/created successfully.<br>";
    
    // Select the database
    $pdo->exec("USE " . DB_NAME);
    
    // Check if users table exists
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() == 0) {
        // Create users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");
        
        // Insert default admin user (password: admin123)
        $pdo->exec("INSERT INTO users (username, password, email, full_name, role) VALUES
            ('admin', '$2y$10$8tGIx5.D0t5zZV7mBRQRkuFKVkwjbJcf.Zl0sPYYCXSqcgQvCS3Hy', 'admin@krisahmontessori.com', 'Admin User', 'admin')");
        echo "Users table created with default admin user.<br>";
    }
    
    // Check if news_events table exists
    $result = $pdo->query("SHOW TABLES LIKE 'news_events'");
    if ($result->rowCount() == 0) {
        // Create news_events table
        $pdo->exec("CREATE TABLE IF NOT EXISTS news_events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255),
            event_date DATE,
            is_event BOOLEAN DEFAULT FALSE,
            is_featured BOOLEAN DEFAULT FALSE,
            created_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
        )");
        echo "News events table created successfully.<br>";
    }

    // Check if settings table exists
    $result = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($result->rowCount() == 0) {
        // Create settings table
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) NOT NULL UNIQUE,
            setting_value TEXT NOT NULL,
            updated_by INT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        )");

        // Insert default settings
        $pdo->exec("INSERT INTO settings (setting_key, setting_value) VALUES
            ('school_name', 'Krisah Montessori School'),
            ('school_address', 'Western Region, Ghana'),
            ('school_phone', '+233 24 567 8901'),
            ('school_email', 'info@krisahmontessori.com'),
            ('facebook_url', 'https://facebook.com/krisahmontessori'),
            ('instagram_url', 'https://instagram.com/krisahmontessori'),
            ('twitter_url', 'https://twitter.com/krisahmontessori'),
            ('about_us', 'Krisah Montessori School is dedicated to providing quality education based on Montessori principles.'),
            ('mission_statement', 'Our mission is to provide a nurturing environment that cultivates independent thinking, creativity, and a lifelong love of learning through the Montessori approach.'),
            ('vision_statement', 'To be the leading Montessori institution in Ghana, recognized for excellence in education and character development.')");
        echo "Settings table created with default values.<br>";
    }
    
    echo "All database checks completed successfully!";
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?> 