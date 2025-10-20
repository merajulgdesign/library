<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
    <div class="row nav_head">
        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 ">
        <img class= "kacst-logo" src="images/kacst-logo.png" alt="">
        </div>
        <div class="site-title col-lg-8 col-md-8 col-sm-12 col-xs-12">
        <h2>Library Management System Update</h2>
        <h3>KHANJAHAN ALI COLLEGE OF SCIENCE & TECHNOLOGY</h3>
        </div>

        
        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- ✅ যদি ইউজার লগইন করা থাকে -->
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <button class="btn btn-primary mt-3" ><a href="redirect_role.php">Dashboard</a></button>
            <button class="btn btn-primary mt-3"><a href="logout.php">Logout</a> </button>
            </div>
          
        <?php else: ?>
          <!-- 🚫 লগইন না করলে -->
              <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <button class="btn btn-primary mt-3"><a href="register.php">Registration</a> </button>
            <button class="btn btn-primary mt-3 me-1"><a href="login.php">Login</button>
        <?php endif; ?>
        </div>