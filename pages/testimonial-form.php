<?php
/**
 * Testimonial Submission Form
 */

// Set page title and description for SEO
$page_title = "Submit Testimonial";
$page_description = "Share your experience at Krisah Montessori School.";

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Initialize variables
$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDbConnection();
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $rating = (int)($_POST['rating'] ?? 5);
        
        if (empty($name) || empty($email) || empty($role) || empty($content)) {
            throw new Exception('All fields are required.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        if ($rating < 1 || $rating > 5) {
            throw new Exception('Rating must be between 1 and 5.');
        }
        
        // Handle image upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/testimonials/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception('Invalid image format. Allowed formats: ' . implode(', ', $allowed_extensions));
            }
            
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                throw new Exception('Failed to upload image. Please try again.');
            }
            
            $image_path = $new_filename;
        }
        
        // Insert into pending_testimonials
        $stmt = $db->prepare("INSERT INTO pending_testimonials (name, email, role, content, rating, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $role, $content, $rating, $image_path]);
        
        $success_message = 'Thank you for sharing your testimonial! It will be reviewed by our team before being published.';
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Share Your Experience</h1>
        <p class="text-xl text-white">Help others learn about your Krisah Montessori journey</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-8" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
                    <div class="mt-4 text-center">
                        <a href="testimonials.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                            Return to Testimonials
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
                    <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if (!$success_message): ?>
            <form method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-8">
                <div class="mb-6">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Your Name *</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>
                
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Your Email *</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                           value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="mb-6">
                    <label for="role" class="block text-gray-700 font-medium mb-2">Your Role *</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select your role</option>
                        <option value="Parent" <?php echo ($_POST['role'] ?? '') === 'Parent' ? 'selected' : ''; ?>>Parent</option>
                        <option value="Student" <?php echo ($_POST['role'] ?? '') === 'Student' ? 'selected' : ''; ?>>Student</option>
                        <option value="Alumni" <?php echo ($_POST['role'] ?? '') === 'Alumni' ? 'selected' : ''; ?>>Alumni</option>
                        <option value="Staff" <?php echo ($_POST['role'] ?? '') === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="rating" class="block text-gray-700 font-medium mb-2">Rating *</label>
                    <div class="flex items-center space-x-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <input type="radio" id="rating<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>"
                                   <?php echo ($_POST['rating'] ?? 5) == $i ? 'checked' : ''; ?>
                                   class="hidden peer">
                            <label for="rating<?php echo $i; ?>" 
                                   class="cursor-pointer text-2xl peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-400 transition-colors">
                                ★
                            </label>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="content" class="block text-gray-700 font-medium mb-2">Your Testimonial *</label>
                    <textarea id="content" name="content" required rows="6"
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                </div>
                
                <div class="mb-6">
                    <label for="image" class="block text-gray-700 font-medium mb-2">Your Photo (Optional)</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="mt-1 text-sm text-gray-500">Allowed formats: JPG, PNG, GIF (max 5MB)</p>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                        Submit Testimonial
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?> 