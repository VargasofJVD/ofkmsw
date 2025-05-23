<?php
/**
 * Gallery Management
 * 
 * Allows administrators to manage the school's image and video gallery.
 */

// Start session
session_start();

// Add these constants at the top of the file after the session_start()
define('MAX_IMAGE_SIZE', 50 * 1024 * 1024); // 50MB
define('MAX_VIDEO_SIZE', 100 * 1024 * 1024); // 100MB

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

// Add this function at the top of the file after session_start()
function handleUploadError($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return 'The file is too large. Maximum allowed size is 100MB for videos and 50MB for images.';
        case UPLOAD_ERR_PARTIAL:
            return 'The file was only partially uploaded. Please try again.';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded. Please select a file.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder. Please contact the administrator.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk. Please try again.';
        case UPLOAD_ERR_EXTENSION:
            return 'A PHP extension stopped the file upload. Please try a different file format.';
        default:
            return 'An unknown error occurred during upload. Please try again.';
    }
}

// Add this function to check for PHP configuration errors
function checkPhpUploadErrors() {
    if (isset($_SERVER['CONTENT_LENGTH']) && empty($_POST) && empty($_FILES)) {
        $max_size = ini_get('post_max_size');
        return "The file exceeds the server's maximum upload size limit of {$max_size}. Please choose a smaller file.";
    }
    return null;
}

try {
    $db = getDbConnection();
    
    // Get all categories
    $cat_stmt = $db->query("SELECT * FROM gallery_categories ORDER BY name ASC");
    $categories = $cat_stmt->fetchAll();
    
    // Handle form submissions
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check for PHP configuration errors first
        $php_error = checkPhpUploadErrors();
        if ($php_error) {
            $error = $php_error;
        } else if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $title = trim($_POST['title'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    $file_type = $_POST['file_type'] ?? 'image';
                    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
                    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                    
                    // Handle file upload
                    $file_path = '';
                    if (isset($_FILES['file'])) {
                        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                            $upload_dir = '../uploads/gallery/';
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }
                            
                            $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                            $file_size = $_FILES['file']['size'];
                            
                            // Check file size based on type
                            if ($file_type === 'image' && $file_size > MAX_IMAGE_SIZE) {
                                $error = 'Image file size exceeds the limit of 50MB.';
                            } elseif ($file_type === 'video' && $file_size > MAX_VIDEO_SIZE) {
                                $error = 'Video file size exceeds the limit of 100MB.';
                            } else {
                                if ($file_type === 'image') {
                                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                                } else {
                                    $allowed_extensions = ['mp4', 'webm', 'ogg'];
                                }
                                
                                if (in_array($file_extension, $allowed_extensions)) {
                                    $new_filename = uniqid() . '.' . $file_extension;
                                    $upload_path = $upload_dir . $new_filename;
                                    
                                    if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
                                        $file_path = 'uploads/gallery/' . $new_filename;
                                    } else {
                                        $error = 'Failed to move uploaded file. Please try again.';
                                    }
                                } else {
                                    $error = 'Invalid file type. Allowed types: ' . implode(', ', $allowed_extensions);
                                }
                            }
                        } else {
                            $error = handleUploadError($_FILES['file']['error']);
                        }
                    } else {
                        $error = 'Please select a file to upload.';
                    }
                    
                    if ($file_path) {
                        $stmt = $db->prepare("INSERT INTO gallery (title, description, file_path, file_type, category_id, is_featured, uploaded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $description, $file_path, $file_type, $category_id, $is_featured, $_SESSION['admin_id']]);
                        $success = 'Gallery item added successfully!';
                    }
                    break;
                
                case 'edit':
                    if (isset($_POST['id'])) {
                        $id = (int)$_POST['id'];
                        $title = trim($_POST['title'] ?? '');
                        $description = trim($_POST['description'] ?? '');
                        $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
                        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
                        
                        // Handle file upload
                        $file_path = $_POST['current_file'] ?? '';
                        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                            $upload_dir = '../uploads/gallery/';
                            if (!file_exists($upload_dir)) {
                                mkdir($upload_dir, 0777, true);
                            }
                            
                            $file_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                            $file_type = $_POST['file_type'] ?? 'image';
                            
                            if ($file_type === 'image') {
                                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                            } else {
                                $allowed_extensions = ['mp4', 'webm', 'ogg'];
                            }
                            
                            if (in_array($file_extension, $allowed_extensions)) {
                                $new_filename = uniqid() . '.' . $file_extension;
                                $upload_path = $upload_dir . $new_filename;
                                
                                if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
                                    // Delete old file if exists
                                    if ($file_path && file_exists($upload_dir . $file_path)) {
                                        unlink($upload_dir . $file_path);
                                    }
                                    $file_path = $new_filename;
                                }
                            }
                        }
                        
                        $stmt = $db->prepare("UPDATE gallery SET title = ?, description = ?, file_path = ?, category_id = ?, is_featured = ? WHERE id = ?");
                        $stmt->execute([$title, $description, $file_path, $category_id, $is_featured, $id]);
                        $success = 'Gallery item updated successfully!';
                    }
                    break;
                
                case 'delete':
                    if (isset($_POST['id'])) {
                        // Get file path before deleting
                        $stmt = $db->prepare("SELECT file_path FROM gallery WHERE id = ?");
                        $stmt->execute([$_POST['id']]);
                        $file_path = $stmt->fetchColumn();
                        
                        // Delete the record
                        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
                        $stmt->execute([$_POST['id']]);
                        
                        // Delete file if exists
                        if ($file_path && file_exists('../uploads/gallery/' . $file_path)) {
                            unlink('../uploads/gallery/' . $file_path);
                        }
                        
                        $success = 'Gallery item deleted successfully!';
                    }
                    break;
            }
        }
    }
    
    // Get all gallery items with category names
    $stmt = $db->query("SELECT g.*, c.name as category_name, u.username as uploaded_by_name 
                        FROM gallery g 
                        LEFT JOIN gallery_categories c ON g.category_id = c.id 
                        LEFT JOIN users u ON g.uploaded_by = u.id 
                        ORDER BY g.created_at DESC");
    $gallery_items = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log('Gallery management error: ' . $e->getMessage());
    $error = 'An error occurred while managing the gallery.';
}

// Set page title
$page_title = 'Gallery Management';
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
                <a href="gallery.php" class="flex items-center space-x-2 p-2 rounded active">
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
                <a href="students.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span>Students</span>
                </a>
                <a href="messages.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-envelope w-6"></i>
                    <span>Messages</span>
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
                    <h1 class="text-2xl font-bold text-gray-800">Gallery</h1>
                    <p class="text-gray-600">Manage school images and videos</p>
                </div>
                <button onclick="document.getElementById('addGalleryModal').classList.remove('hidden')" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md transition duration-300">
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
            
            <!-- Gallery Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($gallery_items as $item): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if ($item['file_type'] === 'image'): ?>
                        <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                        <video src="../<?php echo htmlspecialchars($item['file_path']); ?>" class="w-full h-48 object-cover" controls></video>
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($item['description']); ?></p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                            </span>
                            <div class="flex space-x-2">
                                <button onclick="editGalleryItem(<?php echo json_encode($item); ?>)" class="text-primary hover:text-primary-dark">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteGalleryItem(<?php echo $item['id']; ?>)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    
    <!-- Add Gallery Modal -->
    <div id="addGalleryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add Gallery Item</h3>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="add">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="file_type" id="file_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File</label>
                        <input type="file" name="file" id="file" accept="image/*,video/*" required class="mt-1 block w-full">
                        <p class="mt-1 text-sm text-gray-500" id="fileHelp">
                            Allowed formats: JPG, PNG, GIF (max 50MB), MP4, WebM, OGG (max 100MB)
                        </p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="is_featured" class="ml-2 block text-sm text-gray-700">
                            Feature this item
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('addGalleryModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
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
    
    <!-- Edit Gallery Modal -->
    <div id="editGalleryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Gallery Item</h3>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="current_file" id="edit_current_file">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="edit_title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="edit_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="file_type" id="edit_file_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">File</label>
                        <input type="file" name="file" id="edit_file" accept="image/*,video/*" class="mt-1 block w-full">
                        <p class="mt-1 text-sm text-gray-500">
                            Leave empty to keep current file. Allowed formats: JPG, PNG, GIF (max 50MB), MP4, WebM, OGG (max 100MB)
                        </p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_featured" id="edit_is_featured" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="edit_is_featured" class="ml-2 block text-sm text-gray-700">
                            Feature this item
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('editGalleryModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
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
    
    <script>
        // Update file input accept attribute based on selected type
        document.getElementById('file_type').addEventListener('change', function() {
            const fileInput = document.getElementById('file');
            const fileHelp = document.getElementById('fileHelp');
            
            if (this.value === 'image') {
                fileInput.accept = 'image/*';
                fileHelp.textContent = 'Allowed formats: JPG, PNG, GIF';
            } else {
                fileInput.accept = 'video/*';
                fileHelp.textContent = 'Allowed formats: MP4, WebM, OGG';
            }
        });
        
        // Edit gallery item
        function editGalleryItem(item) {
            document.getElementById('edit_id').value = item.id;
            document.getElementById('edit_title').value = item.title;
            document.getElementById('edit_description').value = item.description;
            document.getElementById('edit_category_id').value = item.category_id || '';
            document.getElementById('edit_file_type').value = item.file_type;
            document.getElementById('edit_current_file').value = item.file_path;
            document.getElementById('edit_is_featured').checked = item.is_featured == 1;
            
            document.getElementById('editGalleryModal').classList.remove('hidden');
        }
        
        // Delete gallery item
        function deleteGalleryItem(id) {
            if (confirm('Are you sure you want to delete this item?')) {
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
    </script>
</body>
</html> 