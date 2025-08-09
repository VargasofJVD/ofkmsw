<?php
/**
 * Admin Credentials Helper
 * Use this script to check or reset admin credentials
 */

// Include database connection
require_once 'config/database.php';

echo "<h2>Admin Credentials Helper</h2>";

try {
    $db = getDbConnection();
    
    // Check existing admin users
    $stmt = $db->query("SELECT id, username, email, full_name, role FROM users WHERE role = 'admin'");
    $admins = $stmt->fetchAll();
    
    if (count($admins) > 0) {
        echo "<h3>Current Admin Users:</h3>";
        echo "<ul>";
        foreach ($admins as $admin) {
            echo "<li><strong>Username:</strong> {$admin['username']}</li>";
            echo "<li><strong>Email:</strong> {$admin['email']}</li>";
            echo "<li><strong>Full Name:</strong> {$admin['full_name']}</li>";
            echo "<li><strong>Role:</strong> {$admin['role']}</li>";
            echo "<hr>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>No admin users found!</p>";
    }
    
    echo "<h3>Default Admin Credentials:</h3>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> admin</li>";
    echo "<li><strong>Password:</strong> admin123</li>";
    echo "<li><strong>URL:</strong> <a href='http://localhost/ofkmsw/admin/'>http://localhost/ofkmsw/admin/</a></li>";
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Reset Admin Password (Optional):</h3>";
echo "<form method='post'>";
echo "<input type='hidden' name='action' value='reset_password'>";
echo "<label>New Password: <input type='password' name='new_password' required></label><br><br>";
echo "<button type='submit'>Reset Admin Password</button>";
echo "</form>";

// Handle password reset
if ($_POST['action'] === 'reset_password') {
    $new_password = $_POST['new_password'] ?? '';
    
    if (!empty($new_password)) {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
            $stmt->execute([$hashed_password]);
            
            echo "<p style='color: green;'>✅ Admin password updated successfully!</p>";
            echo "<p><strong>New credentials:</strong></p>";
            echo "<ul>";
            echo "<li><strong>Username:</strong> admin</li>";
            echo "<li><strong>Password:</strong> " . htmlspecialchars($new_password) . "</li>";
            echo "</ul>";
            
        } catch (PDOException $e) {
            echo "<p style='color: red;'>❌ Error updating password: " . $e->getMessage() . "</p>";
        }
    }
}
?> 