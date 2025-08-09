<?php
/**
 * Admin Settings Page
 * 
 * Manage website settings and configuration
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

// Handle form submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDbConnection();
        
        // Update settings
        $settings_to_update = [
            'school_name' => $_POST['school_name'] ?? '',
            'school_address' => $_POST['school_address'] ?? '',
            'school_phone' => $_POST['school_phone'] ?? '',
            'school_email' => $_POST['school_email'] ?? '',
            'about_us' => $_POST['about_us'] ?? '',
            'mission_statement' => $_POST['mission_statement'] ?? '',
            'vision_statement' => $_POST['vision_statement'] ?? '',
            'facebook_url' => $_POST['facebook_url'] ?? '',
            'instagram_url' => $_POST['instagram_url'] ?? '',
            'twitter_url' => $_POST['twitter_url'] ?? '',
            'office_hours' => $_POST['office_hours'] ?? ''
        ];
        
        foreach ($settings_to_update as $key => $value) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, updated_by) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE setting_value = ?, updated_by = ?");
            $stmt->execute([$key, $value, $_SESSION['admin_id'], $value, $_SESSION['admin_id']]);
        }
        
        $success_message = 'Settings updated successfully!';
        
    } catch (PDOException $e) {
        $error_message = 'Error updating settings: ' . $e->getMessage();
    }
}

// Get current settings
$settings = [];
try {
    $db = getDbConnection();
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    $error_message = 'Error loading settings: ' . $e->getMessage();
}

// Set page title
$page_title = 'Settings';
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
                <a href="testimonials.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-quote-right w-6"></i>
                    <span>Testimonials</span>
                </a>
                <a href="settings.php" class="flex items-center space-x-2 p-2 rounded active">
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
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Website Settings</h1>
                <p class="text-gray-600">Manage website configuration and information</p>
            </div>
            
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" class="space-y-6">
                <!-- School Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">School Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                            <input type="text" name="school_name" value="<?php echo htmlspecialchars($settings['school_name'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">School Address</label>
                            <input type="text" name="school_address" value="<?php echo htmlspecialchars($settings['school_address'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="school_phone" value="<?php echo htmlspecialchars($settings['school_phone'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="school_email" value="<?php echo htmlspecialchars($settings['school_email'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Office Hours</label>
                            <input type="text" name="office_hours" value="<?php echo htmlspecialchars($settings['office_hours'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>
                
                <!-- About Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">About Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">About Us</label>
                            <textarea name="about_us" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($settings['about_us'] ?? ''); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mission Statement</label>
                            <textarea name="mission_statement" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($settings['mission_statement'] ?? ''); ?></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vision Statement</label>
                            <textarea name="vision_statement" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($settings['vision_statement'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Social Media Links</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                            <input type="url" name="facebook_url" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                            <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                            <input type="url" name="twitter_url" value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-md transition duration-300">
                        <i class="fas fa-save mr-2"></i> Save Settings
                    </button>
                </div>
            </form>
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4">
        <div class="container mx-auto px-6">
            <p class="text-center text-gray-600 text-sm">
                &copy; <?php echo date('Y'); ?> Krisah Montessori School. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html> 