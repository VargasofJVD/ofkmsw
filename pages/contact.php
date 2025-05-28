<?php
/**
 * Contact Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "Contact Us";
$page_description = "Get in touch with Krisah Montessori School. Find our location, contact information, and send us a message.";

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Initialize variables
$success_message = '';
$error_message = '';
$form_submitted = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDbConnection();
        
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            throw new Exception('Please fill in all required fields.');
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Please enter a valid email address.');
        }
        
        // Insert into messages table
        $stmt = $db->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        
        // Send notification email to admin (optional)
        // mail($admin_email, "New Contact Form Submission: $subject", $message, "From: $email");
        
        $success_message = 'Thank you for your message! We will get back to you soon.';
        $form_submitted = true;
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Contact Us</h1>
        <p class="text-xl text-white">We'd love to hear from you</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Get In Touch</h2>
                <p class="text-lg text-gray-600 mb-8">Have questions about our programs, admissions, or anything else? Feel free to reach out to us using any of the methods below.</p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full p-3 text-white mr-4">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Our Location</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($settings['school_address'] ?? 'Takoradi, Western Region, Ghana'); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full p-3 text-white mr-4">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Phone Number</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($settings['school_phone'] ?? '+233 XX XXX XXXX'); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full p-3 text-white mr-4">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Email Address</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($settings['school_email'] ?? 'info@krisahmontessori.com'); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full p-3 text-white mr-4">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 mb-1">Office Hours</h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($settings['office_hours'] ?? 'Monday - Friday: 8:00 AM - 4:00 PM'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="font-bold text-gray-800 mb-3">Connect With Us</h3>
                    <div class="flex space-x-4">
                        <a href="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>" class="bg-primary hover:bg-primary-dark w-10 h-10 rounded-full flex items-center justify-center text-white transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>" class="bg-primary hover:bg-primary-dark w-10 h-10 rounded-full flex items-center justify-center text-white transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($settings['twitter_url'] ?? '#'); ?>" class="bg-primary hover:bg-primary-dark w-10 h-10 rounded-full flex items-center justify-center text-white transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <div class="bg-white shadow-md rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Send Us a Message</h2>
                    
                    <?php if ($error_message): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($form_submitted): ?>
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Message Sent Successfully!</h3>
                            <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($success_message); ?></p>
                            <a href="contact.php" class="inline-block bg-primary hover:bg-primary-dark text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                                Send Another Message
                            </a>
                        </div>
                    <?php else: ?>
                        <form method="post" action="">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Your Name *</label>
                                <input type="text" id="name" name="name" required
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Your Email *</label>
                                <input type="email" id="email" name="email" required
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-4">
                                <label for="subject" class="block text-gray-700 font-medium mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject" required
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                       value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Your Message *</label>
                                <textarea id="message" name="message" required rows="6"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Find Us</h2>
        <div class="h-96 rounded-lg overflow-hidden shadow-md">
            <!-- Replace with your actual Google Maps embed code -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3975.5511660549933!2d-1.7723848857415297!3d4.8952968963888!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfe779c3d6f601ed%3A0x3c3fcc244c7e9eba!2sTakoradi%2C%20Ghana!5e0!3m2!1sen!2sus!4v1623251234567!5m2!1sen!2sus" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?>