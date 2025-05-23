<?php
// Include database configuration
require_once '../config/database.php';

try {
    // Get database connection
    $db = getDbConnection();
    
    // Drop table if exists
    $db->exec("DROP TABLE IF EXISTS news_events");
    
    // Read SQL file
    $sql = file_get_contents('create_news_events.sql');
    
    // Execute SQL
    $db->exec($sql);
    
    echo "News and events table created successfully!";
} catch (PDOException $e) {
    die("Error creating news and events table: " . $e->getMessage());
}
?> 