<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!-- 🌐 NAVBAR START -->
<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container">
    <!-- Brand -->
    <a class="navbar-brand fw-bold" href="../index.php">Home</a>

    <!-- Toggle button for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Links -->
     <?php if (isset($_SESSION['user_id']) === 'admin'): ?>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="fw-bold navbar-nav ms-auto ">
        <li class="nav-item">
          <a class="nav-link active" href="books.php">Books</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" href="add_book.php">Add Book</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" href="edit_book.php">Edit Book</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" href="manage_books.php">Manage Book</a>
        </li>
         <li class="nav-item">
          <a class="nav-link active" href="manage_borrow.php">Borrow & Return</a>
        </li>
        
         <li class="nav-item">
          <a class="nav-link active" href="manage_users.php">Manage Users</a>
        </li>
        </ul>
        <?php else: ?>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ms-auto">
          <li class="nav-item">
          <a class="nav-link active" href="../user/borrowed_books.php">Borrow & Return</a>
          </li>
        </ul>

        <?php endif; ?>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav ms-auto">
           <li class="nav-item">
          <a class="nav-link fw-bold" href="about.php">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-bold" href="contact.php">Contact</a>
        </li>
        <div class="d-flex">
      <a class="btn btn-danger" href="../logout.php">Logout</a>
    </div>
        </ul>
        <ul>
        <!-- Dropdown Menu -->
        <!--<li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            Account
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="login.php">Login</a></li>
            <li><a class="dropdown-item" href="register.php">Register</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </li>-->
      </ul>
    </div>
  </div>
</nav>
