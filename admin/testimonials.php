<?php
/**
 * Testimonials Management
 * 
 * manage parent and student testimonials.
 */

// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Get admin information
$admin_name = $_SESSION['admin_name'] ?? 'Administrator';
$admin_role = $_SESSION['admin_role'] ?? 'staff';

// Initialize variables
$error = '';
$success = '';
$testimonials = []; // Initialize testimonials array
$pending_testimonials = []; // Initialize pending testimonials array

try {
    $db = getDbConnection();
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $name = trim($_POST['name'] ?? '');
                    $role = trim($_POST['role'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    $rating = (int)($_POST['rating'] ?? 5);
                    
                    if (empty($name) || empty($role) || empty($content)) {
                        throw new Exception('Name, role, and content are required fields.');
                    }
                    
                    // Handle image upload
                    $image = '';
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = '../uploads/testimonials/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($file_extension, $allowed_extensions)) {
                            throw new Exception('Invalid file format. Allowed formats: JPG, PNG, GIF');
                        }
                        
                        $new_filename = uniqid() . '.' . $file_extension;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                            throw new Exception('Failed to upload image. Please try again.');
                        }
                        
                        $image = 'uploads/testimonials/' . $new_filename;
                    }
                    
                    $stmt = $db->prepare("INSERT INTO testimonials (name, role, content, rating, image, created_by) VALUES (?, ?, ?, ?, ?, ?)");
                    if (!$stmt->execute([$name, $role, $content, $rating, $image, $_SESSION['admin_id']])) {
                        throw new Exception('Failed to add testimonial. Please try again.');
                    }
                    $success = 'Testimonial added successfully!';
                    break;
                
                case 'edit':
                    if (!isset($_POST['id'])) {
                        throw new Exception('Invalid testimonial ID.');
                    }
                    
                    $id = (int)$_POST['id'];
                    $name = trim($_POST['name'] ?? '');
                    $role = trim($_POST['role'] ?? '');
                    $content = trim($_POST['content'] ?? '');
                    $rating = (int)($_POST['rating'] ?? 5);
                    
                    if (empty($name) || empty($role) || empty($content)) {
                        throw new Exception('Name, role, and content are required fields.');
                    }
                    
                    // Handle image upload
                    $image = $_POST['current_image'] ?? '';
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = '../uploads/testimonials/';
                        if (!file_exists($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        
                        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                        
                        if (!in_array($file_extension, $allowed_extensions)) {
                            throw new Exception('Invalid file format. Allowed formats: JPG, PNG, GIF');
                        }
                        
                        $new_filename = uniqid() . '.' . $file_extension;
                        $upload_path = $upload_dir . $new_filename;
                        
                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                            throw new Exception('Failed to upload image. Please try again.');
                        }
                        
                        // Delete old image if exists
                        if ($image && file_exists('../' . $image)) {
                            unlink('../' . $image);
                        }
                        $image = 'uploads/testimonials/' . $new_filename;
                    }
                    
                    $stmt = $db->prepare("UPDATE testimonials SET name = ?, role = ?, content = ?, rating = ?, image = ? WHERE id = ?");
                    if (!$stmt->execute([$name, $role, $content, $rating, $image, $id])) {
                        throw new Exception('Failed to update testimonial. Please try again.');
                    }
                    $success = 'Testimonial updated successfully!';
                    break;
                
                case 'delete':
                    if (!isset($_POST['id'])) {
                        throw new Exception('Invalid testimonial ID.');
                    }
                    
                    // Get image path before deleting
                    $stmt = $db->prepare("SELECT image FROM testimonials WHERE id = ?");
                    if (!$stmt->execute([$_POST['id']])) {
                        throw new Exception('Failed to find testimonial. Please try again.');
                    }
                    $image = $stmt->fetchColumn();
                    
                    // Delete the record
                    $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
                    if (!$stmt->execute([$_POST['id']])) {
                        throw new Exception('Failed to delete testimonial. Please try again.');
                    }
                    
                    // Delete image if exists
                    if ($image && file_exists('../' . $image)) {
                        unlink('../' . $image);
                    }
                    
                    $success = 'Testimonial deleted successfully!';
                    break;
                
                case 'approve_testimonial':
                    if (isset($_POST['id'])) {
                        // Get pending testimonial
                        $stmt = $db->prepare("SELECT * FROM pending_testimonials WHERE id = ?");
                        $stmt->execute([$_POST['id']]);
                        $testimonial = $stmt->fetch();
                        
                        if ($testimonial) {
                            // Insert into main testimonials table
                            $stmt = $db->prepare("INSERT INTO testimonials (name, role, content, rating, image, created_by) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->execute([
                                $testimonial['name'],
                                $testimonial['role'],
                                $testimonial['content'],
                                $testimonial['rating'],
                                $testimonial['image'],
                                $_SESSION['admin_id']
                            ]);
                            
                            // Update status in pending_testimonials
                            $stmt = $db->prepare("UPDATE pending_testimonials SET status = 'approved' WHERE id = ?");
                            $stmt->execute([$_POST['id']]);
                            
                            $success = 'Testimonial approved and published successfully!';
                        }
                    }
                    break;
                
                case 'reject_testimonial':
                    if (isset($_POST['id'])) {
                        $stmt = $db->prepare("UPDATE pending_testimonials SET status = 'rejected' WHERE id = ?");
                        $stmt->execute([$_POST['id']]);
                        $success = 'Testimonial rejected successfully!';
                    }
                    break;
            }
        }
    }
    
    // Get all testimonials
    $stmt = $db->query("SELECT t.*, u.username as created_by_name 
                        FROM testimonials t 
                        LEFT JOIN users u ON t.created_by = u.id 
                        ORDER BY t.created_at DESC");
    if (!$stmt) {
        throw new Exception('Failed to fetch testimonials. Please try again.');
    }
    $testimonials = $stmt->fetchAll();
    
    // Get pending testimonials
    $stmt = $db->query("SELECT * FROM pending_testimonials WHERE status = 'pending' ORDER BY created_at DESC");
    $pending_testimonials = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log('Database error in testimonials management: ' . $e->getMessage());
    $error = 'A database error occurred. Please try again later.';
} catch (Exception $e) {
    error_log('Error in testimonials management: ' . $e->getMessage());
    $error = $e->getMessage();
}

// Set page title
$page_title = 'Testimonials Management';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Krisah Montessori School</title>
    
    <!-- Favicon -->
    <link rel="icon" href="../assets/images/favicon.ico" type="image/x-icon">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            light: '#4B83A6',
                            DEFAULT: '#2A5674',
                            dark: '#1A3A50'
                        },
                        secondary: {
                            light: '#F9A03F',
                            DEFAULT: '#F78C0C',
                            dark: '#D67600'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">
    <!-- Admin Header -->
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="flex items-center space-x-2">
                    <img src="../assets/images/logo.png" alt="Krisah Montessori School" class="h-10">
                    <span class="font-bold text-lg">Admin Panel</span>
                </a>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative group">
                    <button class="flex items-center space-x-2 focus:outline-none">
                        <span><?php echo htmlspecialchars($admin_name); ?></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
                        <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <a href="change-password.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-key mr-2"></i> Change Password
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </div>
                </div>
                <a href="../index.php" target="_blank" class="text-white hover:text-gray-200" title="View Website">
                    <i class="fas fa-external-link-alt"></i>
                </a>
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
                <a href="testimonials.php" class="flex items-center space-x-2 p-2 rounded active">
                    <i class="fas fa-quote-right w-6"></i>
                    <span>Testimonials</span>
                </a>
                <a href="settings.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-cog w-6"></i>
                    <span>Settings</span>
                </a>
                <?php if ($admin_role === 'admin'): ?>
                <a href="users.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-users-cog w-6"></i>
                    <span>User Management</span>
                </a>
                <?php endif; ?>
                <div class="border-t border-primary my-4"></div>
                <a href="logout.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Testimonials</h1>
                    <p class="text-gray-600">Manage parent and student testimonials</p>
                </div>
                <button onclick="document.getElementById('addTestimonialModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md transition duration-300">
                    <i class="fas fa-plus mr-2"></i> Add New
                </button>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Testimonials List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <?php if ($testimonial['image']): ?>
                                <img src="../<?php echo htmlspecialchars($testimonial['image']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="h-16 w-16 rounded-full object-cover mr-4">
                            <?php else: ?>
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-gray-400 text-2xl"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($testimonial['name']); ?></h3>
                                <p class="text-gray-600"><?php echo htmlspecialchars($testimonial['role']); ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex text-yellow-400 mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 italic"><?php echo htmlspecialchars($testimonial['content']); ?></p>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span><?php echo date('M d, Y', strtotime($testimonial['created_at'])); ?></span>
                            <div class="flex space-x-2">
                                <button onclick="editTestimonial(<?php echo htmlspecialchars(json_encode($testimonial)); ?>)" class="text-primary hover:text-primary-dark">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTestimonial(<?php echo $testimonial['id']; ?>)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Add this section after the existing testimonials grid -->
            <?php if (!empty($pending_testimonials)): ?>
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Pending Testimonials</h2>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($pending_testimonials as $item): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <?php if (!empty($item['image'])): ?>
                                            <img src="../uploads/testimonials/<?php echo htmlspecialchars($item['image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                 class="w-10 h-10 rounded-full mr-3 object-cover">
                                            <?php endif; ?>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($item['name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($item['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($item['role']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-4 h-4 <?php echo $i <= $item['rating'] ? 'text-yellow-400' : 'text-gray-300'; ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            <?php echo htmlspecialchars($item['content']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick='viewTestimonial(<?php echo json_encode($item); ?>)' 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <form method="post" class="inline">
                                            <input type="hidden" name="action" value="approve_testimonial">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="text-green-600 hover:text-green-900 mr-3">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="post" class="inline">
                                            <input type="hidden" name="action" value="reject_testimonial">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
    </div>
    
    <!-- Add Testimonial Modal -->
    <div id="addTestimonialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Testimonial</h3>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="add">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <input type="text" name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary" placeholder="e.g., Parent, Student, Alumni">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <select name="rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                        <p class="mt-1 text-sm text-gray-500">Optional. Allowed formats: JPG, PNG, GIF</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addTestimonialModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Testimonial Modal -->
    <div id="editTestimonialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Testimonial</h3>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="current_image" id="edit_current_image">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <input type="text" name="role" id="edit_role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <select name="rating" id="edit_rating" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Star<?php echo $i > 1 ? 's' : ''; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Content</label>
                        <textarea name="content" id="edit_content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Photo</label>
                        <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to keep current photo. Allowed formats: JPG, PNG, GIF</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editTestimonialModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add View Testimonial Modal -->
    <div id="viewTestimonialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Testimonial Details</h3>
                    <button onclick="document.getElementById('viewTestimonialModal').classList.add('hidden')" 
                            class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div id="view_testimonial_image" class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-2xl"></i>
                        </div>
                        <div>
                            <h4 id="view_testimonial_name" class="text-lg font-semibold text-gray-900"></h4>
                            <p id="view_testimonial_role" class="text-gray-600"></p>
                            <p id="view_testimonial_email" class="text-sm text-gray-500"></p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <div id="view_testimonial_rating" class="flex"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Testimonial</label>
                        <p id="view_testimonial_content" class="text-gray-700 whitespace-pre-wrap"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submitted On</label>
                        <p id="view_testimonial_date" class="text-gray-600"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Edit testimonial
        function editTestimonial(testimonial) {
            document.getElementById('edit_id').value = testimonial.id;
            document.getElementById('edit_name').value = testimonial.name;
            document.getElementById('edit_role').value = testimonial.role;
            document.getElementById('edit_rating').value = testimonial.rating;
            document.getElementById('edit_content').value = testimonial.content;
            document.getElementById('edit_current_image').value = testimonial.image;
            
            document.getElementById('editTestimonialModal').classList.remove('hidden');
        }
        
        // Delete testimonial
        function deleteTestimonial(id) {
            if (confirm('Are you sure you want to delete this testimonial?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // View testimonial
        function viewTestimonial(testimonial) {
            // Set testimonial details
            document.getElementById('view_testimonial_name').textContent = testimonial.name;
            document.getElementById('view_testimonial_role').textContent = testimonial.role;
            document.getElementById('view_testimonial_email').textContent = testimonial.email;
            document.getElementById('view_testimonial_content').textContent = testimonial.content;
            document.getElementById('view_testimonial_date').textContent = new Date(testimonial.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            // Set rating stars
            const ratingContainer = document.getElementById('view_testimonial_rating');
            ratingContainer.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('svg');
                star.className = `w-5 h-5 ${i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'}`;
                star.setAttribute('fill', 'currentColor');
                star.setAttribute('viewBox', '0 0 20 20');
                star.innerHTML = '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
                ratingContainer.appendChild(star);
            }
            
            // Set image if exists
            const imageContainer = document.getElementById('view_testimonial_image');
            if (testimonial.image) {
                imageContainer.innerHTML = `<img src="../uploads/testimonials/${testimonial.image}" alt="${testimonial.name}" class="w-16 h-16 rounded-full object-cover">`;
            } else {
                imageContainer.innerHTML = '<i class="fas fa-user text-gray-400 text-2xl"></i>';
            }
            
            // Show modal
            document.getElementById('viewTestimonialModal').classList.remove('hidden');
        }
    </script>
</body>
</html> 