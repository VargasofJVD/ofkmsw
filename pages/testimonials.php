<?php
/**
 * Testimonials Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "Testimonials";
$page_description = "Read what parents, students, and staff say about Krisah Montessori School.";

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Initialize variables
$testimonials = [];
$error_message = '';

try {
    $db = getDbConnection();
    
    // Get all approved testimonials
    $stmt = $db->prepare("SELECT * FROM testimonials ORDER BY created_at DESC");
    $stmt->execute();
    $testimonials = $stmt->fetchAll();
} catch (Exception $e) {
    $error_message = "An error occurred while retrieving testimonials.";
    error_log("Testimonials page error: " . $e->getMessage());
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Testimonials</h1>
        <p class="text-xl text-white">What our community says about Krisah Montessori School</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>
        
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Our Community Speaks</h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">Read what parents, students, and staff have to say about their experience at Krisah Montessori School.</p>
            <div class="mt-8">
                <a href="/ofkms/pages/testimonial-form.php" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-8 rounded-lg transition duration-300">
                    Share Your Experience
                </a>
            </div>
        </div>
        
        <?php if (empty($testimonials)): ?>
            <div class="text-center py-8">
                <p class="text-lg text-gray-600">No testimonials available yet. Be the first to share your experience!</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col h-full">
                        <div class="flex-grow">
                            <div class="flex items-center mb-4">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="text-yellow-400 text-xl">
                                        <?php echo ($i <= $testimonial['rating']) ? '★' : '☆'; ?>
                                    </span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-700 mb-6 italic">"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                        </div>
                        <div class="flex items-center mt-4">
                            <?php if (!empty($testimonial['image'])): ?>
                                <img src="../<?php echo htmlspecialchars($testimonial['image']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="w-12 h-12 rounded-full object-cover mr-4">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center mr-4">
                                    <span class="text-xl font-bold"><?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="font-bold text-gray-800"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($testimonial['role']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?>