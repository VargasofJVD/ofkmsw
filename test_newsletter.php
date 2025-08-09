<?php
/**
 * Test script for newsletter subscription
 */

// Include database connection
require_once 'config/database.php';

echo "<h2>Newsletter Subscription Test</h2>";

try {
    $db = getDbConnection();
    
    // Check if table exists
    $stmt = $db->query("SHOW TABLES LIKE 'newsletter_subscribers'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Newsletter subscribers table exists</p>";
        
        // Check table structure
        $stmt = $db->query("DESCRIBE newsletter_subscribers");
        echo "<h3>Table Structure:</h3>";
        echo "<ul>";
        while ($row = $stmt->fetch()) {
            echo "<li><strong>{$row['Field']}</strong> - {$row['Type']} - {$row['Null']} - {$row['Key']} - {$row['Default']}</li>";
        }
        echo "</ul>";
        
        // Count existing subscribers
        $stmt = $db->query("SELECT COUNT(*) as count FROM newsletter_subscribers");
        $count = $stmt->fetch()['count'];
        echo "<p>Current subscribers: <strong>{$count}</strong></p>";
        
    } else {
        echo "<p style='color: red;'>❌ Newsletter subscribers table does not exist</p>";
        echo "<p>Please run the database setup scripts:</p>";
        echo "<ul>";
        echo "<li>mysql -u root -p < database/mvp_setup.sql</li>";
        echo "<li>mysql -u root -p < database/create_newsletter_subscribers.sql</li>";
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>Test Form:</h3>";
echo "<form method='post' action='includes/subscribe.php'>";
echo "<input type='text' name='name' placeholder='Name' required><br><br>";
echo "<input type='email' name='email' placeholder='Email' required><br><br>";
echo "<input type='checkbox' name='consent' required> I agree to receive newsletter updates<br><br>";
echo "<button type='submit'>Test Subscribe</button>";
echo "</form>";
?> 