<?php
require_once '../config/database.php';

try {
    $db = getDbConnection();
    
    // New password
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update admin user's password
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $result = $stmt->execute([$hashed_password]);
    
    if ($result) {
        echo "Admin password has been reset successfully.<br>";
        echo "New password: admin123<br>";
        echo "New password hash: " . $hashed_password . "<br>";
        
        // Verify the new password
        if (password_verify($new_password, $hashed_password)) {
            echo "Password verification successful!<br>";
        } else {
            echo "Password verification failed!<br>";
        }
    } else {
        echo "Failed to reset password.<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 