<?php
/**
 * Admin Admissions Management
 * 
 * This page allows administrators to view and manage admission applications.
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

// Handle form processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDbConnection();
        
        if (isset($_POST['action']) && isset($_POST['form_id'])) {
            $form_id = (int)$_POST['form_id'];
            $action = $_POST['action'];
            
            if ($action === 'approve') {
                // First, fetch the admission form data
                $stmt_fetch = $db->prepare("SELECT * FROM admission_forms WHERE id = ?");
                $stmt_fetch->execute([$form_id]);
                $admission_data = $stmt_fetch->fetch(PDO::FETCH_ASSOC);

                if ($admission_data) {
                    // Generate a unique student ID
                    $student_id = uniqid('STU_', true); // Prefix with STU_ for clarity

                    // Insert data into the students table
                    // Assuming the following columns exist in admission_forms and students tables.
                    // Adjust column names as per your actual database schema.
                    $stmt_insert = $db->prepare("INSERT INTO students (student_id, first_name, last_name, class, admission_date, registration_number, grade, gender, age, parent_name, parent_phone, parent_email, address, city, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    
                    // Map data from admission_forms to students table columns
                    // Use N/A or default values for columns not present in admission_forms
                    $stmt_insert->execute([
                        $student_id,
                        $admission_data['child_first_name'] ?? '',
                        $admission_data['child_last_name'] ?? '',
                        $admission_data['applying_for_class'] ?? '', // Assuming this maps to 'class'
                        $admission_data['created_at'] ?? date('Y-m-d'), // Using created_at as admission_date, default to today
                        $admission_data['registration_number'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['grade'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['gender'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['age'] ?? 0, // Assuming column exists in admission_forms, default to 0
                        $admission_data['parent_name'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['parent_phone'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['parent_email'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['address'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['city'] ?? '', // Assuming column exists in admission_forms
                        $admission_data['country'] ?? '' // Assuming column exists in admission_forms
                    ]);

                     // Update admission form status
                    $stmt = $db->prepare("UPDATE admission_forms SET status = 'approved', is_processed = 1 WHERE id = ?");
                    $stmt->execute([$form_id]);
                    $success = 'Application has been approved and student added.';
                } else {
                    $error = 'Could not fetch admission form data.';
                }
            } elseif ($action === 'reject') {
                // Update admission form status
                $stmt = $db->prepare("UPDATE admission_forms SET status = 'rejected', is_processed = 1 WHERE id = ?");
                $stmt->execute([$form_id]);
                $success = 'Application has been rejected.';
            }
        }
    } catch (PDOException $e) {
        $error = 'An error occurred while processing the application.';
        error_log('Admission processing error: ' . $e->getMessage());
    }
}

// Get admission forms
try {
    $db = getDbConnection();
    
    // Get filter parameters
    $status_filter = $_GET['status'] ?? 'all';
    $class_filter = $_GET['class'] ?? 'all';
    $search_term = trim($_GET['search'] ?? '');
    
    // Build query
    $query = "SELECT * FROM admission_forms WHERE 1=1";
    $params = [];
    
    if ($status_filter !== 'all') {
        $query .= " AND status = ?";
        $params[] = $status_filter;
    }
    
    if ($class_filter !== 'all') {
        $query .= " AND applying_for_class = ?";
        $params[] = $class_filter;
    }
    
    if (!empty($search_term)) {
        $query .= " AND (child_first_name LIKE ? OR child_last_name LIKE ? OR parent_email LIKE ?)";
        $params[] = '%' . $search_term . '%';
        $params[] = '%' . $search_term . '%';
        $params[] = '%' . $search_term . '%';
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $admission_forms = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error = 'An error occurred while loading admission forms.';
    error_log('Admission forms loading error: ' . $e->getMessage());
    $admission_forms = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        #viewModal.hidden { display: none !important; }
    </style>
</head>
<body class="bg-gray-900 font-sans min-h-screen flex flex-col">
<!-- <body class="bg-gray-50 font-sans min-h-screen flex flex-col"> -->
    <!-- Header -->
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-white hover:text-gray-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="text-2xl font-bold">Admissions Management</h1>
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
                <a href="admissions.php" class="flex items-center space-x-2 p-2 rounded active">
                    <i class="fas fa-user-plus w-6"></i>
                    <span>Admissions</span>
                </a>
                <a href="students.php" class="flex items-center space-x-2 p-2 rounded">
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
        <main class="flex-1 p-6 relative overflow-hidden">
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
            
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="get" class="flex flex-wrap gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>Approved</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                        <select id="class" name="class" class="rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="all" <?php echo $class_filter === 'all' ? 'selected' : ''; ?>>All Classes</option>
                            <option value="preschool" <?php echo $class_filter === 'preschool' ? 'selected' : ''; ?>>Preschool</option>
                            <option value="lower_primary" <?php echo $class_filter === 'lower_primary' ? 'selected' : ''; ?>>Lower Primary</option>
                            <option value="upper_primary" <?php echo $class_filter === 'upper_primary' ? 'selected' : ''; ?>>Upper Primary</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="search" name="search" placeholder="Search by name or email..."
                               class="rounded-md border-blue-900 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Applications Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($admission_forms)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No applications found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($admission_forms as $form): ?>
                                    <?php $status = $form['status'] ?? 'pending'; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo htmlspecialchars($form['child_first_name'] . ' ' . $form['child_last_name']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($form['applying_for_class']))); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($form['parent_name']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo htmlspecialchars($form['parent_email']); ?><br>
                                                <?php echo htmlspecialchars($form['parent_phone']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <?php echo date('M d, Y', strtotime($form['created_at'])); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php
                                            $status_class = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ][$status];
                                            ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="viewApplication(<?php echo $form['id']; ?>)" class="text-primary hover:text-primary-dark">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <?php if ($status === 'pending'): ?>
                                                    <form method="post" class="inline">
                                                        <input type="hidden" name="form_id" value="<?php echo $form['id']; ?>">
                                                        <input type="hidden" name="action" value="approve">
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form method="post" class="inline">
                                                        <input type="hidden" name="form_id" value="<?php echo $form['id']; ?>">
                                                        <input type="hidden" name="action" value="reject">
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        
            <!-- View Application Modal (now inside main, absolute) -->
            <div id="viewModal" class="absolute inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Application Details</h3>
                        <div class="mt-2 px-7 py-3">
                            <div id="modalContent" class="text-left">
                                <!-- Content will be loaded dynamically -->
                            </div>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button id="closeModal" class="px-4 py-2 bg-primary text-white text-base font-medium rounded-md shadow-sm hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // View Application Modal
        function viewApplication(id) {
            const modal = document.getElementById('viewModal');
            const modalContent = document.getElementById('modalContent');
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Load application details
            fetch(`view_admission_modal.php?id=${id}`)
                .then(response => response.text())
                .then(html => {
                    modalContent.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalContent.innerHTML = '<p class="text-red-500">Error loading application details.</p>';
                });
        }
        
        // Close modal
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('viewModal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    </script>
</body>
</html> 