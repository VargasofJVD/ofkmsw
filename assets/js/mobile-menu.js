// Mobile Menu Functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const navLinks = document.getElementById('nav-links');
    
    if (mobileMenuButton && navLinks) {
        // Toggle menu on button click
        mobileMenuButton.addEventListener('click', function() {
            // Toggle menu visibility
            if (navLinks.classList.contains('hidden')) {
                navLinks.classList.remove('hidden');
                navLinks.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
            } else {
                navLinks.classList.remove('flex');
                navLinks.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
            
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

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navLinks.contains(event.target) && !mobileMenuButton.contains(event.target) && !navLinks.classList.contains('hidden')) {
                navLinks.classList.remove('flex');
                navLinks.classList.add('hidden');
                document.body.style.overflow = '';
                
                const icon = mobileMenuButton.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
}); 