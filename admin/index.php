<?php
/**
 * Admin Login Page
 * 
 * This is the entry point for the admin dashboard. It handles authentication
 * and redirects to the dashboard upon successful login.
 */

// Start session
session_start();

// Include database connection
require_once '../config/database.php';

// Check if user is already logged in
if (isset($_SESSION['admin_id'])) {
    // Redirect to dashboard
    header('Location: dashboard.php');
    exit;
}

// Initialize variables
$username = '';
$error = '';

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Add debug logging
    error_log("Login attempt - Username: " . $username);
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            // Get database connection
            $db = getDbConnection();
            error_log("Database connection successful");
            
            // Prepare statement
            $stmt = $db->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($user = $stmt->fetch()) {
                error_log("User found in database");
                // Verify password
                if (password_verify($password, $user['password'])) {
                    error_log("Password verification successful");
                    // Set session variables
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_name'] = $user['full_name'];
                    $_SESSION['admin_role'] = $user['role'];
                    
                    // Redirect to dashboard
                    header('Location: dashboard.php');
                    exit;
                } else {
                    error_log("Password verification failed");
                    $error = 'Invalid username or password';
                }
            } else {
                error_log("No user found with username: " . $username);
                $error = 'Invalid username or password';
            }
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            $error = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Krisah Montessori School</title>
    
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
<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <a href="../index.php">
                <img src="../assets/images/logo.png" alt="Krisah Montessori School" class="h-16 mx-auto mb-2">
            </a>
            <h1 class="text-2xl font-bold text-primary">Admin Login</h1>
            <p class="text-gray-600">Enter your credentials to access the dashboard</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6">
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter your username" required>
                </div>
            </div>
            
            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary" placeholder="Enter your password" required>
                </div>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-md transition duration-300">Login</button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <a href="../index.php" class="text-primary hover:text-primary-dark">
                <i class="fas fa-arrow-left mr-1"></i> Back to Website
            </a>
        </div>
    </div>
    
    <script>
        // Focus on username field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>