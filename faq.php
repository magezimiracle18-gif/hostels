<?php
/* ================================================
   faq.php - Frequently Asked Questions
   ================================================ */
require_once 'config.php';
$page_title = 'FAQ - Frequently Asked Questions';

include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= APP_URL ?>/index.php">Home</a> <span>›</span> FAQ
        </div>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about MUST Hostel Finder</p>
    </div>
</div>

<!-- FAQ Content -->
<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">

            <!-- Questions Section -->
            <div style="margin-bottom: 60px;">
                <h2 style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="FAQ" style="width: 1.5rem; height: 1.5rem;">
                    For Students
                </h2>

                <!-- Question 1 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I create an account?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Click the "Sign Up" button in the top navigation bar. Fill in your full name, email, phone number, and password. 
                            You'll receive a confirmation email to verify your account. Once verified, you can log in and start browsing hostels.
                        </p>
                    </div>
                </div>

                <!-- Question 2 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I search for hostels?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Go to the Hostels page and use the filter sidebar on the left. You can filter by location, room type (single/shared/self-contained), 
                            price range, and availability. Use the search bar to find hostels by name. Click any hostel card to view detailed information.
                        </p>
                    </div>
                </div>

                <!-- Question 3 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I book a hostel?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Find a hostel you like, click on it to view details, then click the "Book Now" button. Fill in your booking preferences 
                            and confirm. You'll receive a booking confirmation with details about the hostel and payment instructions (if applicable).
                        </p>
                    </div>
                </div>

                <!-- Question 4 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">Can I cancel my booking?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Yes, you can cancel bookings through your Dashboard. Go to "My Bookings" and click the cancel button on any confirmed booking. 
                            Please check our cancellation policy for any applicable fees or notice periods.
                        </p>
                    </div>
                </div>

                <!-- Question 5 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">What payment methods do you accept?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Payments are arranged directly between students and hostel owners. Most hostels accept mobile money (MTN, Airtel), bank transfers, 
                            or cash payment. Check the hostel details for their preferred payment method.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Hostel Owners Section -->
            <div style="margin-bottom: 60px;">
                <h2 style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel Owners" style="width: 1.5rem; height: 1.5rem;">
                    For Hostel Owners
                </h2>

                <!-- Question 6 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I list my hostel?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Sign up for an admin account and go to the Admin Dashboard. Click "Add New Hostel" and fill in details about your hostel including 
                            name, location, room types, prices, amenities, and photos. Your hostel will be reviewed and published within 24 hours.
                        </p>
                    </div>
                </div>

                <!-- Question 7 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I update my hostel information?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Log into your Admin Dashboard, go to "Manage Hostels", select your hostel, and click "Edit". 
                            Update any information and save. Changes take effect immediately.
                        </p>
                    </div>
                </div>

                <!-- Question 8 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">Is there a listing fee?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            No, listing your hostel on MUST Hostel Finder is completely free. We believe in supporting student housing 
                            without adding extra costs for hostel owners.
                        </p>
                    </div>
                </div>

                <!-- Question 9 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I track bookings?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Your Admin Dashboard displays all bookings for your hostels. You can view confirmed bookings, cancelled bookings, 
                            and contact information for students who have booked rooms.
                        </p>
                    </div>
                </div>
            </div>

            <!-- General Section -->
            <div>
                <h2 style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/clipboard.svg" alt="General" style="width: 1.5rem; height: 1.5rem;">
                    General Questions
                </h2>

                <!-- Question 10 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">Is my information secure?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Yes, we take security seriously. Your password is encrypted, and all personal information is protected according to 
                            data protection standards. Never share your login credentials with anyone.
                        </p>
                    </div>
                </div>

                <!-- Question 11 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">How do I contact support?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            You can reach our support team through the Contact page or email us at <strong>info@musthostels.ac.ug</strong>. 
                            We typically respond within 24 hours.
                        </p>
                    </div>
                </div>

                <!-- Question 12 -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 20px; overflow: hidden;">
                    <div style="padding: 20px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #f9f9f9;" onclick="toggleFAQ(this)">
                        <h4 style="margin: 0; color: #333;">Do you cover other universities?</h4>
                        <span style="font-size: 1.5em; color: #667eea;">+</span>
                    </div>
                    <div class="faq-answer" style="display: none; padding: 20px; background: white; border-top: 1px solid #e5e7eb;">
                        <p style="margin: 0; color: #666; line-height: 1.8;">
                            Currently, MUST Hostel Finder focuses on accommodation near Mbarara University of Science and Technology. 
                            We're planning to expand to other universities in the future.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto;">
            <h2>Didn't find what you're looking for?</h2>
            <p style="font-size: 1.1em; color: #666; margin-bottom: 30px;">
                Contact us directly for more help or questions.
            </p>
            <a href="<?= APP_URL ?>/contact.php" class="btn btn-primary btn-lg">
                <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Contact"> Get in Touch
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
function toggleFAQ(element) {
    const answer = element.nextElementSibling;
    const icon = element.querySelector('span');
    
    // Close all other open answers
    document.querySelectorAll('.faq-answer').forEach(a => {
        if (a !== answer) {
            a.style.display = 'none';
            a.previousElementSibling.querySelector('span').textContent = '+';
        }
    });
    
    // Toggle current answer
    if (answer.style.display === 'none' || answer.style.display === '') {
        answer.style.display = 'block';
        icon.textContent = '−';
    } else {
        answer.style.display = 'none';
        icon.textContent = '+';
    }
}
</script>
</body>
</html>
