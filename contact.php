<?php
/* ================================================
   contact.php - Contact Us Page
   ================================================ */
require_once 'config.php';
$page_title = 'Contact Us';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message_text = trim($_POST['message'] ?? '');

    // Validate
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = 'All fields are required.';
        $message_type = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $message_type = 'error';
    } else {
        // In a real app, you'd send an email here
        // For now, we'll just show a success message
        $message = 'Thank you for your message! We\'ll get back to you soon.';
        $message_type = 'success';
        
        // Optional: Log to database
        $db = getDB();
        $db->query("INSERT INTO messages (name, email, subject, message) VALUES 
            ('{$db->real_escape_string($name)}', '{$db->real_escape_string($email)}', 
             '{$db->real_escape_string($subject)}', '{$db->real_escape_string($message_text)}')");
    }
}

include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= APP_URL ?>/index.php">Home</a> <span>›</span> Contact
        </div>
        <h1>Get in Touch</h1>
        <p>We'd love to hear from you. Send us a message anytime.</p>
    </div>
</div>

<!-- Contact Content -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: start; max-width: 1100px; margin: 0 auto;">
            
            <!-- Contact Info -->
            <div>
                <h2 style="display: flex; align-items: center; gap: 12px; margin-bottom: 40px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Contact Info" style="width: 1.5rem; height: 1.5rem;">
                    Contact Information
                </h2>

                <!-- Address -->
                <div style="margin-bottom: 35px;">
                    <h4 style="color: #667eea; margin-top: 0; display: flex; align-items: center; gap: 10px;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Address" style="width: 1.2rem; height: 1.2rem;">
                        Office Location
                    </h4>
                    <p style="margin: 10px 0; color: #666;">
                        MUST Campus, Mbarara<br>
                        Mbarara University of Science and Technology<br>
                        Uganda
                    </p>
                </div>

                <!-- Phone -->
                <div style="margin-bottom: 35px;">
                    <h4 style="color: #667eea; margin-top: 0; display: flex; align-items: center; gap: 10px;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Phone" style="width: 1.2rem; height: 1.2rem;">
                        Phone
                    </h4>
                    <p style="margin: 10px 0;">
                        <a href="tel:+256700000000" style="color: #667eea; text-decoration: none;">+256 700 000 000</a><br>
                        <a href="tel:+256701111111" style="color: #667eea; text-decoration: none;">+256 701 111 111</a>
                    </p>
                </div>

                <!-- Email -->
                <div style="margin-bottom: 35px;">
                    <h4 style="color: #667eea; margin-top: 0; display: flex; align-items: center; gap: 10px;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/clipboard.svg" alt="Email" style="width: 1.2rem; height: 1.2rem;">
                        Email
                    </h4>
                    <p style="margin: 10px 0;">
                        <a href="mailto:info@musthostels.ac.ug" style="color: #667eea; text-decoration: none;">info@musthostels.ac.ug</a><br>
                        <a href="mailto:support@musthostels.ac.ug" style="color: #667eea; text-decoration: none;">support@musthostels.ac.ug</a>
                    </p>
                </div>

                <!-- Hours -->
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea;">
                    <h4 style="color: #667eea; margin-top: 0;">Business Hours</h4>
                    <p style="margin: 0; color: #666;">
                        <strong>Monday – Friday:</strong> 8:00 AM – 6:00 PM<br>
                        <strong>Saturday:</strong> 10:00 AM – 4:00 PM<br>
                        <strong>Sunday:</strong> Closed
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div>
                <h2 style="display: flex; align-items: center; gap: 12px; margin-bottom: 30px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Send Message" style="width: 1.5rem; height: 1.5rem;">
                    Send us a Message
                </h2>

                <?php if ($message): ?>
                    <div style="padding: 15px; border-radius: 8px; margin-bottom: 25px; background: <?= $message_type === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $message_type === 'success' ? '#155724' : '#721c24' ?>; border: 1px solid <?= $message_type === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;">
                        <?= sanitize($message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" style="display: flex; flex-direction: column; gap: 18px;">
                    
                    <!-- Name -->
                    <div>
                        <label for="name" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Full Name</label>
                        <input type="text" id="name" name="name" required 
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em;"
                               value="<?= htmlspecialchars($name ?? '') ?>">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em;"
                               value="<?= htmlspecialchars($email ?? '') ?>">
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Subject</label>
                        <input type="text" id="subject" name="subject" required 
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em;"
                               placeholder="How can we help?" 
                               value="<?= htmlspecialchars($subject ?? '') ?>">
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Message</label>
                        <textarea id="message" name="message" required rows="5" 
                                  style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 1em; font-family: inherit; resize: vertical;"
                                  placeholder="Tell us more about your inquiry..."><?= htmlspecialchars($message_text ?? '') ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; justify-content: center;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Send"> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Response Time Info -->
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <div style="text-align: center; max-width: 700px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; text-align: center;">
                <div>
                    <div style="color: #667eea; font-size: 1.5em; font-weight: bold; margin-bottom: 10px;">24 Hours</div>
                    <p style="margin: 0; color: #666;">Average response time for inquiries</p>
                </div>
                <div>
                    <div style="color: #667eea; font-size: 1.5em; font-weight: bold; margin-bottom: 10px;">100% Secure</div>
                    <p style="margin: 0; color: #666;">Your information is safe with us</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>
