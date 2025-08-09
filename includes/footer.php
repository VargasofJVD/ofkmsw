<?php
/**
 * Footer template for Krisah Montessori School Website
 * Includes footer content, JavaScript imports, and closing HTML tags
 */
?>
    </main>
    
    <!-- Footer -->
    <footer class="bg-primary text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <!-- School Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Krisah Montessori School</h3>
                    <p class="mb-4"><?php echo htmlspecialchars($settings['about_us'] ?? 'A quality Montessori education institution in the Western Region of Ghana.'); ?></p>
                    <div class="flex space-x-3">
                        <a href="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>" class="bg-primary-dark hover:bg-secondary w-10 h-10 rounded-full flex items-center justify-center transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>" class="bg-primary-dark hover:bg-secondary w-10 h-10 rounded-full flex items-center justify-center transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo htmlspecialchars($settings['twitter_url'] ?? '#'); ?>" class="bg-primary-dark hover:bg-secondary w-10 h-10 rounded-full flex items-center justify-center transition duration-300" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/ofkmsw/index.php" class="hover:text-secondary transition duration-300">Home</a></li>
                        <li><a href="/ofkmsw/pages/about.php" class="hover:text-secondary transition duration-300">About Us</a></li>
                        <li><a href="/ofkmsw/pages/academics.php" class="hover:text-secondary transition duration-300">Academics</a></li>
                        <li><a href="/ofkmsw/pages/contact.php" class="hover:text-secondary transition duration-300">Contact</a></li>
                        <li><a href="/ofkmsw/pages/news-events.php" class="hover:text-secondary transition duration-300">News & Events</a></li>
                        <li><a href="/ofkmsw/pages/testimonials.php" class="hover:text-secondary transition duration-300">Testimonials</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3"></i>
                            <span><?php echo htmlspecialchars($settings['school_address'] ?? 'Western Region, Ghana'); ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3"></i>
                            <span><?php echo htmlspecialchars($settings['school_phone'] ?? '+233 XX XXX XXXX'); ?></span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3"></i>
                            <span><?php echo htmlspecialchars($settings['school_email'] ?? 'info@krisahmontessori.com'); ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Newsletter -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Newsletter</h3>
                    <p class="mb-4">Subscribe to our newsletter for updates on school events and activities.</p>
                    <button onclick="openNewsletterModal()" class="w-full bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                        <i class="fas fa-envelope mr-2"></i>
                        Subscribe to Newsletter
                    </button>
                </div>
            </div>
            
            <!-- Divider -->
            <div class="border-t border-primary-light pt-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p>&copy; <?php echo date('Y'); ?> Krisah Montessori School. All rights reserved.</p>
                    <p class="mt-2 md:mt-0">Designed with <i class="fas fa-heart text-secondary"></i> for quality education</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const navLinks = document.getElementById('nav-links');
            navLinks.classList.toggle('hidden');
        });
    </script>
    
    <!-- Custom JavaScript -->
    <script src="/ofkmsw/assets/js/main.js"></script>
    
    <?php if (isset($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Include Newsletter Modal -->
    <?php include_once __DIR__ . '/newsletter-modal.php'; ?>
</body>
</html>