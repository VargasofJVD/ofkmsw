<?php
/**
 * Admin Dashboard
 * 
 * Main dashboard for administrators to manage the website content.
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

// Get dashboard statistics
try {
    $db = getDbConnection();
    
    // Count students
    $stmt = $db->query("SELECT COUNT(*) as student_count FROM students");
    $student_count = $stmt->fetch()['student_count'] ?? 0;
    
    // Count news/events
    $stmt = $db->query("SELECT COUNT(*) as news_count FROM news_events");
    $news_count = $stmt->fetch()['news_count'] ?? 0;
    
    // Count gallery items
    $stmt = $db->query("SELECT COUNT(*) as gallery_count FROM gallery");
    $gallery_count = $stmt->fetch()['gallery_count'] ?? 0;
    
    // Count unprocessed admission forms
    $stmt = $db->query("SELECT COUNT(*) as form_count FROM admission_forms WHERE is_processed = 0");
    $form_count = $stmt->fetch()['form_count'] ?? 0;
    
    // Count unread contact messages
    $stmt = $db->query("SELECT COUNT(*) as message_count FROM contact_messages WHERE is_read = 0");
    $message_count = $stmt->fetch()['message_count'] ?? 0;
    
    // Get recent admission forms
    $stmt = $db->query("SELECT * FROM admission_forms ORDER BY created_at DESC LIMIT 5");
    $recent_forms = $stmt->fetchAll();
    
    // Get recent news/events
    $stmt = $db->query("SELECT * FROM news_events ORDER BY created_at DESC LIMIT 5");
    $recent_news = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log('Dashboard error: ' . $e->getMessage());
    $error = 'An error occurred while loading dashboard data.';
}

// Set page title
$page_title = 'Admin Dashboard';
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
                <a href="dashboard.php" class="flex items-center space-x-2 p-2 rounded active">
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
                    <?php if (isset($form_count) && $form_count > 0): ?>
                        <span class="bg-secondary text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $form_count; ?></span>
                    <?php endif; ?>
                </a>
                <a href="students.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-user-graduate w-6"></i>
                    <span>Students</span>
                </a>
                <a href="messages.php" class="flex items-center space-x-2 p-2 rounded">
                    <i class="fas fa-envelope w-6"></i>
                    <span>Messages</span>
                    <?php if (isset($message_count) && $message_count > 0): ?>
                        <span class="bg-secondary text-white text-xs rounded-full px-2 py-1 ml-auto"><?php echo $message_count; ?></span>
                    <?php endif; ?>
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
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600">Welcome back, <?php echo htmlspecialchars($admin_name); ?>!</p>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Total Students</p>
                            <h3 class="text-2xl font-bold"><?php echo number_format($student_count ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <i class="fas fa-newspaper text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">News & Events</p>
                            <h3 class="text-2xl font-bold"><?php echo number_format($news_count ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <i class="fas fa-images text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Gallery Items</p>
                            <h3 class="text-2xl font-bold"><?php echo number_format($gallery_count ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Pending Applications</p>
                            <h3 class="text-2xl font-bold"><?php echo number_format($form_count ?? 0); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Applications -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Recent Applications</h2>
                        <a href="admissions.php" class="text-primary hover:text-primary-dark text-sm">View All</a>
                    </div>
                    
                    <?php if (isset($recent_forms) && count($recent_forms) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="pb-2">Name</th>
                                        <th class="pb-2">Class</th>
                                        <th class="pb-2">Date</th>
                                        <th class="pb-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php foreach ($recent_forms as $form): ?>
                                        <tr>
                                            <td class="py-3 text-sm">
                                                <?php echo htmlspecialchars($form['child_first_name'] . ' ' . $form['child_last_name']); ?>
                                            </td>
                                            <td class="py-3 text-sm">
                                                <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($form['applying_for_class']))); ?>
                                            </td>
                                            <td class="py-3 text-sm">
                                                <?php echo date('M d, Y', strtotime($form['created_at'])); ?>
                                            </td>
                                            <td class="py-3 text-sm">
                                                <a href="admission-detail.php?id=<?php echo $form['id']; ?>" class="text-primary hover:text-primary-dark">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No recent applications</p>
                    <?php endif; ?>
                </div>
                
                <!-- Recent News & Events -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Recent News & Events</h2>
                        <a href="news.php" class="text-primary hover:text-primary-dark text-sm">View All</a>
                    </div>
                    
                    <?php if (isset($recent_news) && count($recent_news) > 0): ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_news as $news): ?>
                                <div class="flex items-start">
                                    <?php if (!empty($news['image'])): ?>
                                        <img src="../uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="w-16 h-16 object-cover rounded mr-4">
                                    <?php else: ?>
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center mr-4">
                                            <i class="fas fa-newspaper text-gray-400 text-2xl"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h3 class="font-semibold"><?php echo htmlspecialchars($news['title']); ?></h3>
                                        <p class="text-sm text-gray-500"><?php echo date('M d, Y', strtotime($news['created_at'])); ?></p>
                                        <a href="news-edit.php?id=<?php echo $news['id']; ?>" class="text-primary hover:text-primary-dark text-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 text-center py-4">No recent news or events</p>
                    <?php endif; ?>
                </div>
            </div>
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
    
    <!-- JavaScript -->
    <script>
        // Toggle dropdown menu
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButtons = document.querySelectorAll('.dropdown-button');
            
            dropdownButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const dropdown = this.nextElementSibling;
                    dropdown.classList.toggle('hidden');
                });
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                dropdownButtons.forEach(button => {
                    const dropdown = button.nextElementSibling;
                    if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>