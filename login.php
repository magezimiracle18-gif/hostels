<?php
/* ================================================
   login.php - User Login
   ================================================ */
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

$errors    = [];
$email_val = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $email_val = $email;

    if (empty($email) || empty($password)) {
        $errors[] = 'Please enter both email and password.';
    } else {
        $db = getDB();

        // --- Try users table first ---
        $stmt = $db->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res  = $stmt->get_result();
        $user = $res->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // --- Set session ---
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email']    = $user['email'];
            $_SESSION['role']     = $user['role'];

            // --- Redirect ---
            $redirect = $_GET['redirect'] ?? '';
            if ($redirect && strpos($redirect, APP_URL) === 0) {
                header('Location: ' . $redirect);
            } elseif ($user['role'] === 'admin') {
                header('Location: ' . APP_URL . '/admin/index.php');
            } else {
                header('Location: ' . APP_URL . '/dashboard.php');
            }
            exit;
        } else {
            $errors[] = 'Invalid email or password. Please try again.';
        }
    }
}

$page_title = 'Login';
include 'includes/head.php';
?>
<?php include 'includes/navbar.php'; ?>

<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="nav-brand-icon" style="width:50px;height:50px;font-size:22px;border-radius:14px;margin:0 auto 10px;">M</div>
        </div>
        <h1 class="auth-title">Welcome Back</h1>
        <p class="auth-subtitle">Log in to manage your hostel bookings</p>

        <?php renderFlash(); ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                ❌ <?= $errors[0] ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control"
                    placeholder="you@example.com"
                    value="<?= sanitize($email_val) ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                    placeholder="Your password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:8px;">
                Log In →
            </button>
        </form>

        <div class="auth-divider"><span>or</span></div>

        <div class="text-center" style="font-size:0.82rem;color:var(--text-mid);">
            Demo Admin: <strong>admin@must.ac.ug</strong> / <strong>admin123</strong>
        </div>

        <div class="auth-footer">
            Don't have an account? <a href="signup.php">Sign up free</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
