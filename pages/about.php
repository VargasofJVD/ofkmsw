<?php
/**
 * About Us Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "About Us";
$page_description = "Learn about Krisah Montessori School's mission, values, and educational approach in the Western Region of Ghana.";

// Include header
include_once '../includes/header.php';
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">About Us</h1>
        <p class="text-xl text-white">Learn about our mission, values, and educational approach</p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-primary mb-4">Our Mission</h2>
                <p class="text-gray-700"><?php echo htmlspecialchars($settings['mission_statement'] ?? 'Our mission is to provide a nurturing environment that cultivates independent thinking, creativity, and a lifelong love of learning through the Montessori approach.'); ?></p>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-primary mb-4">Our Vision</h2>
                <p class="text-gray-700"><?php echo htmlspecialchars($settings['vision_statement'] ?? 'To be the leading Montessori institution in Ghana, recognized for excellence in education and character development.'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Our Story</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">The journey of Krisah Montessori School</p>
        </div>
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <img src="../assets/images/school-building.jpg" alt="Krisah Montessori School Building" class="rounded-lg shadow-lg">
            </div>
            <div class="md:w-1/2">
                <p class="text-gray-700 mb-4">Krisah Montessori School was founded in [founding year] with a vision to provide quality Montessori education in the Western Region of Ghana. What began as a small preschool has grown into a comprehensive educational institution serving children from preschool through upper primary.</p>
                <p class="text-gray-700 mb-4">Our school was established on the principles of Dr. Maria Montessori, focusing on child-centered learning, hands-on experiences, and fostering independence. We believe that each child is unique and deserves an education that respects their individuality and natural development.</p>
                <p class="text-gray-700">Today, Krisah Montessori School stands as a beacon of educational excellence in the region, continuing to nurture young minds and prepare them for future success.</p>
            </div>
        </div>
    </div>
</section>

<!-- Core Values -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Our Core Values</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">The principles that guide our educational approach</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-child text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Child-Centered Learning</h3>
                <p class="text-gray-700">We recognize each child's unique potential and tailor our approach to their individual needs and interests.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hands-helping text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Community & Collaboration</h3>
                <p class="text-gray-700">We foster a sense of community and encourage collaboration among students, teachers, and parents.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-reader text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Lifelong Learning</h3>
                <p class="text-gray-700">We instill a love for learning that extends beyond the classroom and continues throughout life.</p>
            </div>
        </div>
    </div>
</section>

<!-- Headteacher's Message -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Headteacher's Message</h2>
        </div>
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/3 mb-8 md:mb-0 md:pr-8 text-center">
                <img src="../assets/images/headteacher.jpg" alt="Headteacher" class="rounded-full w-48 h-48 object-cover mx-auto mb-4 border-4 border-primary">
                <h3 class="text-xl font-bold text-primary">Mrs. [Headteacher Name]</h3>
                <p class="text-gray-700">Headteacher</p>
            </div>
            <div class="md:w-2/3">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <p class="text-gray-700 mb-4">Welcome to Krisah Montessori School! As the Headteacher, I am delighted to lead an institution that is committed to providing a nurturing and stimulating environment for children to learn and grow.</p>
                    <p class="text-gray-700 mb-4">At Krisah, we believe in the Montessori philosophy that respects each child's unique development path. Our dedicated teachers guide students to discover their potential through hands-on learning experiences that foster independence, critical thinking, and creativity.</p>
                    <p class="text-gray-700">We invite you to visit our school and see firsthand how our approach to education is shaping the future leaders of Ghana. Thank you for considering Krisah Montessori School for your child's educational journey.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?>