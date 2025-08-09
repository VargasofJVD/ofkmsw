<!-- Newsletter Subscription Modal -->
<div id="newsletter-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="modal-content">
        <!-- Modal Header -->
        <div class="bg-primary text-white p-4 rounded-t-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">Stay Updated!</h3>
                    <p class="text-primary-light text-sm mt-1">Get the latest news from Krisah Montessori</p>
                </div>
                <button id="close-modal" class="text-white hover:text-gray-200 transition duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4">
            <div class="text-center mb-4">
                <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-envelope text-xl text-primary"></i>
                </div>
                <h4 class="text-base font-semibold text-gray-800 mb-1">Join Our Newsletter</h4>
                <p class="text-gray-600 text-sm">Receive updates about school events and announcements.</p>
            </div>
            
            <form id="newsletter-modal-form" class="space-y-3">
                <div>
                    <label for="modal-name" class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" id="modal-name" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 text-sm"
                           placeholder="Enter your full name">
                </div>
                
                <div>
                    <label for="modal-email" class="block text-xs font-medium text-gray-700 mb-1">Email Address *</label>
                    <input type="email" id="modal-email" name="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-primary focus:border-transparent transition duration-300 text-sm"
                           placeholder="Enter your email address">
                </div>
                
                <div class="flex items-start">
                    <input type="checkbox" id="modal-consent" name="consent" required 
                           class="h-3 w-3 text-primary focus:ring-primary border-gray-300 rounded mt-0.5">
                    <label for="modal-consent" class="ml-2 block text-xs text-gray-700">
                        I agree to receive newsletter updates from Krisah Montessori School
                    </label>
                </div>
                
                <button type="submit" id="modal-submit-btn" 
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded transition duration-300 flex items-center justify-center text-sm">
                    <span>Subscribe to Newsletter</span>
                    <i class="fas fa-paper-plane ml-2"></i>
                </button>
            </form>
            
            <div id="modal-message" class="mt-3 text-xs hidden"></div>
        </div>
    </div>
</div>

<script>
// Newsletter Modal Functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('newsletter-modal');
    const modalContent = document.getElementById('modal-content');
    const closeBtn = document.getElementById('close-modal');
    const form = document.getElementById('newsletter-modal-form');
    const messageDiv = document.getElementById('modal-message');
    const submitBtn = document.getElementById('modal-submit-btn');
    
    // Open modal function
    window.openNewsletterModal = function() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    };
    
    // Close modal function
    function closeModal() {
        modalContent.classList.add('scale-95', 'opacity-0');
        modalContent.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form
            form.reset();
            messageDiv.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Subscribe to Newsletter</span><i class="fas fa-paper-plane ml-2"></i>';
        }, 300);
    }
    
    // Close modal on button click
    closeBtn.addEventListener('click', closeModal);
    
    // Close modal on outside click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submitted');
        
        const formData = new FormData(form);
        const name = formData.get('name');
        const email = formData.get('email');
        const consent = formData.get('consent');
        
        console.log('Form data:', { name, email, consent });
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Subscribing...';
        messageDiv.classList.add('hidden');
        
        // Send AJAX request
        fetch('/ofkmsw/includes/subscribe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&consent=' + encodeURIComponent(consent)
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            messageDiv.textContent = data.message;
            messageDiv.className = 'mt-4 text-sm ' + (data.success ? 'text-green-600 bg-green-50 p-3 rounded' : 'text-red-600 bg-red-50 p-3 rounded');
            messageDiv.classList.remove('hidden');
            
            if (data.success) {
                form.reset();
                // Close modal after 2 seconds on success
                setTimeout(closeModal, 2000);
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Subscribe to Newsletter</span><i class="fas fa-paper-plane ml-2"></i>';
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            messageDiv.textContent = 'An error occurred. Please try again.';
            messageDiv.className = 'mt-4 text-sm text-red-600 bg-red-50 p-3 rounded';
            messageDiv.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Subscribe to Newsletter</span><i class="fas fa-paper-plane ml-2"></i>';
        });
    });
});
</script> 