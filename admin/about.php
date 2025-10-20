<?php 
include "ad-menu.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>About Library</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background-color: #f8f9fa;
    font-family: "Arial", sans-serif;
}
.header-section {
    background-image: url(../images/li2.jpg);
    height: 70vh;
    background-size:cover;
    color: white;
    padding: 50px 0;
    text-align: center;
}
.header-section h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}
.header-section p {
    font-size: 1.2rem;
}
.content-section {
    padding: 50px 15px;
}
.content-section h2 {
    margin-bottom: 20px;
    color: #0d6efd;
}
.content-section p {
    font-size: 1rem;
    line-height: 1.6;
}
.feature-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: white;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>
</head>
<body container  style="font-family: 'Times New Roman', Times, serif;">

<!-- Header -->
<div class="header-section container">
    <h1>Our Library</h1>
    <p>Knowledge is power. Explore, Learn, and Grow.</p>
</div>

<!-- Content -->
<div class="container content-section">
    <h2>About Us</h2>
    <p>
        Welcome to <strong>KHANJAHAN Ali Library Management System</strong>. Established in <em>Year</em>, our library has been providing 
        access to a wide collection of books, journals, and digital resources to support education, research, and personal development.
    </p>

    <h2>Our Mission</h2>
    <p>
        Our mission is to provide a friendly and resourceful environment for readers of all ages. We aim to promote literacy, 
        encourage lifelong learning, and make information accessible to everyone.
    </p>

    <h2>Facilities</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="feature-card text-center">
                <h5>Extensive Collection</h5>
                <p>Thousands of books across multiple genres including fiction, non-fiction, academic, and reference materials.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <h5>Digital Access</h5>
                <p>Access e-books, journals, and other digital content online from anywhere.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="feature-card text-center">
                <h5>Reading Spaces</h5>
                <p>Quiet and comfortable reading areas for students, researchers, and book lovers.</p>
            </div>
        </div>
    </div>

    <h2>Contact Us</h2>
    <p>
        Address: 123 Library Street, City, Country<br>
        Phone: +880 1234 567890<br>
        Email: info@yourlibrary.com
    </p>
    <?php include "../inc/copyright.php";?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
