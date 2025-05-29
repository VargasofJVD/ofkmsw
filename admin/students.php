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
$search_query = '';
$total_students = 0;
$early_childhood_students = 0;
$lower_primary_students = 0;
$upper_primary_students = 0;
$jhs_students = 0;
$search_result_count = 0;

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
    
    // Check if a search query is present
    if (isset($_GET['searchStudent']) && !empty($_GET['searchStudent'])) {
        $search_query = filter_input(INPUT_GET, 'searchStudent', FILTER_SANITIZE_STRING);
        // Fetch students matching the search query (by name or registration number)
        $stmt = $db->prepare("SELECT * FROM students WHERE first_name LIKE :search_query OR last_name LIKE :search_query OR registration_number LIKE :search_query ORDER BY admission_date DESC");
        $stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
        $stmt->execute();
    } else {
        // Fetch all students
        $stmt = $db->query("SELECT * FROM students ORDER BY admission_date DESC");
    }
    
    $students = $stmt->fetchAll();
    
    // Fetch student counts for statistics
    $total_students_stmt = $db->query("SELECT COUNT(*) FROM students");
    $total_students = $total_students_stmt->fetchColumn();

    $early_childhood_stmt = $db->query("SELECT COUNT(*) FROM students WHERE grade < 1"); // Assuming grades less than 1 for Day Care/Early Childhood
    $early_childhood_students = $early_childhood_stmt->fetchColumn();

    $lower_primary_stmt = $db->query("SELECT COUNT(*) FROM students WHERE grade BETWEEN 1 AND 3");
    $lower_primary_students = $lower_primary_stmt->fetchColumn();

    $upper_primary_stmt = $db->query("SELECT COUNT(*) FROM students WHERE grade BETWEEN 4 AND 6");
    $upper_primary_students = $upper_primary_stmt->fetchColumn();

    $jhs_students_stmt = $db->query("SELECT COUNT(*) FROM students WHERE grade BETWEEN 7 AND 9"); // Assuming grades 7-9 for JHS
    $jhs_students = $jhs_students_stmt->fetchColumn();

    // If there's a search query, also get the count of search results
    $search_result_count = count($students);
    
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
            
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Search Result Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Search Result</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $search_result_count; ?></h2>
                    </div>
                    <i class="fas fa-search text-3xl text-blue-500"></i>
                </div>
                
                <!-- Total Students Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Students</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $total_students; ?></h2>
                    </div>
                    <i class="fas fa-users text-3xl text-green-500"></i>
                </div>
                
                <!-- Early Childhood Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Early Childhood (< Grade 1)</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $early_childhood_students; ?></h2>
                    </div>
                    <i class="fas fa-child text-3xl text-yellow-500"></i>
                </div>
                
                <!-- Lower Primary Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Lower Primary (Grades 1-3)</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $lower_primary_students; ?></h2>
                    </div>
                    <i class="fas fa-school text-3xl text-orange-500"></i>
                </div>
                 <!-- Upper Primary Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Upper Primary (Grades 4-6)</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $upper_primary_students; ?></h2>
                    </div>
                    <i class="fas fa-school text-3xl text-red-500"></i>
                </div>
                 <!-- JHS Card -->
                <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">JHS (Grades 7-9)</p>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo $jhs_students; ?></h2>
                    </div>
                    <i class="fas fa-university text-3xl text-purple-500"></i>
                </div>
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
            <div class="mb-6 text-right flex justify-between items-center">
                <!-- Search Input -->
                <div class="w-1/3">
                    <input type="text" id="searchStudent" name="searchStudent" placeholder="Search student by ID or Name" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300 text-gray-700" value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg Num</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($students)): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found.</td>
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
                                                <?php echo htmlspecialchars($student['registration_number'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($student['grade'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($student['gender'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($student['age'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="view_student.php?id=<?php echo $student['id'] ?? ''; ?>" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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