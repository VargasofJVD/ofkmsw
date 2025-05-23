<?php
/**
 * Database Configuration File
 * 
 * This file contains the database connection settings for the Krisah Montessori School website.
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Change this in production
define('DB_PASS', '');     // Change this in production
define('DB_NAME', 'krisah_db');

/**
 * Get database connection
 * 
 * @return PDO Database connection object
 */
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Log error and display user-friendly message
        error_log('Database Connection Error: ' . $e->getMessage());
        die("Sorry, there was a problem connecting to the database. Please try again later.");
    }
}