/**
 * Main JavaScript file for Krisah Montessori School Website
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all interactive elements
    initMobileMenu();
    initSmoothScroll();
    initGalleryLightbox();
    initFormValidation();
});

/**
 * Mobile Menu Functionality
 */
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const navLinks = document.getElementById('nav-links');
    
    if (mobileMenuButton && navLinks) {
        // Toggle menu on button click
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event from bubbling up
            navLinks.classList.toggle('hidden');
            
            // Toggle icon between bars and X
            const icon = mobileMenuButton.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close menu when clicking a link
        const menuLinks = navLinks.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.classList.add('hidden');
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navLinks.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                navLinks.classList.add('hidden');
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
}

/**
 * Smooth Scroll for Anchor Links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update URL but without scrolling
                history.pushState(null, null, targetId);
            }
        });
    });
}

/**
 * Gallery Lightbox Functionality
 */
function initGalleryLightbox() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    if (galleryItems.length > 0) {
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const imgSrc = this.querySelector('img').getAttribute('src');
                const imgAlt = this.querySelector('img').getAttribute('alt');
                const videoSrc = this.dataset.videoSrc;
                
                // Create lightbox elements
                const lightbox = document.createElement('div');
                lightbox.className = 'fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50';
                
                // Close button
                const closeBtn = document.createElement('button');
                closeBtn.className = 'absolute top-4 right-4 text-white text-2xl';
                closeBtn.innerHTML = '<i class="fas fa-times"></i>';
                closeBtn.addEventListener('click', () => lightbox.remove());
                
                // Content container
                const content = document.createElement('div');
                content.className = 'max-w-4xl max-h-[90vh] relative';
                
                // Add appropriate content (image or video)
                if (videoSrc) {
                    const video = document.createElement('video');
                    video.className = 'max-h-[90vh] max-w-full';
                    video.controls = true;
                    video.autoplay = true;
                    
                    const source = document.createElement('source');
                    source.src = videoSrc;
                    source.type = 'video/mp4';
                    
                    video.appendChild(source);
                    content.appendChild(video);
                } else {
                    const img = document.createElement('img');
                    img.className = 'max-h-[90vh] max-w-full';
                    img.src = imgSrc;
                    img.alt = imgAlt;
                    content.appendChild(img);
                }
                
                // Caption
                if (imgAlt && !videoSrc) {
                    const caption = document.createElement('div');
                    caption.className = 'text-white text-center mt-2';
                    caption.textContent = imgAlt;
                    content.appendChild(caption);
                }
                
                // Assemble and add to DOM
                lightbox.appendChild(closeBtn);
                lightbox.appendChild(content);
                document.body.appendChild(lightbox);
                
                // Close on background click
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        lightbox.remove();
                    }
                });
                
                // Close on ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        lightbox.remove();
                    }
                });
            });
        });
    }
}

/**
 * Form Validation
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    showError(field, 'This field is required');
                } else {
                    clearError(field);
                }
            });
            
            // Check email fields
            const emailFields = form.querySelectorAll('input[type="email"]');
            emailFields.forEach(field => {
                if (field.value.trim() && !isValidEmail(field.value)) {
                    isValid = false;
                    showError(field, 'Please enter a valid email address');
                }
            });
            
            // Prevent form submission if validation fails
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    
    // Helper functions
    function showError(field, message) {
        // Clear any existing error
        clearError(field);
        
        // Add error class to field
        field.classList.add('border-red-500');
        
        // Create and insert error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
    
    function clearError(field) {
        field.classList.remove('border-red-500');
        const errorDiv = field.parentNode.querySelector('.text-red-500');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
}

/**
 * Testimonial Carousel (if present)
 */
function initTestimonialCarousel() {
    const carousel = document.querySelector('.testimonial-carousel');
    if (!carousel) return;
    
    const slides = carousel.querySelectorAll('.testimonial-slide');
    const dotsContainer = carousel.querySelector('.carousel-dots');
    let currentIndex = 0;
    
    // Create dots if they don't exist
    if (!dotsContainer && slides.length > 1) {
        const dots = document.createElement('div');
        dots.className = 'carousel-dots flex justify-center mt-4 space-x-2';
        
        slides.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.className = 'w-3 h-3 rounded-full bg-gray-300';
            dot.addEventListener('click', () => goToSlide(index));
            dots.appendChild(dot);
        });
        
        carousel.appendChild(dots);
    }
    
    // Initialize carousel
    function initCarousel() {
        slides.forEach((slide, index) => {
            slide.style.display = index === currentIndex ? 'block' : 'none';
        });
        
        updateDots();
    }
    
    // Go to specific slide
    function goToSlide(index) {
        slides[currentIndex].style.display = 'none';
        currentIndex = index;
        slides[currentIndex].style.display = 'block';
        updateDots();
    }
    
    // Update dots to reflect current slide
    function updateDots() {
        const dots = carousel.querySelectorAll('.carousel-dots button');
        dots.forEach((dot, index) => {
            if (index === currentIndex) {
                dot.classList.add('bg-primary');
                dot.classList.remove('bg-gray-300');
            } else {
                dot.classList.add('bg-gray-300');
                dot.classList.remove('bg-primary');
            }
        });
    }
    
    // Auto-advance slides
    function autoAdvance() {
        goToSlide((currentIndex + 1) % slides.length);
    }
    
    // Initialize and set auto-advance if multiple slides
    if (slides.length > 0) {
        initCarousel();
        
        if (slides.length > 1) {
            setInterval(autoAdvance, 5000);
        }
    }
}