<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Show the 3 newest courses on the homepage.
$stmt = $pdo->query('SELECT id, title, short_description, icon, level FROM courses ORDER BY id ASC LIMIT 3');
$featuredCourses = $stmt->fetchAll();

// Count active students, just to show a friendly number in the hero.
$studentCount = (int) $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

$page_title = 'Home';
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container hero-inner">
        <div>
            <h1>Technology training made <span class="highlight">simple</span> for elders</h1>
            <p>Learn smartphones, computers, and the internet step by step, at your own speed, with patient teachers and friendly classmates.</p>
            <div class="hero-actions">
                <a href="register.php" class="btn btn-primary">Enroll Now</a>
                <a href="courses.php" class="btn btn-secondary" style="color:#fff;border-color:#fff;">See Our Courses</a>
            </div>
        </div>
        <div class="hero-badge">
            <div class="num"><?= $studentCount > 0 ? $studentCount : '50' ?>+</div>
            <div>Students already learning with us</div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Why choose Silver Bytes Academy?</h2>
            <p>We built this academy only for elders and older people who want to learn technology without feeling rushed or judged.</p>
        </div>
        <div class="grid grid-3">
            <div class="card reveal">
                <span class="icon">🐢</span>
                <h3>Slow & Simple</h3>
                <p>Every lesson goes step by step, in easy words, with plenty of time to practice and ask questions.</p>
            </div>
            <div class="card reveal">
                <span class="icon">🤝</span>
                <h3>Patient Teachers</h3>
                <p>Our teachers repeat things gladly and never make you feel rushed. There are no silly questions here.</p>
            </div>
            <div class="card reveal">
                <span class="icon">🔒</span>
                <h3>Safe & Private</h3>
                <p>Your personal details are protected, and we teach you how to stay safe online from scams and fraud.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-title reveal">
            <h2>Popular Courses</h2>
            <p>A few of the technical courses our students enjoy the most.</p>
        </div>
        <div class="grid grid-3">
            <?php foreach ($featuredCourses as $course): ?>
                <div class="card reveal">
                    <span class="icon"><?= clean($course['icon']) ?></span>
                    <h3><?= clean($course['title']) ?></h3>
                    <p><?= clean($course['short_description']) ?></p>
                    <span class="level-tag"><?= clean($course['level']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-2">
            <a href="courses.php" class="btn btn-secondary">View All Courses</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>How to Join</h2>
            <p>Getting started only takes three simple steps.</p>
        </div>
        <div class="grid grid-3">
            <div class="card reveal text-center">
                <div class="step-num" style="margin:0 auto 14px;">1</div>
                <h3>Create an Account</h3>
                <p>Fill in the short enrollment form with your details.</p>
            </div>
            <div class="card reveal text-center">
                <div class="step-num" style="margin:0 auto 14px;">2</div>
                <h3>Log In</h3>
                <p>Use your email and password any time to sign in.</p>
            </div>
            <div class="card reveal text-center">
                <div class="step-num" style="margin:0 auto 14px;">3</div>
                <h3>Start Learning</h3>
                <p>Our team will contact you to arrange your first class.</p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
