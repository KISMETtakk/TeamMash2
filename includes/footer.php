</main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Mashitishi B. Phurutsi</h3>
                    <p>Tech-driven educator and digital innovator</p>
                    <div class="social-links">
                        <a href="https://www.linkedin.com/in/mashitishi-b-phurutsi-626975a/" target="_blank" class="social-link">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="https://orcid.org/my-orcid?orcid=0000-0003-3148-985X" target="_blank" class="social-link">
                            <i class="fab fa-orcid"></i>
                        </a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="endorse.php">Endorse Me</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Campaign</h4>
                    <p class="campaign-slogan">"For A Smarter, Stronger Council"</p>
                    <p class="vote-text">Vote Phurutsi 2024</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Mashitishi B. Phurutsi Campaign. All rights reserved.</p>
                <p class="popia-notice">
                    <a href="#" onclick="showPopiaModal()">POPIA Privacy Notice</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- POPIA Modal -->
    <div id="popia-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopiaModal()">&times;</span>
            <h2>POPIA Privacy Notice</h2>
            <div class="modal-body">
                <p>In accordance with the Protection of Personal Information Act (POPIA), we collect and process your personal information for the following purposes:</p>
                <ul>
                    <li>To display your endorsement on our website</li>
                    <li>To respond to your contact inquiries</li>
                    <li>To maintain records of campaign support</li>
                </ul>
                <p>Your information will be kept secure and will not be shared with third parties without your consent.</p>
                <p>You have the right to access, correct, or delete your personal information at any time.</p>
                <p>For any privacy-related queries, please contact us through our contact page.</p>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="back-to-top" class="back-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- JavaScript -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/animations.js"></script>
</body>
</html>