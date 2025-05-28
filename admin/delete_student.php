<?php
/**
 * Admin Delete Student
 * 
 * This script handles the deletion of a student record.
 */

// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page
    header('Location: index.php');
    exit;
}

// Initialize variables for redirection messages
$message = '';
$message_type = 'error';

// Get student ID from URL
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Check if ID is provided and is a valid integer
if (!$student_id) {
    $message = 'No student ID provided.';
} else {
    try {
        $db = getDbConnection();
        
        // Prepare and execute DELETE statement
        $stmt = $db->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        
        // Check if deletion was successful (at least one row affected)
        if ($stmt->rowCount() > 0) {
            $message = 'Student deleted successfully.';
            $message_type = 'success';
        } else {
            $message = 'Student not found or could not be deleted.';
        }

    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
        error_log('Delete student database error: ' . $e->getMessage());
    }
}

// Redirect back to students list page with message
header('Location: students.php?message=' . urlencode($message) . '&type=' . urlencode($message_type));
exit;
?> 