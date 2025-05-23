<?php
/**
 * News & Events Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "News & Events";
$page_description = "Stay updated with the latest news, announcements, and upcoming events at Krisah Montessori School.";

// Include header
include_once '../includes/header.php';

// Get database connection
require_once '../config/database.php';

// Fetch news and events from database
try {
    $db = getDbConnection();
    
    // Get featured news/events for the top section
    $featured_stmt = $db->prepare("SELECT * FROM news_events WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 3");
    $featured_stmt->execute();
    $featured_items = $featured_stmt->fetchAll();
    
    // Get all news items (pagination can be added later)
    $news_stmt = $db->prepare("SELECT * FROM news_events WHERE is_event = 0 ORDER BY created_at DESC LIMIT 10");
    $news_stmt->execute();
    $news_items = $news_stmt->fetchAll();
    
    // Get upcoming events
    $current_date = date('Y-m-d');
    $events_stmt = $db->prepare("SELECT * FROM news_events WHERE is_event = 1 AND event_date >= ? ORDER BY event_date ASC LIMIT 10");
    $events_stmt->execute([$current_date]);
    $upcoming_events = $events_stmt->fetchAll();
    
    // Get past events
    $past_events_stmt = $db->prepare("SELECT * FROM news_events WHERE is_event = 1 AND event_date < ? ORDER BY event_date DESC LIMIT 5");
    $past_events_stmt->execute([$current_date]);
    $past_events = $past_events_stmt->fetchAll();
    
} catch (Exception $e) {
    // Handle database error
    $error_message = "Unable to retrieve news and events at this time.";
    error_log("News & Events page error: " . $e->getMessage());
}
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">News & Events</h1>
        <p class="text-xl text-white">Stay updated with the latest happenings at Krisah Montessori School</p>
    </div>
</section>

<?php if (isset($error_message)): ?>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    </div>
<?php else: ?>

    <!-- Featured News/Events Section -->
    <?php if (!empty($featured_items)): ?>
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary mb-4">Featured Updates</h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">Highlights from our school community</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($featured_items as $item): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (!empty($item['image'])): ?>
                    <img src="../assets/uploads/news/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                    <img src="../assets/images/news-placeholder.jpg" alt="News Placeholder" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">
                                <?php echo date('F j, Y', strtotime($item['created_at'])); ?>
                            </span>
                            <?php if ($item['is_event']): ?>
                            <span class="bg-secondary text-white text-xs px-2 py-1 rounded">Event</span>
                            <?php else: ?>
                            <span class="bg-primary text-white text-xs px-2 py-1 rounded">News</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-xl font-bold text-primary mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr(strip_tags($item['content']), 0, 150); ?>...
                        </p>
                        <a href="news-detail.php?id=<?php echo $item['id']; ?>" class="text-secondary hover:text-secondary-dark font-bold">Read More →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Latest News Section -->
    <section class="py-16 bg-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary mb-4">Latest News</h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">Recent updates and announcements</p>
            </div>
            
            <?php if (empty($news_items)): ?>
            <div class="text-center text-gray-600">
                <p>No news articles available at this time. Please check back later.</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($news_items as $news): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (!empty($news['image'])): ?>
                    <img src="../assets/uploads/news/<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>" class="w-full h-48 object-cover">
                    <?php else: ?>
                    <img src="../assets/images/news-placeholder.jpg" alt="News Placeholder" class="w-full h-48 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">
                                <?php echo date('F j, Y', strtotime($news['created_at'])); ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-primary mb-2"><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr(strip_tags($news['content']), 0, 120); ?>...
                        </p>
                        <a href="news-detail.php?id=<?php echo $news['id']; ?>" class="text-secondary hover:text-secondary-dark font-bold">Read More →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary mb-4">Upcoming Events</h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">Mark your calendars for these important dates</p>
            </div>
            
            <?php if (empty($upcoming_events)): ?>
            <div class="text-center text-gray-600">
                <p>No upcoming events scheduled at this time. Please check back later.</p>
            </div>
            <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($upcoming_events as $event): ?>
                <div class="flex bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-secondary text-white p-4 flex flex-col items-center justify-center min-w-[100px]">
                        <span class="text-2xl font-bold"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                        <span class="text-sm"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                        <span class="text-sm"><?php echo date('Y', strtotime($event['event_date'])); ?></span>
                    </div>
                    <div class="p-6 flex-1">
                        <h3 class="text-xl font-bold text-primary mb-2"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="text-gray-700 mb-4">
                            <?php echo substr(strip_tags($event['content']), 0, 120); ?>...
                        </p>
                        <a href="news-detail.php?id=<?php echo $event['id']; ?>" class="text-secondary hover:text-secondary-dark font-bold">Event Details →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Past Events Section -->
    <?php if (!empty($past_events)): ?>
    <section class="py-16 bg-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-primary mb-4">Past Events</h2>
                <p class="text-xl text-gray-700 max-w-3xl mx-auto">Highlights from our recent activities</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($past_events as $past_event): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <?php if (!empty($past_event['image'])): ?>
                    <img src="../assets/uploads/news/<?php echo htmlspecialchars($past_event['image']); ?>" alt="<?php echo htmlspecialchars($past_event['title']); ?>" class="w-full h-40 object-cover">
                    <?php else: ?>
                    <img src="../assets/images/news-placeholder.jpg" alt="Event Placeholder" class="w-full h-40 object-cover">
                    <?php endif; ?>
                    
                    <div class="p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">
                                <?php echo date('F j, Y', strtotime($past_event['event_date'])); ?>
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-primary mb-2"><?php echo htmlspecialchars($past_event['title']); ?></h3>
                        <a href="news-detail.php?id=<?php echo $past_event['id']; ?>" class="text-secondary hover:text-secondary-dark text-sm font-bold">View Recap →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php endif; ?>

<?php
// Include footer
include_once '../includes/footer.php';
?>