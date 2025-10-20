<?php
session_start();
include "../db.php";
include "ad-menu.php";

$msg = "";
$msg_type = "info";

// ✅ Message form submission
if (isset($_POST['send_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $roll = mysqli_real_escape_string($conn, $_POST['roll']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if ($name && $roll && $email && $subject && $message) {
        $query = "INSERT INTO contact_messages (name, roll, email, subject, message)
                  VALUES ('$name', '$roll', '$email', '$subject', '$message')";
        if (mysqli_query($conn, $query)) {
            $msg = "✅ Message sent successfully!";
            $msg_type = "success";
        } else {
            $msg = "❌ Error: " . mysqli_error($conn);
            $msg_type = "danger";
        }
    } else {
        $msg = "⚠️ Please fill all the fields!";
        $msg_type = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Library</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">



<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
            <h3>📩 Contact Us</h3>
            <p class="mb-0">Have any questions? Send us a message.</p>
        </div>
        <div class="card-body p-4">

            <?php if ($msg): ?>
                <div class="alert alert-<?php echo $msg_type; ?> text-center">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="needs-validation" novalidate>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Roll</label>
                        <input type="text" name="roll" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" required></textarea>
                </div>

                <div class="text-center">
                    <button type="submit" name="send_message" class="btn btn-primary px-4">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="text-center mt-5 text-muted">
    &copy; <?= date('Y') ?> Library Management System
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
