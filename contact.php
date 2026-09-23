<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$old = ['name' => '', 'email' => '', 'message' => ''];

// This block only runs when the visitor submits the form (POST request).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check the CSRF token first. See includes/functions.php for what
    // a CSRF token is and why every form on this site uses one.
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session has expired. Please try again.';
    } else {
        $old['name'] = trim($_POST['name'] ?? '');
        $old['email'] = trim($_POST['email'] ?? '');
        $old['message'] = trim($_POST['message'] ?? '');

        if ($old['name'] === '') $errors[] = 'Please enter your name.';
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
        if ($old['message'] === '') $errors[] = 'Please write your message.';

        if (empty($errors)) {
            // A "prepared statement": the SQL text and the real values
            // are sent to MySQL separately, which blocks SQL injection.
            $stmt = $pdo->prepare(
                'INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)'
            );
            $stmt->execute([
                ':name' => $old['name'],
                ':email' => $old['email'],
                ':message' => $old['message'],
            ]);

            set_flash('success', 'Thank you! Your message has been sent. We will contact you soon.');
            redirect('contact.php');
        }
    }
}

$page_title = 'Contact';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Contact Us</h2>
            <p>We are happy to answer any question. Choose the way that is easiest for you.</p>
        </div>

        <div class="contact-grid">
            <div class="reveal">
                <a href="https://wa.me/21369732938" target="_blank" rel="noopener noreferrer" class="contact-card">
                    <span class="icon">📞</span>
                    <div>
                        <h3>WhatsApp</h3>
                        <p>+213 69 73 29 38</p>
                    </div>
                </a>
                <a href="mailto:wadhaja25597@gmail.com" class="contact-card">
                    <span class="icon">✉️</span>
                    <div>
                        <h3>Email</h3>
                        <p>wadhaja25597@gmail.com</p>
                    </div>
                </a>
                <div class="contact-card">
                    <span class="icon">🕒</span>
                    <div>
                        <h3>Reply Time</h3>
                        <p>We usually reply within one day.</p>
                    </div>
                </div>
            </div>

            <div class="form-wrap reveal">
                <?php if ($errors): ?>
                    <div class="error-box">
                        <?php foreach ($errors as $error): ?>
                            <div><?= clean($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="contact.php" data-validate novalidate>
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

                    <div class="form-row">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required value="<?= clean($old['name']) ?>">
                        <div class="field-error" id="name-error"></div>
                    </div>

                    <div class="form-row">
                        <label for="email">Your Email</label>
                        <input type="email" id="email" name="email" required value="<?= clean($old['email']) ?>">
                        <div class="field-error" id="email-error"></div>
                    </div>

                    <div class="form-row">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" required><?= clean($old['message']) ?></textarea>
                        <div class="field-error" id="message-error"></div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
