<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_student_login();

// Always re-fetch fresh data from the database - never trust only
// what is stored in the session, in case details were updated.
$stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
$stmt->execute([':id' => $_SESSION['student_id']]);
$student = $stmt->fetch();

if (!$student) {
    // The account may have been deleted by the admin - log the
    // visitor out safely instead of showing a broken page.
    redirect('logout.php');
}

$page_title = 'My Account';
require __DIR__ . '/includes/header.php';
?>

<section class="dashboard-header">
    <div class="container">
        <h1>Welcome, <?= clean($student['full_name']) ?> 👋</h1>
        <p style="color:#cbd5e6;">Here are the details on your Silver Bytes Academy account.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-4" style="margin-bottom:10px;">
            <div class="card reveal">
                <span class="icon">👤</span>
                <h3>Full Name</h3>
                <p><?= clean($student['full_name']) ?></p>
            </div>
            <div class="card reveal">
                <span class="icon">✉️</span>
                <h3>Email</h3>
                <p><?= clean($student['email']) ?></p>
            </div>
            <div class="card reveal">
                <span class="icon">📞</span>
                <h3>Phone</h3>
                <p><?= clean($student['phone']) ?></p>
            </div>
            <div class="card reveal">
                <span class="icon">🎓</span>
                <h3>Course Interest</h3>
                <p><?= $student['course_interest'] ? clean($student['course_interest']) : 'Not chosen yet' ?></p>
            </div>
        </div>

        <div class="card reveal" style="margin-top:20px;">
            <h3>Need to change your details?</h3>
            <p>To update your information or reset your password, please contact the admin - they will help you personally and safely.</p>
            <div style="margin-top:16px;display:flex;gap:14px;flex-wrap:wrap;">
                <a href="https://wa.me/21369732938" target="_blank" rel="noopener noreferrer" class="btn btn-secondary">📞 WhatsApp Admin</a>
                <a href="mailto:wadhaja25597@gmail.com" class="btn btn-secondary">✉️ Email Admin</a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
