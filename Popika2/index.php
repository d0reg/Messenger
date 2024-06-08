<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="waves"></div>
<div class="bubbles">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
</div>
<div class="slider-container">
    <div class="slider">
        <div class="slide active">
            <h3 align="center">История</h3>
            <p align="center">ProComChat был создан в 2023 году командой энтузиастов, которые хотели улучшить корпоративное общение. Они заметили, что многие компании сталкиваются с проблемами в коммуникации из-за использования различных платформ, что приводило к путанице и потере информации. ProComChat был разработан как единое решение для всех корпоративных коммуникационных потребностей, предлагая интегрированные инструменты для обмена сообщениями, видеоконференций и управления проектами.</p>
        </div>
        <div class="slide">
            <h3>Slide 2</h3>
            <p>This is the second slide.</p>
        </div>
        <div class="slide">
            <h3>Slide 3</h3>
            <p>This is the third slide.</p>
        </div>
    </div>
    <div class="slider-nav">
        <button class="prev">Previous</button>
        <button class="next">Next</button>
    </div>
</div>
<form method="post" action="login_process.php">
    <h2>Login</h2>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Login</button>
    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    document.querySelector('.next').addEventListener('click', function() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % totalSlides;
        slides[currentSlide].classList.add('active');
    });

    document.querySelector('.prev').addEventListener('click', function() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        slides[currentSlide].classList.add('active');
    });
});
</script>
</body>
</html>
