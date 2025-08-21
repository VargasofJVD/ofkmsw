<?php
/**
 * Header template for Krisah Montessori School Website
 * Includes HTML head, meta tags, CSS imports, and navigation
 */

// Start session for potential user authentication
session_start();

// Include database connection for potential settings retrieval
require_once __DIR__ . '/../config/database.php';

// Get site settings from database
$settings = [];
try {
    $db = getDbConnection();
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    // Fallback to default settings if database error
    $settings['school_name'] = 'Krisah Montessori School';
    error_log('Error loading settings: ' . $e->getMessage());
}

// Get current page for navigation highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        // Dynamic title based on page
        if (isset($page_title)) {
            echo htmlspecialchars($page_title) . ' | ' . htmlspecialchars($settings['school_name'] ?? 'Krisah Montessori School');
        } else {
            echo htmlspecialchars($settings['school_name'] ?? 'Krisah Montessori School');
        }
        ?>
    </title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description ?? 'Krisah Montessori School - Quality Montessori education in the Western Region of Ghana'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" href="/ofkmsw/assets/images/favicon.ico" type="image/x-icon">
    
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
                            light: '#4A90E2',
                            DEFAULT: '#2B6CB0',
                            dark: '#1E4B8F'
                        },
                        light: '#F5F7FA'
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
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
    <link rel="stylesheet" href="/ofkmsw/assets/css/style.css">
    
    <!-- Custom JavaScript -->
    <script src="/ofkmsw/assets/js/main.js"></script>
    <script src="/ofkmsw/assets/js/mobile-menu.js"></script>
</head>
<body class="font-sans text-gray-800 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <!-- Top Bar -->
            
            
            <!-- Main Navigation -->
            <nav class="py-4 flex flex-wrap justify-between items-center">
                <!-- Logo -->
                <a href="/ofkmsw/index.php" class="flex items-center space-x-3">
                    <img src="/ofkmsw/assets/images/logo.png" alt="Krisah Montessori School" class="h-12">
                    <div>
                        <h1 class="text-xl font-bold text-primary">Krisah Montessori</h1>
                        <p class="text-xs text-gray-600">Excellence in Education</p>
                    </div>
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-700 focus:outline-none p-2" aria-label="Toggle menu">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                
                <!-- Navigation Links -->
                <div id="nav-links" class="hidden md:flex w-full md:w-auto mt-4 md:mt-0 bg-white md:bg-transparent fixed md:relative inset-0 md:inset-auto top-[120px] md:top-auto shadow-lg md:shadow-none z-50 transition-all duration-300 ease-in-out">
                    <ul class="flex flex-col md:flex-row md:space-x-6 space-y-2 md:space-y-0 p-4 md:p-0 w-full md:w-auto">
                        <li class="md:hidden">
                            <a href="/ofkmsw/pages/contact.php" class="block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 text-center">Contact Us</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/index.php" class="block <?php echo $current_page == 'index.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">Home</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/pages/about.php" class="block <?php echo $current_page == 'about.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">About Us</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/pages/academics.php" class="block <?php echo $current_page == 'academics.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">Academics</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/pages/news-events.php" class="block <?php echo $current_page == 'news-events.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">News & Events</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/pages/testimonials.php" class="block <?php echo $current_page == 'testimonials.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">Testimonials</a>
                        </li>
                        <li>
                            <a href="/ofkmsw/pages/contact.php" class="block <?php echo $current_page == 'contact.php' ? 'text-secondary font-semibold' : 'text-gray-700 hover:text-primary'; ?>">Contact</a>
                        </li>
                    </ul>
                </div>
                
                <!-- CTA Button (Desktop Only) -->
                <a href="/ofkmsw/pages/contact.php" class="hidden md:block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ml-4">Contact Us</a>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="flex-grow">