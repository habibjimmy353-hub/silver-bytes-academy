<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// If a student is already logged in, sending them back to the
// register page makes no sense - send them to their dashboard.
if (student_logged_in()) {
    redirect('dashboard.php');
}

$errors = [];
$old = ['full_name' => '', 'email' => '', 'phone' => '', 'age' => '', 'gender' => 'other', 'address' => '', 'course_interest' => ''];

$courses = $pdo->query('SELECT title FROM courses ORDER BY title ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session has expired. Please refresh the page and try again.';
    } else {
        // Collect and clean every field the student sent.
        $old['full_name'] = trim($_POST['full_name'] ?? '');
        $old['email'] = trim($_POST['email'] ?? '');
        $old['phone'] = trim($_POST['phone'] ?? '');
        $old['age'] = trim($_POST['age'] ?? '');
        $old['gender'] = trim($_POST['gender'] ?? 'other');
        $old['address'] = trim($_POST['address'] ?? '');
        $old['course_interest'] = trim($_POST['course_interest'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // ---- Server-side validation --------------------------------
        // We ALWAYS re-check everything here in PHP, even though the
        // browser already checked it with JavaScript, because a
        // visitor could turn JavaScript off or send data directly.
        if ($old['full_name'] === '' || mb_strlen($old['full_name']) < 3) {
            $errors[] = 'Please enter your full name (at least 3 letters).';
        }
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }
        if ($old['phone'] === '' || !preg_match('/^[0-9+\s-]{8,20}$/', $old['phone'])) {
            $errors[] = 'Please enter a valid phone number.';
        }
        if ($old['age'] === '' || !ctype_digit($old['age']) || (int) $old['age'] < 16 || (int) $old['age'] > 120) {
            $errors[] = 'Please enter a valid age (16 or older).';
        }
        if ($old['address'] === '') {
            $errors[] = 'Please enter your address.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Your password must be at least 8 characters long.';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        // Check the email is not already registered.
        if (empty($errors)) {
            $check = $pdo->prepare('SELECT id FROM students WHERE email = :email');
            $check->execute([':email' => $old['email']]);
            if ($check->fetch()) {
                $errors[] = 'This email is already registered. Please log in instead.';
            }
        }

        if (empty($errors)) {
            // password_hash() turns the password into a secure, one-way
            // scrambled value. Even the admin can never see the real
            // password again - only password_verify() can check it.
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                'INSERT INTO students (full_name, email, phone, age, gender, address, password_hash, course_interest)
                 VALUES (:full_name, :email, :phone, :age, :gender, :address, :password_hash, :course_interest)'
            );
            $stmt->execute([
                ':full_name' => $old['full_name'],
                ':email' => $old['email'],
                ':phone' => $old['phone'],
                ':age' => (int) $old['age'],
                ':gender' => in_array($old['gender'], ['male', 'female', 'other'], true) ? $old['gender'] : 'other',
                ':address' => $old['address'],
                ':password_hash' => $hash,
                ':course_interest' => $old['course_interest'] !== '' ? $old['course_interest'] : null,
            ]);

            // Log the new student in immediately, and remember their
            // ID and name in the session. regenerate_id prevents a
            // "session fixation" attack (an old session ID being reused).
            $_SESSION['student_id'] = (int) $pdo->lastInsertId();
            $_SESSION['student_name'] = $old['full_name'];
            session_regenerate_id(true);

            set_flash('success', 'Welcome, ' . $old['full_name'] . '! Your account was created successfully.');
            redirect('dashboard.php');
        }
    }
}

$page_title = 'Enroll Now';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Create Your Account</h2>
            <p>Fill in your details below. It only takes a few minutes. If you already have an account, please <a href="login.php" style="color:var(--color-navy);font-weight:700;">log in here</a> instead.</p>
        </div>

        <div class="form-wrap wide reveal">
            <?php if ($errors): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <div><?= clean($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="register.php" data-validate novalidate autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                <div class="form-row two-col">
                    <div>
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required value="<?= clean($old['full_name']) ?>">
                        <div class="field-error" id="full_name-error"></div>
                    </div>
                    <div>
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" min="16" max="120" required value="<?= clean($old['age']) ?>">
                        <div class="field-error" id="age-error"></div>
                    </div>
                </div>

                <div class="form-row two-col">
                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?= clean($old['email']) ?>">
                        <div class="field-error" id="email-error"></div>
                    </div>
                    <div>
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required value="<?= clean($old['phone']) ?>">
                        <div class="field-error" id="phone-error"></div>
                    </div>
                </div>

                <div class="form-row two-col">
                    <div>
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="male" <?= $old['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                            <option value="female" <?= $old['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                            <option value="other" <?= $old['gender'] === 'other' ? 'selected' : '' ?>>Prefer not to say</option>
                        </select>
                    </div>
                    <div>
                        <label for="course_interest">Course You Are Interested In</label>
                        <select id="course_interest" name="course_interest">
                            <option value="">Not sure yet</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= clean($course['title']) ?>" <?= $old['course_interest'] === $course['title'] ? 'selected' : '' ?>>
                                    <?= clean($course['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="address">Home Address</label>
                    <input type="text" id="address" name="address" required value="<?= clean($old['address']) ?>">
                    <div class="field-error" id="address-error"></div>
                </div>

                <div class="form-row two-col">
                    <div>
                        <label for="password">Password</label>
                        <div class="password-wrap">
                            <input type="password" id="password" name="password" required minlength="8">
                            <button type="button" class="toggle-password" data-target="password" aria-label="Show password">👁️</button>
                        </div>
                        <div class="hint">At least 8 characters.</div>
                        <div class="field-error" id="password-error"></div>
                    </div>
                    <div>
                        <label for="confirm_password">Confirm Password</label>
                        <div class="password-wrap">
                            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                            <button type="button" class="toggle-password" data-target="confirm_password" aria-label="Show password">👁️</button>
                        </div>
                        <div class="field-error" id="confirm_password-error"></div>
                    </div>
                </div>

                <p class="hint" style="margin-bottom:18px;">
                    By creating an account, you agree to our <a href="policy.php" style="color:var(--color-navy);font-weight:700;">Website Policy</a>.
                </p>

                <button type="submit" class="btn btn-primary btn-block">Create My Account</button>
            </form>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
