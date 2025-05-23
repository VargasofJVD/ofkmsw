<?php
/**
 * Academics Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "Academics";
$page_description = "Explore our Montessori curriculum for Preschool, Lower Primary, and Upper Primary at Krisah Montessori School.";

// Include header
include_once '../includes/header.php';
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Academics</h1>
        <p class="text-xl text-white">Discover our Montessori educational approach</p>
    </div>
</section>

<!-- Montessori Approach Overview -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">The Montessori Approach</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">A child-centered educational philosophy that nurtures the whole child</p>
        </div>
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <img src="../assets/images/montessori-materials.jpg" alt="Montessori Learning Materials" class="rounded-lg shadow-lg">
            </div>
            <div class="md:w-1/2">
                <p class="text-gray-700 mb-4">The Montessori method, developed by Dr. Maria Montessori, is an educational approach characterized by an emphasis on independence, freedom within limits, and respect for a child's natural psychological, physical, and social development.</p>
                <p class="text-gray-700 mb-4">At Krisah Montessori School, we implement this philosophy through:</p>
                <ul class="list-disc pl-5 mb-4 text-gray-700">
                    <li class="mb-2">Child-led learning in prepared environments</li>
                    <li class="mb-2">Mixed-age classrooms that foster peer learning</li>
                    <li class="mb-2">Specialized educational materials that isolate specific skills</li>
                    <li class="mb-2">Uninterrupted work periods that develop concentration</li>
                    <li class="mb-2">Trained teachers who observe and guide rather than instruct</li>
                </ul>
                <p class="text-gray-700">This approach helps children develop a strong foundation in academic excellence while nurturing their natural curiosity, creativity, and love for learning.</p>
            </div>
        </div>
    </div>
</section>

<!-- Educational Levels -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Our Educational Programs</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">Comprehensive Montessori education from preschool through upper primary</p>
        </div>
        
        <!-- Preschool -->
        <div class="bg-white p-8 rounded-lg shadow-md mb-8">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <img src="../assets/images/preschool.jpg" alt="Preschool Classroom" class="rounded-lg shadow-md w-full h-64 object-cover">
                </div>
                <div class="md:w-2/3 md:pl-8">
                    <h3 class="text-2xl font-bold text-primary mb-4">Preschool (Ages 2.5-6)</h3>
                    <p class="text-gray-700 mb-4">Our preschool program provides a nurturing environment where children develop independence, concentration, and a love for learning. The curriculum focuses on:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Practical Life</h4>
                            <p class="text-gray-700">Activities that develop motor skills, coordination, independence, and concentration.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Sensorial</h4>
                            <p class="text-gray-700">Materials that refine the senses and help children order and classify their environment.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Language</h4>
                            <p class="text-gray-700">Activities that build vocabulary, phonemic awareness, and pre-reading/writing skills.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Mathematics</h4>
                            <p class="text-gray-700">Concrete materials that introduce mathematical concepts and operations.</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Children also explore cultural subjects, music, art, and outdoor activities in this formative stage.</p>
                </div>
            </div>
        </div>
        
        <!-- Lower Primary -->
        <div class="bg-white p-8 rounded-lg shadow-md mb-8">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <img src="../assets/images/lower-primary.jpg" alt="Lower Primary Classroom" class="rounded-lg shadow-md w-full h-64 object-cover">
                </div>
                <div class="md:w-2/3 md:pl-8">
                    <h3 class="text-2xl font-bold text-primary mb-4">Lower Primary (Ages 6-9)</h3>
                    <p class="text-gray-700 mb-4">Building on the foundation established in preschool, our lower primary program expands children's knowledge and skills through:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Language Arts</h4>
                            <p class="text-gray-700">Reading fluency, comprehension, grammar, spelling, and creative writing.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Mathematics</h4>
                            <p class="text-gray-700">Operations, fractions, decimals, geometry, and problem-solving using Montessori materials.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Cultural Studies</h4>
                            <p class="text-gray-700">Geography, history, science, and botany through hands-on exploration.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Practical Skills</h4>
                            <p class="text-gray-700">Time management, organization, research skills, and collaborative work.</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Students work at their own pace while developing responsibility, independence, and critical thinking skills.</p>
                </div>
            </div>
        </div>
        
        <!-- Upper Primary -->
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0">
                    <img src="../assets/images/upper-primary.jpg" alt="Upper Primary Classroom" class="rounded-lg shadow-md w-full h-64 object-cover">
                </div>
                <div class="md:w-2/3 md:pl-8">
                    <h3 class="text-2xl font-bold text-primary mb-4">Upper Primary (Ages 9-12)</h3>
                    <p class="text-gray-700 mb-4">Our upper primary program prepares students for secondary education while continuing to foster independence and a love for learning:</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Advanced Language Arts</h4>
                            <p class="text-gray-700">Literature analysis, research writing, public speaking, and advanced grammar.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Advanced Mathematics</h4>
                            <p class="text-gray-700">Pre-algebra, algebraic concepts, advanced geometry, and mathematical applications.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Integrated Sciences</h4>
                            <p class="text-gray-700">Physics, chemistry, biology, and environmental science through experiments and projects.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-secondary mb-2">Social Studies</h4>
                            <p class="text-gray-700">Ghanaian and world history, civics, economics, and current events.</p>
                        </div>
                    </div>
                    <p class="text-gray-700">Students engage in collaborative projects, community service, and leadership opportunities that prepare them for future academic and personal success.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Curriculum Features -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Curriculum Features</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">What makes our educational approach unique</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Mixed-Age Classrooms</h3>
                <p class="text-gray-700">Our classrooms span a three-year age range, allowing children to learn from peers and develop leadership skills.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Uninterrupted Work Periods</h3>
                <p class="text-gray-700">Extended blocks of time allow students to deeply engage with materials and develop concentration and persistence.</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="bg-primary-dark rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hands text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-primary mb-2">Hands-On Learning</h3>
                <p class="text-gray-700">Specialized materials provide concrete experiences that build understanding of abstract concepts.</p>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?>