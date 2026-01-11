    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4>QuickShop</h4>
                        <p>Your trusted online shopping destination for quality products and excellent service.</p>
                        <div class="footer-social">
                            <a href="https://www.facebook.com/codebuddy661?mibextid=ZbWKwL" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://www.tiktok.com/@codebuddy05?_t=ZS-8vgRaT0x9OC&_r=1" class="social-icon"><i class="fab fa-tiktok"></i></a>
                            <a href="https://www.instagram.com/codebuddy661?utm_source=qr&igsh=MTVkaXdiN213NXZ6YQ==" class="social-icon"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/@Codebuddy166" class="social-icon"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="products.php">Products</a></li>
                            <li><a href="categories.php">Categories</a></li>
                            <li><a href="about.php">About Us</a></li>
                            <li><a href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-widget">
                        <h4>Contact Us</h4>
                        <ul class="footer-contact">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Hasilpur</span>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <span>03287299206</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>hanzla@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">&copy; <?php echo date('Y'); ?> QuickShop. All Rights Reserved.</p>
                    </div>
                   
                    </div>
                </div>
            </div>
        </div>
    </footer>

    

    <!-- Custom JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            this.classList.toggle('active');
            document.querySelector('.nav-links').classList.toggle('active');
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('back-to-top');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Cart Count Update
        function updateCartCount() {
            fetch('get_cart_count.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.cart-count').textContent = data.count;
                });
        }
        updateCartCount();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 