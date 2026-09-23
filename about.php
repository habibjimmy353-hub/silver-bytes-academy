<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = 'About Us';
require __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-title reveal">
            <h2>About Silver Bytes Academy</h2>
            <p>A warm and patient place to learn technology, built especially for elders and older people.</p>
        </div>

        <div class="grid grid-3" style="align-items:start;">
            <div class="card reveal">
                <span class="icon">🎯</span>
                <h3>Our Mission</h3>
                <p>To help elders feel confident using smartphones, computers, and the internet, so they can stay connected with family, manage daily tasks, and enjoy technology without fear.</p>
            </div>
            <div class="card reveal">
                <span class="icon">👵👴</span>
                <h3>Who We Teach</h3>
                <p>Anyone who is older and new to technology, or who wants a gentle refresher. No experience is required at all — we start from zero.</p>
            </div>
            <div class="card reveal">
                <span class="icon">🌟</span>
                <h3>Our Promise</h3>
                <p>Small classes, simple language, large text, and teachers who are always happy to repeat a step as many times as needed.</p>
            </div>
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="section-title reveal">
            <h2>What Makes Us Different</h2>
        </div>
        <div class="steps reveal" style="max-width:760px;margin:0 auto;">
            <div class="step">
                <div class="step-num">1</div>
                <div>
                    <h3>No Rushing</h3>
                    <p>Every class moves at a comfortable pace. We repeat and practice until everyone feels sure.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div>
                    <h3>Real-Life Skills</h3>
                    <p>We teach things people actually use every day: calling family, sending photos, and staying safe online.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div>
                    <h3>A Caring Community</h3>
                    <p>Students learn together and support each other, building both skills and friendships.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section text-center reveal">
    <div class="container">
        <h2>Ready to begin?</h2>
        <p style="max-width:520px;margin:14px auto 26px;">Join Silver Bytes Academy today and take your first step into the digital world.</p>
        <a href="register.php" class="btn btn-primary">Enroll Now</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
