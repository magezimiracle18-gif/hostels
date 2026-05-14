<?php
/* ================================================
   about.php - About Hostel Mate
   ================================================ */
require_once 'config.php';
$page_title = 'About Us';

$db = getDB();

// Get platform stats
$total_hostels  = $db->query("SELECT COUNT(*) as c FROM hostels")->fetch_assoc()['c'] ?? 0;
$total_students = $db->query("SELECT COUNT(*) as c FROM users WHERE role='student'")->fetch_assoc()['c'] ?? 0;
$total_bookings = $db->query("SELECT COUNT(*) as c FROM bookings WHERE status='confirmed'")->fetch_assoc()['c'] ?? 0;

// Fallback demo numbers
if ($total_hostels  < 3)  $total_hostels  = 500;
if ($total_students < 10) $total_students = 15000;
if ($total_bookings < 5)  $total_bookings = 5000;

include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <div class="breadcrumb">
            <a href="<?= APP_URL ?>/index.php">Home</a> <span>›</span> About
        </div>
        <h1>About Hostel Mate</h1>
        <p>Connecting students with safe, affordable accommodation near campus</p>
    </div>
</div>

<!-- Story Section -->
<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 40px;">
                <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Our Story" style="width: 2rem; height: 2rem; margin-bottom: 20px;">
                <h2>Our Story</h2>
            </div>
            <p style="font-size: 1.1em; line-height: 1.8; color: #555; margin-bottom: 20px;">
                Hostel Mate was created to solve a real problem: finding quality student accommodation in Mbarara 
                is challenging, time-consuming, and often inefficient. Students waste countless hours searching through 
                scattered listings, making phone calls, and visiting properties individually.
            </p>
            <p style="font-size: 1.1em; line-height: 1.8; color: #555;">
                Our platform brings together hostel owners and student seekers in one centralized, easy-to-use marketplace. 
                We've built a solution that saves time, ensures transparency, and provides peace of mind for both sides.
            </p>
        </div>
    </div>
</section>

<!-- Mission, Vision, Values -->
<section class="section" style="background: #f8f9fa;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">What We Stand For</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
            <!-- Mission -->
            <div style="background: white; padding: 30px; border-radius: 8px; border-left: 4px solid #667eea; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Mission" style="width: 1.5rem; height: 1.5rem; color: #667eea;">
                    <h3 style="margin: 0; color: #667eea;">Our Mission</h3>
                </div>
                <p style="margin: 0; color: #666; line-height: 1.6;">
                    To make student accommodation accessible, affordable, and hassle-free across Mbarara by connecting verified hostels with verified students.
                </p>
            </div>

            <!-- Vision -->
            <div style="background: white; padding: 30px; border-radius: 8px; border-left: 4px solid #764ba2; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="Vision" style="width: 1.5rem; height: 1.5rem; color: #764ba2;">
                    <h3 style="margin: 0; color: #764ba2;">Our Vision</h3>
                </div>
                <p style="margin: 0; color: #666; line-height: 1.6;">
                    To become the most trusted and comprehensive student housing platform in East Africa, setting new standards for accommodation experiences.
                </p>
            </div>

            <!-- Values -->
            <div style="background: white; padding: 30px; border-radius: 8px; border-left: 4px solid #f093fb; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/lock.svg" alt="Values" style="width: 1.5rem; height: 1.5rem; color: #f093fb;">
                    <h3 style="margin: 0; color: #f093fb;">Our Values</h3>
                </div>
                <p style="margin: 0; color: #666; line-height: 1.6;">
                    Trust, transparency, accessibility, and community. We're committed to ethical practices and putting our users first.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 40px;">
                <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/clipboard.svg" alt="Why Choose Us" style="width: 1.5rem; height: 1.5rem;">
                <h2 style="margin: 0;">Why Choose Us?</h2>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Verified Hostels
                    </h4>
                    <p style="color: #666;">All hostels undergo quality verification before listing</p>
                </div>
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Real Photos & Reviews
                    </h4>
                    <p style="color: #666;">See actual hostel images and honest student feedback</p>
                </div>
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Easy Booking
                    </h4>
                    <p style="color: #666;">Secure online booking with confirmation and cancellation support</p>
                </div>
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Multiple Filters
                    </h4>
                    <p style="color: #666;">Search by location, price, room type, and amenities</p>
                </div>
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Direct Communication
                    </h4>
                    <p style="color: #666;">Connect directly with hostel owners for inquiries</p>
                </div>
                <div>
                    <h4 style="color: #667eea; margin-top: 0;">
                        <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/check.svg" alt="Check" style="width: 1rem; height: 1rem; margin-right: 8px; vertical-align: middle;">
                        Student-Centric
                    </h4>
                    <p style="color: #666;">Built specifically with student needs and budgets in mind</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Platform Stats -->
<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="container">
        <h2 style="text-align: center; color: white; margin-bottom: 50px;">Platform Impact</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; text-align: center;">
            <div>
                <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 10px;"><?= number_format($total_hostels) ?>+</div>
                <div style="font-size: 1.1em; opacity: 0.9;">Quality Hostels Listed</div>
            </div>
            <div>
                <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 10px;"><?= number_format($total_students) ?>+</div>
                <div style="font-size: 1.1em; opacity: 0.9;">Active Students</div>
            </div>
            <div>
                <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 10px;"><?= number_format($total_bookings) ?>+</div>
                <div style="font-size: 1.1em; opacity: 0.9;">Successful Bookings</div>
            </div>
            <div>
                <div style="font-size: 2.5em; font-weight: bold; margin-bottom: 10px;">4.8★</div>
                <div style="font-size: 1.1em; opacity: 0.9;">Average Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div style="text-align: center; max-width: 600px; margin: 0 auto;">
            <h2>Ready to Find Your Hostel?</h2>
            <p style="font-size: 1.1em; color: #666; margin-bottom: 30px;">
                Browse our verified hostels and book your accommodation today.
            </p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="<?= APP_URL ?>/hostels.php" class="btn btn-primary btn-lg">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="Browse"> Browse Hostels
                </a>
                <a href="<?= APP_URL ?>/contact.php" class="btn btn-outline btn-lg">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/phone.svg" alt="Contact"> Get in Touch
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>

