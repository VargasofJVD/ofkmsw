<?php
/**
 * Newsletter Subscription Handler
 * 
 * Handles newsletter subscription form submissions
 */

// Include database connection
require_once '../config/database.php';

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$consent = $_POST['consent'] ?? '';

// Validate required fields
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Full name is required']);
    exit;
}

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email address is required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

if (empty($consent)) {
    echo json_encode(['success' => false, 'message' => 'Please agree to receive newsletter updates']);
    exit;
}

try {
    $db = getDbConnection();
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'This email is already subscribed to our newsletter']);
        exit;
    }
    
    // Insert new subscriber
    $stmt = $db->prepare("INSERT INTO newsletter_subscribers (name, email, subscribed_at) VALUES (?, ?, NOW())");
    $stmt->execute([$name, $email]);
    
    echo json_encode(['success' => true, 'message' => 'Thank you for subscribing to our newsletter, ' . htmlspecialchars($name) . '!']);
    
} catch (PDOException $e) {
    error_log('Newsletter subscription error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?> 