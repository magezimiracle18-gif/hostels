<?php
/* ================================================
   signup.php - User Registration
   ================================================ */
require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

$errors = [];
$values = ['fullname' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Collect & sanitize inputs ---
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm']       ?? '';

    $values = ['fullname' => $fullname, 'email' => $email, 'phone' => $phone];

    // --- Validate ---
    if (empty($fullname))                        $errors[] = 'Full name is required.';
    if (strlen($fullname) < 3)                   $errors[] = 'Full name must be at least 3 characters.';
    if (empty($email))                           $errors[] = 'Email address is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (empty($phone))                           $errors[] = 'Phone number is required.';
    if (!preg_match('/^[0-9+\s\-]{10,15}$/', $phone)) $errors[] = 'Enter a valid phone number.';
    if (empty($password))                        $errors[] = 'Password is required.';
    if (strlen($password) < 6)                   $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm)                  $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $db = getDB();

        // --- Check duplicate email ---
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = 'An account with this email already exists. <a href="login.php">Login instead</a>.';
        } else {
            // --- Hash password & insert user ---
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'student';

            $ins = $db->prepare("INSERT INTO users (fullname, email, phone, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $ins->bind_param('sssss', $fullname, $email, $phone, $hash, $role);

            if ($ins->execute()) {
                setFlash('success', 'Account created successfully! Please log in.');
                header('Location: ' . APP_URL . '/login.php');
                exit;
            } else {
                $errors[] = 'Registration failed. Please try again.';
            }
        }
    }
}

$page_title = 'Create Account';
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="nav-brand-icon" style="width:50px;height:50px;font-size:22px;border-radius:14px;margin:0 auto 10px;">M</div>
        </div>
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Join Hostel Mate and find your ideal room</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                ❌ <div>
                    <?php foreach ($errors as $e): ?>
                        <div><?= $e ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="signup.php" novalidate>
            <div class="form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" class="form-control"
                    placeholder="e.g. Aisha Karungi"
                    value="<?= sanitize($values['fullname']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="you@example.com"
                    value="<?= sanitize($values['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control"
                    placeholder="+256 700 000 000"
                    value="<?= sanitize($values['phone']) ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Min. 6 characters" required>
                </div>
                <div class="form-group">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" class="form-control"
                        placeholder="Repeat password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:8px;">
                Create My Account →
            </button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="login.php">Log in here</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

