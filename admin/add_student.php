<?php
/**
 * Admin Add New Student
 * 
 * This page allows administrators to add a new student record.
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
$form_data = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDbConnection();
        
        // Sanitize and validate input
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $class = trim($_POST['class'] ?? '');
        $admission_date = trim($_POST['admission_date'] ?? '');
        
        // Store submitted data to repopulate form in case of error
        $form_data = $_POST;

        // Basic validation
        if (empty($first_name) || empty($last_name) || empty($class) || empty($admission_date)) {
            throw new Exception('All fields are required.');
        }

        // Assuming a 'students' table with columns: id, first_name, last_name, class, admission_date, created_at, updated_at
        // Prepare and execute INSERT statement
        $stmt = $db->prepare("INSERT INTO students (first_name, last_name, class, admission_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $class, $admission_date]);
        
        $success = 'Student added successfully!';
        
        // Clear form data after successful submission
        $form_data = [];

    } catch (Exception $e) {
        $error = 'An error occurred: ' . $e->getMessage();
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
        error_log('Add student database error: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - Admin Dashboard</title>
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
                    <a href="students.php" class="text-white hover:text-gray-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">Add New Student</h1>
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
                <h1 class="text-2xl font-bold text-gray-800">Add New Student</h1>
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
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="post" action="add_student.php" class="space-y-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" id="first_name" name="first_name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                               value="<?php echo htmlspecialchars($form_data['first_name'] ?? ''); ?>">
                    </div>
                    
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                               value="<?php echo htmlspecialchars($form_data['last_name'] ?? ''); ?>">
                    </div>
                    
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700">Class *</label>
                        <select id="class" name="class" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="">Select Class</option>
                            <option value="Day Care" <?php echo (($form_data['class'] ?? '') === 'Day Care') ? 'selected' : ''; ?>>Day Care</option>
                            <option value="Nursery 1" <?php echo (($form_data['class'] ?? '') === 'Nursery 1') ? 'selected' : ''; ?>>Nursery 1</option>
                            <option value="Nursery 2" <?php echo (($form_data['class'] ?? '') === 'Nursery 2') ? 'selected' : ''; ?>>Nursery 2</option>
                            <option value="K.G. 1" <?php echo (($form_data['class'] ?? '') === 'K.G. 1') ? 'selected' : ''; ?>>K.G. 1</option>
                            <option value="K.G. 2" <?php echo (($form_data['class'] ?? '') === 'K.G. 2') ? 'selected' : ''; ?>>K.G. 2</option>
                            <option value="Grade 1" <?php echo (($form_data['class'] ?? '') === 'Grade 1') ? 'selected' : ''; ?>>Grade 1</option>
                            <option value="Grade 2" <?php echo (($form_data['class'] ?? '') === 'Grade 2') ? 'selected' : ''; ?>>Grade 2</option>
                            <option value="Grade 3" <?php echo (($form_data['class'] ?? '') === 'Grade 3') ? 'selected' : ''; ?>>Grade 3</option>
                            <option value="Grade 4" <?php echo (($form_data['class'] ?? '') === 'Grade 4') ? 'selected' : ''; ?>>Grade 4</option>
                            <option value="Grade 5" <?php echo (($form_data['class'] ?? '') === 'Grade 5') ? 'selected' : ''; ?>>Grade 5</option>
                            <option value="Grade 6" <?php echo (($form_data['class'] ?? '') === 'Grade 6') ? 'selected' : ''; ?>>Grade 6</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date *</label>
                        <input type="date" id="admission_date" name="admission_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                               value="<?php echo htmlspecialchars($form_data['admission_date'] ?? ''); ?>">
                    </div>
                    
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 