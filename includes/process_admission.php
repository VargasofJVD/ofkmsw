<?php
/**
 * Process Admission Form Submission
 * 
 * This script handles the submission of admission application forms.
 * It validates the input, stores the data in the database, and sends a confirmation email.
 */

// Start session if not already started
session_start();

// Include database connection
require_once '../config/database.php';

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = [
        'child_first_name', 'child_last_name', 'child_date_of_birth', 'child_gender',
        'applying_for_class', 'parent_name', 'parent_phone', 'parent_email', 'parent_address',
        'how_did_you_hear', 'terms'
    ];
    
    $missing_fields = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        $response['message'] = 'Please fill in all required fields.';
        $_SESSION['admission_form_error'] = $response['message'];
        $_SESSION['admission_form_data'] = $_POST; // Store form data for repopulation
        header('Location: ../pages/admissions.php#application-form');
        exit;
    }
    
    // Sanitize and validate input
    $child_first_name = filter_input(INPUT_POST, 'child_first_name', FILTER_SANITIZE_STRING);
    $child_last_name = filter_input(INPUT_POST, 'child_last_name', FILTER_SANITIZE_STRING);
    $child_date_of_birth = filter_input(INPUT_POST, 'child_date_of_birth', FILTER_SANITIZE_STRING);
    $child_gender = filter_input(INPUT_POST, 'child_gender', FILTER_SANITIZE_STRING);
    $applying_for_class = filter_input(INPUT_POST, 'applying_for_class', FILTER_SANITIZE_STRING);
    $parent_name = filter_input(INPUT_POST, 'parent_name', FILTER_SANITIZE_STRING);
    $parent_phone = filter_input(INPUT_POST, 'parent_phone', FILTER_SANITIZE_STRING);
    $parent_email = filter_input(INPUT_POST, 'parent_email', FILTER_SANITIZE_EMAIL);
    $parent_address = filter_input(INPUT_POST, 'parent_address', FILTER_SANITIZE_STRING);
    $previous_school = filter_input(INPUT_POST, 'previous_school', FILTER_SANITIZE_STRING);
    $how_did_you_hear = filter_input(INPUT_POST, 'how_did_you_hear', FILTER_SANITIZE_STRING);
    $additional_info = filter_input(INPUT_POST, 'additional_info', FILTER_SANITIZE_STRING);
    
    // Validate email
    if (!filter_var($parent_email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        $_SESSION['admission_form_error'] = $response['message'];
        $_SESSION['admission_form_data'] = $_POST;
        header('Location: ../pages/admissions.php#application-form');
        exit;
    }
    
    try {
        // Get database connection
        $db = getDbConnection();
        
        // Prepare statement
        $stmt = $db->prepare("INSERT INTO admission_forms 
            (child_first_name, child_last_name, child_date_of_birth, child_gender, 
            applying_for_class, parent_name, parent_phone, parent_email, parent_address, 
            previous_school, how_did_you_hear, additional_info) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Execute statement with sanitized data
        $stmt->execute([
            $child_first_name, $child_last_name, $child_date_of_birth, $child_gender,
            $applying_for_class, $parent_name, $parent_phone, $parent_email, $parent_address,
            $previous_school, $how_did_you_hear, $additional_info
        ]);
        
        // Check if insertion was successful
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Your application has been submitted successfully. We will contact you soon.';
            
            // Store success message in session
            $_SESSION['admission_form_success'] = $response['message'];
            
            // Clear form data from session
            if (isset($_SESSION['admission_form_data'])) {
                unset($_SESSION['admission_form_data']);
            }
            
            // TODO: Send confirmation email to parent and notification to admin
            // This would be implemented with a mail function or library
            
            // Redirect back to admissions page with success message
            header('Location: ../pages/admissions.php#application-form');
            exit;
        } else {
            throw new Exception('Failed to insert data into database.');
        }
    } catch (Exception $e) {
        $response['message'] = 'An error occurred: ' . $e->getMessage();
        $_SESSION['admission_form_error'] = $response['message'];
        $_SESSION['admission_form_data'] = $_POST;
        header('Location: ../pages/admissions.php#application-form');
        exit;
    }
} else {
    // If not a POST request, redirect to the admissions page
    header('Location: ../pages/admissions.php');
    exit;
}