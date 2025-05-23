<?php
/**
 * View Testimonial Modal Component
 */
?>
<!-- View Testimonial Modal -->
<div id="viewTestimonialModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Testimonial Details</h3>
                <button onclick="document.getElementById('viewTestimonialModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div class="flex items-center space-x-4">
                    <div id="view_testimonial_image" class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    </div>
                    <div>
                        <h4 id="view_testimonial_name" class="text-lg font-semibold text-gray-900"></h4>
                        <p id="view_testimonial_role" class="text-gray-600"></p>
                        <p id="view_testimonial_email" class="text-sm text-gray-500"></p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <div id="view_testimonial_rating" class="flex"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Testimonial</label>
                    <p id="view_testimonial_content" class="text-gray-700 whitespace-pre-wrap"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Submitted On</label>
                    <p id="view_testimonial_date" class="text-gray-600"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// View testimonial
function viewTestimonial(testimonial) {
    // Set testimonial details
    document.getElementById('view_testimonial_name').textContent = testimonial.name;
    document.getElementById('view_testimonial_role').textContent = testimonial.role;
    document.getElementById('view_testimonial_email').textContent = testimonial.email;
    document.getElementById('view_testimonial_content').textContent = testimonial.content;
    document.getElementById('view_testimonial_date').textContent = new Date(testimonial.created_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Set rating stars
    const ratingContainer = document.getElementById('view_testimonial_rating');
    ratingContainer.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('svg');
        star.className = `w-5 h-5 ${i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300'}`;
        star.setAttribute('fill', 'currentColor');
        star.setAttribute('viewBox', '0 0 20 20');
        star.innerHTML = '<path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>';
        ratingContainer.appendChild(star);
    }
    
    // Set image if exists
    const imageContainer = document.getElementById('view_testimonial_image');
    if (testimonial.image) {
        imageContainer.innerHTML = `<img src="../uploads/testimonials/${testimonial.image}" alt="${testimonial.name}" class="w-16 h-16 rounded-full object-cover">`;
    } else {
        imageContainer.innerHTML = '<i class="fas fa-user text-gray-400 text-2xl"></i>';
    }
    
    // Show modal
    document.getElementById('viewTestimonialModal').classList.remove('hidden');
}
</script> 