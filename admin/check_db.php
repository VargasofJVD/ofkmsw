<?php
require_once '../config/database.php';

try {
    $db = getDbConnection();
    echo "Database connection successful<br>";
    
    // Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "Users table exists<br>";
        
        // Check admin user
        $stmt = $db->query("SELECT id, username, password, role FROM users WHERE username = 'admin'");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "Admin user found:<br>";
            echo "ID: " . $user['id'] . "<br>";
            echo "Username: " . $user['username'] . "<br>";
            echo "Role: " . $user['role'] . "<br>";
            echo "Password hash: " . $user['password'] . "<br>";
            
            // Test password verification
            $test_password = 'admin123';
            if (password_verify($test_password, $user['password'])) {
                echo "Password verification successful with 'admin123'<br>";
            } else {
                echo "Password verification failed with 'admin123'<br>";
            }
        } else {
            echo "Admin user not found in database<br>";
        }
    } else {
        echo "Users table does not exist<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 