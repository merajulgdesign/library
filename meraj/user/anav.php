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
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class=" navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active fw-bold" href="../admin/books.php">All Books</a>
          </li>
          <li class="nav-item  me-3">
          <a class="nav-link active fw-bold" href="borrowed_books.php">Return Book</a>
          </li>

        <li class="nav-item fw-bold">
          <a class="nav-link" href="../admin/about.php">About</a>
        </li>

        <li class="nav-item fw-bold">
          <a class="nav-link" href="../admin/contact.php">Contact</a>
        </li>
        <a class="btn btn-outline-secondary me-2" href="borrowed_books.php">My Borrowed</a>
        <a class="btn btn-danger" href="../logout.php">Logout</a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="../login.php">Login</a></li>
            <li><a class="dropdown-item" href="../register.php">Register</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../logout.php">Logout</a></li>
          </ul>
        </ul>
        <ul>
        <!-- Dropdown Menu -->
       <!-- <li class="nav-item dropdown">
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