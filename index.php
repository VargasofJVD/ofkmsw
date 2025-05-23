<?php
/**
 * Homepage for Krisah Montessori School Website
 */

// Include header
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Krisah Montessori School</h1>
                <p class="text-xl mb-6">Nurturing young minds through the Montessori approach in the Western Region of Ghana.</p>
                <div class="flex flex-wrap gap-4">
                    <a href="pages/admissions.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Apply Now</a>
                    <a href="pages/about.php" class="bg-white hover:bg-gray-100 text-primary font-bold py-3 px-6 rounded-lg transition duration-300">Learn More</a>
                </div>
            </div>
            
            <div class="md:w-1/2">
                <img src="assets/images/hero-image.png" alt="Krisah Montessori Students" class="rounded-lg shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Welcome Message -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">A Child-Centered Approach to Education</h2>
            <div class="w-24 h-1 bg-secondary mx-auto"></div>
        </div>
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-1/3 bg-white p-6 rounded-lg shadow-md">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-child text-2xl text-primary"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-center mb-3">Child-Led Learning</h3>
                <p class="text-gray-700">Our Montessori approach allows children to explore and learn at their own pace, fostering independence and a love for learning.</p>
            </div>
            <div class="md:w-1/3 bg-white p-6 rounded-lg shadow-md">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-book-reader text-2xl text-primary"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-center mb-3">Holistic Development</h3>
                <p class="text-gray-700">We focus on the intellectual, physical, social, and emotional development of each child in our carefully prepared environment.</p>
            </div>
            <div class="md:w-1/3 bg-white p-6 rounded-lg shadow-md">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-users text-2xl text-primary"></i>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-center mb-3">Community Values</h3>
                <p class="text-gray-700">We build a strong community of learners, parents, and educators working together to support each child's unique journey.</p>
            </div>
        </div>
    </div>
</section>

<!-- Programs Overview -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Our Educational Programs</h2>
            <div class="w-24 h-1 bg-secondary mx-auto mb-6"></div>
            <p class="text-lg text-gray-700 max-w-3xl mx-auto">Discover our comprehensive educational programs designed to nurture your child's natural curiosity and love for learning.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:transform hover:scale-105">
                <img src="assets/images/preschool.jpg" alt="Preschool Program" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-primary mb-2">Preschool (Ages 2-5)</h3>
                    <p class="text-gray-700 mb-4">Our preschool program creates a foundation for lifelong learning through play-based activities and Montessori materials.</p>
                    <a href="pages/academics.php#preschool" class="text-secondary hover:text-secondary-dark font-semibold">Learn more →</a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:transform hover:scale-105">
                <img src="assets/images/lower-primary.jpg" alt="Lower Primary Program" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-primary mb-2">Lower Primary (Ages 6-9)</h3>
                    <p class="text-gray-700 mb-4">Children develop core academic skills while exploring subjects through hands-on learning experiences.</p>
                    <a href="pages/academics.php#lower-primary" class="text-secondary hover:text-secondary-dark font-semibold">Learn more →</a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:transform hover:scale-105">
                <img src="assets/images/upper-primary.jpg" alt="Upper Primary Program" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-primary mb-2">Upper Primary (Ages 9-12)</h3>
                    <p class="text-gray-700 mb-4">Students engage in complex projects and collaborative work while mastering advanced academic concepts.</p>
                    <a href="pages/academics.php#upper-primary" class="text-secondary hover:text-secondary-dark font-semibold">Learn more →</a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="pages/academics.php" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300">View All Programs</a>
        </div>
    </div>
</section>

<!-- News & Events Section -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Latest News & Events</h2>
            <div class="w-24 h-1 bg-secondary mx-auto mb-6"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Include database connection
            require_once 'config/database.php';
            
            try {
                $db = getDbConnection();
                $stmt = $db->query("SELECT * FROM news_events ORDER BY created_at DESC LIMIT 3");
                
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo '<div class="bg-white rounded-lg overflow-hidden shadow-md">';
                        if (!empty($row['image'])) {
                            echo '<img src="uploads/news/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['title']) . '" class="w-full h-48 object-cover">';
                        }
                        echo '<div class="p-6">';
                        echo '<p class="text-sm text-gray-500 mb-2">' . date('F j, Y', strtotime($row['created_at'])) . '</p>';
                        echo '<h3 class="text-xl font-bold text-primary mb-2">' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p class="text-gray-700 mb-4">' . substr(htmlspecialchars(strip_tags($row['content'])), 0, 100) . '...</p>';
                        echo '<a href="pages/news-detail.php?id=' . $row['id'] . '" class="text-secondary hover:text-secondary-dark font-semibold">Read more →</a>';
                        echo '</div></div>';
                    }
                } else {
                    echo '<div class="col-span-full text-center"><p>No news or events available at this time.</p></div>';
                }
            } catch (PDOException $e) {
                echo '<div class="col-span-full text-center"><p>Unable to load news and events. Please try again later.</p></div>';
                error_log('Error loading news: ' . $e->getMessage());
            }
            ?>
        </div>
        
        <div class="text-center mt-10">
            <a href="pages/news.php" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300">View All News & Events</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-primary text-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">What Parents & Students Say</h2>
            <div class="w-24 h-1 bg-white mx-auto mb-6"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            try {
                // Get a fresh database connection
                $db = getDbConnection();
                $stmt = $db->query("SELECT * FROM testimonials WHERE is_approved = 1 ORDER BY created_at DESC LIMIT 3");
                
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch()) {
                        echo '<div class="bg-primary-dark p-6 rounded-lg">';
                        echo '<div class="flex items-center mb-4">';
                        if (!empty($row['image'])) {
                            echo '<img src="uploads/testimonials/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="w-12 h-12 rounded-full mr-4 object-cover">';
                        } else {
                            echo '<div class="w-12 h-12 rounded-full bg-secondary flex items-center justify-center mr-4">';
                            echo '<span class="text-white font-bold text-xl">' . substr($row['name'], 0, 1) . '</span>';
                            echo '</div>';
                        }
                        echo '<div>';
                        echo '<h4 class="font-bold">' . htmlspecialchars($row['name']) . '</h4>';
                        echo '<p class="text-sm text-gray-300">' . ucfirst(htmlspecialchars($row['role'])) . '</p>';
                        echo '</div></div>';
                        echo '<p class="italic">"' . htmlspecialchars($row['content']) . '"</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-span-full text-center"><p>No testimonials available at this time.</p></div>';
                }
            } catch (PDOException $e) {
                echo '<div class="col-span-full text-center"><p>Unable to load testimonials. Please try again later.</p></div>';
                error_log('Error loading testimonials: ' . $e->getMessage());
            }
            ?>
        </div>
        
        <div class="text-center mt-10">
            <a href="pages/testimonials.php" class="bg-white hover:bg-gray-100 text-primary font-bold py-3 px-6 rounded-lg transition duration-300">View All Testimonials</a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 bg-secondary text-white text-center">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-6">Ready to Join Our Community?</h2>
        <p class="text-xl mb-8 max-w-3xl mx-auto">Take the first step towards providing your child with a transformative Montessori education experience.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="pages/admissions.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Apply for Admission</a>
            <a href="pages/contact.php" class="bg-transparent hover:bg-secondary-light border-2 border-white text-white font-bold py-3 px-6 rounded-lg transition duration-300">Contact Us</a>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'includes/footer.php';
?>