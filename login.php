<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (student_logged_in()) {
    redirect('dashboard.php');
}

$errors = [];
$old = ['email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session has expired. Please try again.';
    } else {
        $old['email'] = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // ---- Simple brute-force protection -------------------------
        // We count failed login attempts in the session. After 5
        // failed tries, we make the visitor wait before trying again.
        // This slows down attackers who try to guess a password by
        // trying thousands of combinations automatically.
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
        $_SESSION['login_locked_until'] = $_SESSION['login_locked_until'] ?? 0;

        if (time() < $_SESSION['login_locked_until']) {
            $wait = $_SESSION['login_locked_until'] - time();
            $errors[] = "Too many failed attempts. Please wait {$wait} seconds and try again.";
        } elseif ($old['email'] === '' || $password === '') {
            $errors[] = 'Please enter your email and password.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM students WHERE email = :email');
            $stmt->execute([':email' => $old['email']]);
            $student = $stmt->fetch();

            // password_verify() checks the typed password against the
            // stored hash without ever needing to know the real
            // password. We show the SAME error for "no such email" and
            // "wrong password" on purpose, so an attacker cannot learn
            // which emails are registered on our site.
            if (!$student || !password_verify($password, $student['password_hash'])) {
                $_SESSION['login_attempts']++;
                if ($_SESSION['login_attempts'] >= 5) {
                    $_SESSION['login_locked_until'] = time() + 60;
                    $_SESSION['login_attempts'] = 0;
                }
                $errors[] = 'Incorrect email or password.';
            } elseif ($student['status'] === 'blocked') {
                $errors[] = 'This account has been blocked. Please contact the admin.';
            } else {
                $_SESSION['login_attempts'] = 0;
                $_SESSION['student_id'] = (int) $student['id'];
                $_SESSION['student_name'] = $student['full_name'];
                session_regenerate_id(true);

                set_flash('success', 'Welcome back, ' . $student['full_name'] . '!');
                redirect('dashboard.php');
            }
        }
    }
}

$page_title = 'Log In';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Welcome Back</h2>
            <p>Log in with the email and password you used when you enrolled.</p>
        </div>

        <div class="form-wrap reveal">
            <?php if ($errors): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <div><?= clean($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="login.php" data-validate novalidate>
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required value="<?= clean($old['email']) ?>">
                    <div class="field-error" id="email-error"></div>
                </div>

                <div class="form-row">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" required>
                        <button type="button" class="toggle-password" data-target="password" aria-label="Show password">👁️</button>
                    </div>
                    <div class="field-error" id="password-error"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>

            <p class="small-note">New here? <a href="register.php" style="color:var(--color-navy);font-weight:700;">Create an account</a></p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
