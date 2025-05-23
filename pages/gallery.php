<?php
/**
 * Gallery Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "Gallery";
$page_description = "Browse photos and videos showcasing life at Krisah Montessori School - our classrooms, events, and student activities.";

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Initialize variables
$categories = [];
$gallery_items = [];
$active_category = 'all';
$error_message = null;

try {
    $db = getDbConnection();
    
    // Get all categories
    $cat_stmt = $db->query("SELECT * FROM gallery_categories ORDER BY name ASC");
    $categories = $cat_stmt->fetchAll();
    
    // Get selected category
    $selected_category = isset($_GET['category']) ? (int)$_GET['category'] : null;
    
    // Get gallery items
    if ($selected_category) {
        $stmt = $db->prepare("SELECT g.*, c.name as category_name 
                             FROM gallery g 
                             LEFT JOIN gallery_categories c ON g.category_id = c.id 
                             WHERE g.category_id = ? 
                             ORDER BY g.is_featured DESC, g.created_at DESC");
        $stmt->execute([$selected_category]);
    } else {
        $stmt = $db->query("SELECT g.*, c.name as category_name 
                           FROM gallery g 
                           LEFT JOIN gallery_categories c ON g.category_id = c.id 
                           ORDER BY g.is_featured DESC, g.created_at DESC");
    }
    $gallery_items = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log('Gallery page error: ' . $e->getMessage());
    $error = 'An error occurred while loading the gallery.';
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Gallery</h1>
        <p class="text-xl text-white">Visual highlights from our school community</p>
    </div>
</section>

<section class="py-16">
    <div class="container mx-auto px-4">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php else: ?>
            <!-- Category Filter -->
            <div class="mb-12">
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="gallery.php" class="<?php echo !$selected_category ? 'bg-primary text-white' : 'bg-gray-200 text-gray-800'; ?> px-4 py-2 rounded-full hover:bg-primary hover:text-white transition duration-300">
                        All Categories
                    </a>
                    
                    <?php foreach ($categories as $category): ?>
                    <a href="gallery.php?category=<?php echo $category['id']; ?>" 
                       class="<?php echo $selected_category == $category['id'] ? 'bg-primary text-white' : 'bg-gray-200 text-gray-800'; ?> px-4 py-2 rounded-full hover:bg-primary hover:text-white transition duration-300">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if (empty($gallery_items)): ?>
                <div class="text-center text-gray-600 py-12">
                    <p>No gallery items available in this category. Please check back later or select another category.</p>
                </div>
            <?php else: ?>
                <!-- Gallery Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($gallery_items as $item): ?>
                        <div class="gallery-item overflow-hidden rounded-lg shadow-md bg-white">
                            <?php if ($item['is_featured']): ?>
                                <span class="featured-badge">Featured</span>
                            <?php endif; ?>
                            
                            <?php if ($item['file_type'] === 'image'): ?>
                                <a href="../<?php echo htmlspecialchars($item['file_path']); ?>" 
                                   class="glightbox" 
                                   data-gallery="gallery-images"
                                   data-description="<?php echo htmlspecialchars($item['description'] ?: $item['title']); ?>">
                                    <img src="../<?php echo htmlspecialchars($item['file_path']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['title']); ?>" 
                                         class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">
                                </a>
                            <?php else: ?>
                                <a href="../<?php echo htmlspecialchars($item['file_path']); ?>" 
                                   class="glightbox" 
                                   data-gallery="gallery-videos"
                                   data-type="video"
                                   data-description="<?php echo htmlspecialchars($item['description'] ?: $item['title']); ?>">
                                    <div class="relative w-full h-48 bg-gray-900">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-16 h-16 rounded-full bg-primary bg-opacity-75 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 text-center">
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endif; ?>
                            
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-primary mb-1"><?php echo htmlspecialchars($item['title']); ?></h3>
                                <?php if (!empty($item['category_name'])): ?>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($item['category_name']); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($item['description'])): ?>
                                <p class="text-sm text-gray-600"><?php echo htmlspecialchars($item['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Add GLightbox for image/video viewing -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lightbox = GLightbox({
            touchNavigation: true,
            loop: true,
            autoplayVideos: false
        });
    });
</script>

<?php
// Include footer
include_once '../includes/footer.php';
?>