<?php
/**
 * Admin Students Management
 * 
 * This page allows administrators to view, add, edit, and delete student information.
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
$success = '';

// Check for messages from other pages (like delete_student.php)
$redirect_message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);
$message_type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);

if (!empty($redirect_message)) {
    if ($message_type === 'success') {
        $success = $redirect_message;
    } else {
        $error = $redirect_message;
    }
}

// Handle Add/Edit/Delete actions (placeholder)
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     try {
//         $db = getDbConnection();
//         // Handle form submissions for adding, editing, deleting
//     } catch (PDOException $e) {
//         $error = 'An error occurred while processing your request.';
//         error_log('Student management error: ' . $e->getMessage());
//     }
// }

// Get students data
try {
    $db = getDbConnection();
    // Fetch all students (or apply filters later)
    $stmt = $db->query("SELECT * FROM students ORDER BY admission_date DESC"); // Assuming 'students' table and 'admission_date' column
    $students = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error = 'An error occurred while loading student data.';
    error_log('Student data loading error: ' . $e->getMessage());
    $students = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Add any specific styles for this page here */
    </style>
</head>
<!-- <body class="bg-gray-100 min-h-screen"> -->
<body class="bg-gray-900 font-sans min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-white hover:text-gray-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">Students Management</h1>
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
                <h1 class="text-2xl font-bold text-gray-800">Students Management</h1>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Add Student Button -->
            <div class="mb-6 text-right">
                <a href="add_student.php" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus"></i> Add New Student
                </a>
            </div>
            
            <!-- Students Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No students found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($student['class'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($student['admission_date'] ?? 'now')); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="edit_student.php?id=<?php echo $student['id'] ?? ''; ?>" class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="delete_student.php?id=<?php echo $student['id'] ?? ''; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this student?');">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 