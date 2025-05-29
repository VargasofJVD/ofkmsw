<?php
/**
 * Admin View Student Profile
 * 
 * This page displays the complete profile of a single student.
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

// Get admin information
$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
$admin_role = $_SESSION['admin_role'] ?? 'staff';

// Initialize variables
$error = '';
$student = null; // To hold the student data

// Get student ID from URL
$student_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Redirect if no ID is provided or ID is invalid
if (!$student_id) {
    header('Location: students.php?error=No student ID provided.');
    exit;
}

// Fetch student data
try {
    $db = getDbConnection();
    
    $stmt = $db->prepare("SELECT * FROM students WHERE id = ? LIMIT 1");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Redirect if student not found
    if (!$student) {
        header('Location: students.php?error=Student not found.');
        exit;
    }

} catch (PDOException $e) {
    $error = 'An error occurred while loading student data.';
    error_log('View student data error: ' . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Add any specific styles for this page here */
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="students.php" class="text-white hover:text-gray-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">View Student Profile</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Welcome, <?php echo htmlspecialchars($admin_name); ?></span>
                    <a href="logout.php" class="text-white hover:text-gray-200">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <a href="../index.php" target="_blank" class="text-white hover:text-gray-200" title="View Website">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary-dark text-white admin-sidebar">
            <nav class="p-4 space-y-1">
                <a href="dashboard.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span>Dashboard</span>
                </a>
                <a href="news.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-newspaper w-6"></i>
                    <span>News & Events</span>
                </a>
                <a href="gallery.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-images w-6"></i>
                    <span>Gallery</span>
                </a>
                <a href="testimonials.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-quote-right w-6"></i>
                    <span>Testimonials</span>
                </a>
                <a href="admissions.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-user-plus w-6"></i>
                    <span>Admissions</span>
                </a>
                <a href="students.php" class="flex items-center space-x-2 p-2 rounded active">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span>Students</span>
                </a>
                <a href="messages.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-envelope w-6"></i>
                    <span>Messages</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Student Profile</h1>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($student): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Student ID:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['student_id'] ?? ''); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Full Name:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Registration Number:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['registration_number'] ?? ''); ?></p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Class:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['class'] ?? ''); ?></p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-500">Grade:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['grade'] ?? ''); ?></p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-500">Gender:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['gender'] ?? ''); ?></p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-500">Age:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['age'] ?? ''); ?></p>
                        </div>
                         <div>
                            <p class="text-sm font-medium text-gray-500">Admission Date:</p>
                            <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['admission_date'] ?? ''); ?></p>
                        </div>
                        <!-- Add more student details here as needed -->
                    </div>
                    
                    <!-- More Details Dropdown -->
                    <div class="mt-6">
                        <button id="toggleDetails" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">
                            Toggle Additional Details
                        </button>
                        <div id="additionalDetails" class="hidden mt-4 p-4 bg-gray-100 rounded-lg">
                            <h3 class="text-lg font-bold mb-2 text-gray-800">Additional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Parent Details (assuming columns like parent_name, parent_phone, parent_email) -->
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Parent/Guardian Name:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['parent_name'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Parent/Guardian Phone:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['parent_phone'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Parent/Guardian Email:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['parent_email'] ?? 'N/A'); ?></p>
                                </div>

                                <!-- Address Details (assuming columns like address, city, country) -->
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Address:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['address'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">City:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['city'] ?? 'N/A'); ?></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Country:</p>
                                    <p class="mt-1 text-lg text-gray-900"><?php echo htmlspecialchars($student['country'] ?? 'N/A'); ?></p>
                                </div>
                                
                                <!-- Add other details here as needed -->
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                         <a href="students.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Back to Students
                        </a>
                    </div>
                </div>
            <?php else: ?>
                 <div class="bg-white rounded-lg shadow-md p-6">
                     <p class="text-gray-700">Student not found.</p>
                 </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html> 