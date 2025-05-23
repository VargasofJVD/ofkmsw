<?php
/**
 * News & Events Detail Page for Krisah Montessori School
 */

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Initialize variables
$item = null;
$error_message = null;

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    try {
        $db = getDbConnection();
        
        // Get the news/event item
        $stmt = $db->prepare("SELECT * FROM news_events WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        
        if (!$item) {
            $error_message = "The requested item could not be found.";
        } else {
            // Set page title and description for SEO
            $page_title = $item['title'];
            $page_description = substr(strip_tags($item['content']), 0, 160);
            
            // Get related items (same category: news or event)
            $related_stmt = $db->prepare("SELECT * FROM news_events WHERE id != ? AND is_event = ? ORDER BY created_at DESC LIMIT 3");
            $related_stmt->execute([$id, $item['is_event']]);
            $related_items = $related_stmt->fetchAll();
        }
    } catch (Exception $e) {
        $error_message = "An error occurred while retrieving the item.";
        error_log("News detail page error: " . $e->getMessage());
    }
} else {
    $error_message = "Invalid request. Please select a valid news or event item.";
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">
            <?php echo isset($item) ? htmlspecialchars($item['title']) : 'News & Events'; ?>
        </h1>
        <p class="text-xl text-white">
            <?php echo isset($item) && $item['is_event'] ? 'Event Details' : 'News Article'; ?>
        </p>
    </div>
</section>

<div class="container mx-auto px-4 py-16">
    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
            <div class="mt-4">
                <a href="news-events.php" class="text-primary hover:underline">← Back to News & Events</a>
            </div>
        </div>
    <?php elseif ($item): ?>
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Main Content -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (!empty($item['image'])): ?>
                    <img src="../assets/uploads/news/<?php echo htmlspecialchars($item['image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>" 
                         class="w-full h-96 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-8">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <span class="text-gray-600">
                                    <?php if ($item['is_event']): ?>
                                        Event Date: <?php echo date('F j, Y', strtotime($item['event_date'])); ?>
                                    <?php else: ?>
                                        Published: <?php echo date('F j, Y', strtotime($item['created_at'])); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div>
                                <?php if ($item['is_event']): ?>
                                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm">Event</span>
                                <?php else: ?>
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm">News</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="prose max-w-none">
                            <?php echo $item['content']; ?>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <a href="news-events.php" class="text-primary hover:underline">← Back to News & Events</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="lg:w-1/3">
                <?php if (!empty($related_items)): ?>
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h3 class="text-xl font-bold text-primary mb-4">
                        Related <?php echo $item['is_event'] ? 'Events' : 'News'; ?>
                    </h3>
                    
                    <div class="space-y-6">
                        <?php foreach ($related_items as $related): ?>
                        <div class="flex items-start">
                            <?php if (!empty($related['image'])): ?>
                            <img src="../assets/uploads/news/<?php echo htmlspecialchars($related['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($related['title']); ?>" 
                                 class="w-20 h-20 object-cover rounded mr-4">
                            <?php else: ?>
                            <div class="w-20 h-20 bg-gray-200 rounded mr-4 flex items-center justify-center">
                                <span class="text-gray-500 text-xs">No Image</span>
                            </div>
                            <?php endif; ?>
                            
                            <div>
                                <h4 class="font-bold text-primary">
                                    <a href="news-detail.php?id=<?php echo $related['id']; ?>" class="hover:underline">
                                        <?php echo htmlspecialchars($related['title']); ?>
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600">
                                    <?php 
                                    if ($related['is_event']) {
                                        echo date('F j, Y', strtotime($related['event_date'])); 
                                    } else {
                                        echo date('F j, Y', strtotime($related['created_at']));
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Call to Action -->
                <div class="bg-light rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-primary mb-4">Stay Connected</h3>
                    <p class="text-gray-700 mb-4">Subscribe to our newsletter to receive updates about school events and news.</p>
                    <a href="contact.php#newsletter" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-2 px-4 rounded inline-block transition duration-300">Subscribe Now</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Include footer
include_once '../includes/footer.php';
?>