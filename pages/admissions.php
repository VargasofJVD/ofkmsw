<?php
/**
 * Admissions Page for Krisah Montessori School
 */

// Set page title and description for SEO
$page_title = "Admissions";
$page_description = "Learn about the admissions process, requirements, and fees at Krisah Montessori School in the Western Region of Ghana.";

// Include header
include_once '../includes/header.php';
?>

<!-- Page Banner -->
<section class="bg-primary py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Admissions</h1>
        <p class="text-xl text-white">Join our Montessori community</p>
    </div>
</section>

<!-- Admissions Overview -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Welcome to Our Admissions Process</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">We're delighted that you're considering Krisah Montessori School for your child's education</p>
        </div>
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-8">
                <img src="../assets/images/admissions.jpg" alt="Students at Krisah Montessori" class="rounded-lg shadow-lg">
            </div>
            <div class="md:w-1/2">
                <p class="text-gray-700 mb-4">At Krisah Montessori School, we welcome families who are committed to the Montessori approach to education. Our admissions process is designed to ensure that our school is the right fit for your child and family.</p>
                <p class="text-gray-700 mb-4">We accept applications throughout the year, but we encourage families to apply early as spaces are limited. Our school serves children from ages 2.5 to 12 years across our Preschool, Lower Primary, and Upper Primary programs.</p>
                <p class="text-gray-700">We invite you to explore our admissions process and reach out to our admissions team with any questions you may have.</p>
                <div class="mt-6">
                    <a href="#application-form" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300 inline-block">Apply Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admissions Steps -->
<section class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Admissions Process</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">Follow these steps to join our school community</p>
        </div>
        <div class="max-w-4xl mx-auto">
            <!-- Step 1 -->
            <div class="flex flex-col md:flex-row items-start mb-8 bg-white p-6 rounded-lg shadow-md">
                <div class="flex-shrink-0 bg-primary-dark rounded-full w-12 h-12 flex items-center justify-center mr-4 mb-4 md:mb-0">
                    <span class="text-white font-bold">1</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">Inquiry and School Tour</h3>
                    <p class="text-gray-700 mb-2">Contact our admissions office to schedule a tour of our school. This is an opportunity for you to see our classrooms, meet our teachers, and learn more about our Montessori approach.</p>
                    <p class="text-gray-700">Tours are typically conducted on weekdays when school is in session, allowing you to observe our learning environment in action.</p>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="flex flex-col md:flex-row items-start mb-8 bg-white p-6 rounded-lg shadow-md">
                <div class="flex-shrink-0 bg-primary-dark rounded-full w-12 h-12 flex items-center justify-center mr-4 mb-4 md:mb-0">
                    <span class="text-white font-bold">2</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">Application Submission</h3>
                    <p class="text-gray-700 mb-2">Complete and submit the application form along with the required documents and application fee. You can apply online through our website or request a physical application form from our admissions office.</p>
                    <p class="text-gray-700">Required documents include birth certificate, previous school records (if applicable), and immunization records.</p>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="flex flex-col md:flex-row items-start mb-8 bg-white p-6 rounded-lg shadow-md">
                <div class="flex-shrink-0 bg-primary-dark rounded-full w-12 h-12 flex items-center justify-center mr-4 mb-4 md:mb-0">
                    <span class="text-white font-bold">3</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">Child Visit and Assessment</h3>
                    <p class="text-gray-700 mb-2">Once your application is received, we will schedule a visit for your child to spend time in a classroom appropriate for their age. This allows us to observe your child in our environment and assess their readiness for our program.</p>
                    <p class="text-gray-700">For older children, we may conduct academic assessments to determine appropriate placement.</p>
                </div>
            </div>
            
            <!-- Step 4 -->
            <div class="flex flex-col md:flex-row items-start mb-8 bg-white p-6 rounded-lg shadow-md">
                <div class="flex-shrink-0 bg-primary-dark rounded-full w-12 h-12 flex items-center justify-center mr-4 mb-4 md:mb-0">
                    <span class="text-white font-bold">4</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">Family Interview</h3>
                    <p class="text-gray-700 mb-2">Parents/guardians will meet with the school director or a senior staff member to discuss your educational philosophy, expectations, and commitment to the Montessori approach.</p>
                    <p class="text-gray-700">This conversation helps ensure alignment between family values and our educational approach.</p>
                </div>
            </div>
            
            <!-- Step 5 -->
            <div class="flex flex-col md:flex-row items-start bg-white p-6 rounded-lg shadow-md">
                <div class="flex-shrink-0 bg-primary-dark rounded-full w-12 h-12 flex items-center justify-center mr-4 mb-4 md:mb-0">
                    <span class="text-white font-bold">5</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-primary mb-2">Acceptance and Enrollment</h3>
                    <p class="text-gray-700 mb-2">If your child is accepted, you will receive an acceptance letter and enrollment package. To secure your child's place, you will need to complete the enrollment forms and pay the enrollment fee within the specified timeframe.</p>
                    <p class="text-gray-700">Welcome to the Krisah Montessori School family!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tuition and Fees -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Tuition and Fees</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">Investment in your child's education</p>
        </div>
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <p class="text-gray-700 mb-6">Krisah Montessori School strives to provide high-quality education at reasonable rates. Our fees include tuition, materials, and most extracurricular activities. Below is an overview of our fee structure for the current academic year:</p>
            
            <div class="overflow-x-auto mb-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="border px-4 py-2 text-left">Program</th>
                            <th class="border px-4 py-2 text-left">Application Fee</th>
                            <th class="border px-4 py-2 text-left">Annual Tuition</th>
                            <th class="border px-4 py-2 text-left">Materials Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2 font-medium">Preschool (Half Day)</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-medium">Preschool (Full Day)</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2 font-medium">Lower Primary</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border px-4 py-2 font-medium">Upper Primary</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                            <td class="border px-4 py-2">GHS XXX</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <p class="text-gray-700 mb-4">Additional fees may apply for:</p>
            <ul class="list-disc pl-5 mb-6 text-gray-700">
                <li class="mb-2">School uniform</li>
                <li class="mb-2">Transportation services</li>
                <li class="mb-2">After-school programs</li>
                <li class="mb-2">Special field trips</li>
            </ul>
            
            <p class="text-gray-700 mb-4">Payment plans are available, including annual, termly, and monthly options. Please contact our finance office for more details.</p>
            
            <div class="bg-light p-4 rounded-lg">
                <p class="text-gray-700 italic">Note: Fees are subject to review annually. Limited financial aid and scholarships may be available for qualifying families.</p>
            </div>
        </div>
    </div>
</section>

<!-- Application Form -->
<section id="application-form" class="py-16 bg-light">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Application Form</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">Take the first step towards enrolling your child</p>
        </div>
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <form action="../includes/process_admission.php" method="POST" class="space-y-6">
                <h3 class="text-xl font-bold text-primary mb-4">Child Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="child_first_name" class="block text-gray-700 font-medium mb-2">First Name *</label>
                        <input type="text" id="child_first_name" name="child_first_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="child_last_name" class="block text-gray-700 font-medium mb-2">Last Name *</label>
                        <input type="text" id="child_last_name" name="child_last_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="child_dob" class="block text-gray-700 font-medium mb-2">Date of Birth *</label>
                        <input type="date" id="child_dob" name="child_date_of_birth" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="child_gender" class="block text-gray-700 font-medium mb-2">Gender *</label>
                        <select id="child_gender" name="child_gender" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="applying_for_class" class="block text-gray-700 font-medium mb-2">Applying for Class *</label>
                        <select id="applying_for_class" name="applying_for_class" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select Class</option>
                            <option value="preschool">Preschool</option>
                            <option value="lower_primary">Lower Primary</option>
                            <option value="upper_primary">Upper Primary</option>
                        </select>
                    </div>
                    <div>
                        <label for="previous_school" class="block text-gray-700 font-medium mb-2">Previous School (if any)</label>
                        <input type="text" id="previous_school" name="previous_school" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-primary mb-4 mt-8">Parent/Guardian Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="parent_name" class="block text-gray-700 font-medium mb-2">Full Name *</label>
                        <input type="text" id="parent_name" name="parent_name" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="parent_phone" class="block text-gray-700 font-medium mb-2">Phone Number *</label>
                        <input type="tel" id="parent_phone" name="parent_phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="parent_email" class="block text-gray-700 font-medium mb-2">Email Address *</label>
                        <input type="email" id="parent_email" name="parent_email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                    <div>
                        <label for="how_did_you_hear" class="block text-gray-700 font-medium mb-2">How did you hear about us? *</label>
                        <select id="how_did_you_hear" name="how_did_you_hear" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">Select Option</option>
                            <option value="website">Website</option>
                            <option value="social_media">Social Media</option>
                            <option value="friend">Friend/Family</option>
                            <option value="newspaper">Newspaper</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="parent_address" class="block text-gray-700 font-medium mb-2">Address *</label>
                    <textarea id="parent_address" name="parent_address" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                </div>
                
                <div class="mt-6">
                    <label for="additional_info" class="block text-gray-700 font-medium mb-2">Additional Information</label>
                    <textarea id="additional_info" name="additional_info" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Please share any additional information that would help us understand your child better"></textarea>
                </div>
                
                <div class="mt-6">
                    <div class="flex items-start">
                        <input type="checkbox" id="terms" name="terms" required class="mt-1 mr-2">
                        <label for="terms" class="text-gray-700">I confirm that the information provided is accurate and I understand that submission of this form does not guarantee admission *</label>
                    </div>
                </div>
                
                <div class="mt-8">
                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- FAQs -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Frequently Asked Questions</h2>
            <p class="text-xl text-gray-700 max-w-3xl mx-auto">Answers to common questions about our admissions process</p>
        </div>
        <div class="max-w-3xl mx-auto">
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-primary mb-2">What is the ideal age to start Montessori education?</h3>
                <p class="text-gray-700">Children can begin Montessori education as early as 2.5 years old. Starting early allows children to fully benefit from the Montessori approach during their formative years, but children can join at any age and still benefit from our educational philosophy.</p>
            </div>
            
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-primary mb-2">Do you accept children with no prior Montessori experience?</h3>
                <p class="text-gray-700">Yes, we welcome children from all educational backgrounds. Our teachers are experienced in helping children transition to the Montessori environment, regardless of their previous educational experience.</p>
            </div>
            
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-primary mb-2">What is your teacher-to-student ratio?</h3>
                <p class="text-gray-700">Our teacher-to-student ratios vary by age group but are kept low to ensure personalized attention. Typically, we maintain a ratio of 1:10 for preschool and 1:15 for primary levels, with teaching assistants providing additional support.</p>
            </div>
            
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-primary mb-2">Do you offer financial aid or scholarships?</h3>
                <p class="text-gray-700">We offer limited financial aid and scholarships based on need and merit. Families interested in financial assistance should inquire during the application process for more information about eligibility and application procedures.</p>
            </div>
            
            <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-primary mb-2">What is your policy on mid-year admissions?</h3>
                <p class="text-gray-700">We accept applications throughout the year and offer mid-year admissions based on availability. Contact our admissions office to inquire about current openings for your child's age group.</p>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once '../includes/footer.php';
?>