<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$courses = $pdo->query('SELECT * FROM courses ORDER BY id ASC')->fetchAll();

$page_title = 'Courses';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>Our Technical Courses</h2>
            <p>Every course is taught step by step, in easy words, with plenty of hands-on practice.</p>
        </div>

        <div class="grid grid-3">
            <?php foreach ($courses as $course): ?>
                <div class="card reveal">
                    <span class="icon"><?= clean($course['icon']) ?></span>
                    <h3><?= clean($course['title']) ?></h3>
                    <p><?= clean($course['short_description']) ?></p>
                    <p style="margin-top:10px;font-size:0.95rem;"><?= clean($course['full_description']) ?></p>
                    <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
                        <span class="level-tag"><?= clean($course['level']) ?></span>
                        <span class="level-tag">⏱ <?= (int) $course['duration_weeks'] ?> weeks</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-2">
            <a href="register.php" class="btn btn-primary">Enroll in a Course</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
