<?php
/* ================================================
   index.php - Hostel Mate Landing Page
   ================================================ */
require_once 'config.php';
$page_title = 'Find Student Hostels Near MUST Campus';
include 'includes/head.php';

// --- Fetch featured hostels ---
$db = getDB();
$featured_sql  = "SELECT * FROM hostels WHERE availability > 0 ORDER BY created_at DESC LIMIT 6";
$featured_res  = $db->query($featured_sql);
$featured      = $featured_res ? $featured_res->fetch_all(MYSQLI_ASSOC) : [];

// --- Total counts for stats ---
$total_hostels  = $db->query("SELECT COUNT(*) as c FROM hostels")->fetch_assoc()['c'] ?? 0;
$total_students = $db->query("SELECT COUNT(*) as c FROM users WHERE role='student'")->fetch_assoc()['c'] ?? 0;
$total_bookings = $db->query("SELECT COUNT(*) as c FROM bookings WHERE status='confirmed'")->fetch_assoc()['c'] ?? 0;

// Fallback demo numbers
if ($total_hostels  < 3)  $total_hostels  = 24;
if ($total_students < 10) $total_students = 350;
if ($total_bookings < 5)  $total_bookings = 180;
?>
<?php include 'includes/navbar.php'; ?>

<!-- ========================
     HERO SECTION
     ======================== -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Trusted by MUST Students
            </div>
            <h1>
                Find Your Perfect<br>
                <em>Student Hostel</em> Near<br>
                any Campus in Mbarara
            </h1>
            <p>
                Browse verified, affordable hostels around Mbarara University. 
                Filter by room type, price, and distance from campus. 
                Book securely in minutes.
            </p>
            <div class="hero-actions">
                <a href="hostels.php" class="btn btn-primary btn-lg">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostels"> Browse Hostels
                </a>
                <?php if (!isLoggedIn()): ?>
                    <a href="signup.php" class="btn btn-outline btn-lg">Create Account</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn btn-outline btn-lg">My Dashboard</a>
                <?php endif; ?>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-value"><?= $total_hostels ?>+</div>
                    <div class="hero-stat-label">Listed Hostels</div>
                </div>
                <div>
                    <div class="hero-stat-value"><?= $total_students ?>+</div>
                    <div class="hero-stat-label">Students Served</div>
                </div>
                <div>
                    <div class="hero-stat-value"><?= $total_bookings ?>+</div>
                    <div class="hero-stat-label">Bookings Made</div>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-card-stack">
                <div class="hero-card-bg"></div>
                <div class="hero-card-bg"></div>
                <div class="hero-card-main">
                    <div class="hero-card-img">
                        <img src="<?= APP_URL ?>/assets/img/hostels/image1.jpg" alt="Hostel" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <div class="hero-card-body">
                        <div class="hero-card-title">Pearl Hostel</div>
                        <div class="hero-card-meta">
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> 0.3 km from MUST Gate
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span class="hero-card-price">UGX 850,000 / sem</span>
                            <span class="badge badge-green">Available</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================
     QUICK SEARCH
     ======================== -->
<section class="search-section">
    <div class="container">
        <form method="GET" action="hostels.php" class="search-form">
            <div class="search-group">
                <label>Search Hostel</label>
                <input type="text" name="search" class="form-control" placeholder="Hostel name or location...">
            </div>
            <div class="search-group" style="max-width:180px;">
                <label>Room Type</label>
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    <option value="single">Single Room</option>
                    <option value="shared">Shared Room</option>
                    <option value="self-contained">Self-Contained</option>
                </select>
            </div>
            <div class="search-group" style="max-width:180px;">
                <label>Max Price (UGX)</label>
                <select name="max_price" class="form-control">
                    <option value="">Any Price</option>
                    <option value="900000">Under 900,000</option>
                    <option value="1000000">Under 1,000,000</option>
                    <option value="1100000">Under 1,100,000</option>
                    <option value="1300000">Under 1,300,000</option>
                </select>
            </div>
            <div class="search-group" style="max-width:180px;">
                <label>Availability</label>
                <select name="available" class="form-control">
                    <option value="">All Hostels</option>
                    <option value="1">Available Only</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary" style="margin-top:24px;">
                    <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/search.svg" alt="Search"> Search
                </button>
            </div>
        </form>
    </div>
</section>

<!-- ========================
     HOW IT WORKS
     ======================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Simple Process</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get into your ideal hostel in 4 easy steps</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><img src="<?= APP_URL ?>/assets/svg/search.svg" alt="Search"></div>
                <div class="feature-title">1. Search & Filter</div>
                <p class="feature-text">Browse all available hostels and filter by room type, price range, and distance from campus.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><img src="<?= APP_URL ?>/assets/svg/building.svg" alt="View Details"></div>
                <div class="feature-title">2. View Details</div>
                <p class="feature-text">Check hostel photos, amenities, pricing, room availability, and contact information.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><img src="<?= APP_URL ?>/assets/svg/calendar.svg" alt="Book a Room"></div>
                <div class="feature-title">3. Book a Room</div>
                <p class="feature-text">Create an account, log in, and book your preferred room with instant confirmation.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><img src="<?= APP_URL ?>/assets/svg/check.svg" alt="Move In"></div>
                <div class="feature-title">4. Move In!</div>
                <p class="feature-text">Receive your booking confirmation and get in touch with the hostel owner to finalize.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========================
     FEATURED HOSTELS
     ======================== -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Featured</span>
            <h2 class="section-title">Available Hostels Near Campus</h2>
            <p class="section-subtitle">Verified and student-approved accommodation around MUST</p>
        </div>

        <?php if (empty($featured)): ?>
            <!-- Demo cards if DB is empty -->
            <div class="hostels-grid">
                <?php
                $demo = [
                    ['Pearl Student Hostel',   'Kakoba, 0.3km from MUST',  'Single Room',       850000, 'image1.jpg', true],
                    ['Unity Residence',        'Ruharo, 0.5km from MUST',  'Shared Room',       900000, 'image2.jpg.png', true],
                    ['Comfort Suites',         'Kakoba, 0.4km from MUST',  'Self-Contained',    1200000, 'image3.jpg.png', true],
                    ['Nile View Hostel',       'Kamukuzi, 0.6km from MUST','Single Room',       950000, 'image4.jpeg', false],
                    ['Campus Gate Residence',  'Kakoba, 0.2km from MUST',  'Shared Room',       880000, 'image5.jpg', true],
                    ['Green Valley Hostel',    'Biharwe, 0.8km from MUST', 'Self-Contained',    1150000, 'image6.jpg', true],
                ];
                foreach ($demo as $h): ?>
                <div class="hostel-card">
                    <div class="hostel-card-img">
                        <?php if (file_exists('assets/img/hostels/' . $h[4])): ?>
                            <img src="<?= APP_URL ?>/assets/img/hostels/<?= sanitize($h[4]) ?>" alt="<?= sanitize($h[0]) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <?php else: ?>
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel" style="width: 3.5rem; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="hostel-card-body">
                        <div class="hostel-card-badges">
                            <span class="badge badge-blue"><?= $h[2] ?></span>
                            <span class="badge <?= $h[5] ? 'badge-green' : 'badge-red' ?>"><?= $h[5] ? 'Available' : 'Full' ?></span>
                        </div>
                        <div class="hostel-card-title"><?= $h[0] ?></div>
                        <div class="hostel-card-location">
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= $h[1] ?>
                        </div>
                        <div class="hostel-card-footer">
                            <div class="hostel-price"><?= formatPrice($h[3]) ?> <span>/ semester</span></div>
                            <a href="login.php" class="btn btn-primary btn-sm">Book</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="hostels-grid">
                <?php foreach ($featured as $h): ?>
                <div class="hostel-card">
                    <div class="hostel-card-img">
                        <?php if (!empty($h['image']) && file_exists('uploads/hostels/' . $h['image'])): ?>
                            <img src="<?= APP_URL ?>/uploads/hostels/<?= sanitize($h['image']) ?>" alt="<?= sanitize($h['hostel_name']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <?php elseif (!empty($h['image']) && file_exists('assets/img/hostels/' . $h['image'])): ?>
                            <img src="<?= APP_URL ?>/assets/img/hostels/<?= sanitize($h['image']) ?>" alt="<?= sanitize($h['hostel_name']) ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        <?php else: ?>
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/building.svg" alt="Hostel" style="width: 3.5rem; height: auto;">
                        <?php endif; ?>
                    </div>
                    <div class="hostel-card-body">
                        <div class="hostel-card-badges">
                            <span class="badge badge-blue"><?= sanitize(ucfirst($h['room_type'])) ?></span>
                            <span class="badge <?= $h['availability'] > 0 ? 'badge-green' : 'badge-red' ?>">
                                <?= $h['availability'] > 0 ? 'Available' : 'Full' ?>
                            </span>
                        </div>
                        <div class="hostel-card-title"><?= sanitize($h['hostel_name']) ?></div>
                        <div class="hostel-card-location">
                            <img class="icon-svg" src="<?= APP_URL ?>/assets/svg/map-pin.svg" alt="Location"> <?= sanitize($h['location']) ?>
                        </div>
                        <div class="hostel-card-footer">
                            <div class="hostel-price"><?= formatPrice($h['price']) ?> <span>/ sem</span></div>
                            <a href="hostel-details.php?id=<?= (int)$h['id'] ?>" class="btn btn-primary btn-sm">View</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-24">
            <a href="hostels.php" class="btn btn-outline btn-lg">View All Hostels →</a>
        </div>
    </div>
</section>

<!-- ========================
     TESTIMONIALS
     ======================== -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Student Reviews</span>
            <h2 class="section-title">What Students Say</h2>
            <p class="section-subtitle">Hear from students who found their hostel through our platform</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"I found a great self-contained room just 5 minutes from the library. The booking process was so smooth and easy. Highly recommend!"</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AK</div>
                    <div>
                        <div class="testimonial-name">Aisha Karungi</div>
                        <div class="testimonial-course">BSc Computer Science, Year 2</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★★</div>
                <p class="testimonial-text">"As a first-year student from upcountry, I was stressed about accommodation. This platform made it super easy to find a safe shared room."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">BM</div>
                    <div>
                        <div class="testimonial-name">Brian Mugisha</div>
                        <div class="testimonial-course">MBBS Medicine, Year 1</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">★★★★☆</div>
                <p class="testimonial-text">"The filtering by price range was very helpful. I found a hostel within my budget quickly. The hostel details page is very informative."</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">PN</div>
                    <div>
                        <div class="testimonial-name">Patience Nakato</div>
                        <div class="testimonial-course">BBA Business, Year 3</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================
     CTA SECTION
     ======================== -->
<section class="cta-section">
    <div class="container">
        <h2>Ready to Find Your Hostel?</h2>
        <p>Join hundreds of MUST students who use our platform every semester.</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            <a href="hostels.php" class="btn btn-lg" style="background:white;color:var(--primary);font-weight:700;">Browse Hostels</a>
            <?php if (!isLoggedIn()): ?>
                <a href="signup.php" class="btn btn-lg btn-outline" style="border-color:rgba(255,255,255,0.5);color:white;">Create Free Account</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>

