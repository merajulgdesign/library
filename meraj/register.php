<?php
include "db.php";
include "inc/menubar.php";
include "inc/link-head.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name']);
    $roll = trim($_POST['roll']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // পাসওয়ার্ড এনক্রিপশন (নিরাপত্তার জন্য)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default role: user
    $role = "user";

    // ডুপ্লিকেট ইমেইল চেক
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<div class='alert alert-danger text-center mt-3'>❌ এই ইমেইলটি আগেই ব্যবহার করা হয়েছে।</div>";
    } else {
        $sql = "INSERT INTO users (name, roll, email, password, role) 
                VALUES ('$name', '$roll', '$email', '$hashed_password', '$role')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<div class='alert alert-success text-center mt-3'>✅ রেজিস্ট্রেশন সম্পন্ন হয়েছে!</div>";
        } else {
            echo "<div class='alert alert-danger text-center mt-3'>❌ Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
<html>
<head>
    
    <?php include "inc/link-head.php";?>
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg p-4 rounded-4">
                <h3 class="text-center mb-4 text-primary">📚 User Registration</h3>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Roll</label>
                        <input type="text" name="roll" class="form-control" placeholder="Enter your Roll" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>

                    <div class="text-center mt-3">
                        <small>Already have an account? <a href="login.php" class="text-decoration-none">Login here</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
