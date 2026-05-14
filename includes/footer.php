<?php
/* ================================================
   includes/footer.php - Reusable Footer
   ================================================ */
?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand">
                <div class="nav-brand">
                    <div class="nav-brand-icon">M</div>
                    <div class="nav-brand-text" style="color:white;">
                        MUST Hostel Finder
                        <span>Mbarara University</span>
                    </div>
                </div>
                <p>
                    Helping MUST students find safe, affordable, and comfortable 
                    accommodation near campus since 2024. Your trusted hostel partner.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <div class="footer-heading">Quick Links</div>
                <div class="footer-links">
                    <a href="<?= APP_URL ?>/index.php">Home</a>
                    <a href="<?= APP_URL ?>/hostels.php">Browse Hostels</a>
                    <a href="<?= APP_URL ?>/about.php">About Us</a>
                    <a href="<?= APP_URL ?>/faq.php">FAQ</a>
                    <a href="<?= APP_URL ?>/contact.php">Contact</a>
                </div>
            </div>

            <!-- For Students -->
            <div>
                <div class="footer-heading">For Students</div>
                <div class="footer-links">
                    <a href="<?= APP_URL ?>/signup.php">Create Account</a>
                    <a href="<?= APP_URL ?>/login.php">Login</a>
                    <a href="<?= APP_URL ?>/dashboard.php">My Dashboard</a>
                    <a href="<?= APP_URL ?>/hostels.php?type=single">Single Rooms</a>
                    <a href="<?= APP_URL ?>/hostels.php?type=shared">Shared Rooms</a>
                </div>
            </div>

            <!-- Contact -->
            <div>
                <div class="footer-heading">Contact Us</div>
                <div class="footer-links">
                    <a href="#">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> MUST Campus, Mbarara
                    </a>
                    <a href="tel:+256700000000">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Phone"> +256 700 000 000
                    </a>
                    <a href="mailto:info@musthostels.ac.ug">info@musthostels.ac.ug</a>
                    <a href="#">Mon–Fri, 8am–6pm</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© <?= date('Y') ?> MUST Hostel Finder. All rights reserved.</span>
            <span>Built for Mbarara University of Science and Technology</span>
        </div>
    </div>
</footer>
