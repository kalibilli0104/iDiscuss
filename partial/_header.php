<?php
session_start();
include 'partial/_loginModal.php';
include 'partial/_signup.php';
include 'partial/_dbconnect.php'; // Make sure this file connects to $conn

echo '
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">iDiscuss</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Top Categories
          </a>
          <ul class="dropdown-menu">';

          $sql = "SELECT category_name, category_id FROM `categories` LIMIT 5";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            echo '<li><a class="dropdown-item" href="threadlist.php?catid=' . $row['category_id'] . '">' . $row['category_name'] . '</a></li>';
          }

echo '     </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.php">Contact</a>
        </li>
      </ul>

      <div class="mx-2 row">';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {
  echo '
        <form class="d-flex" role="search" action="/search.php" method="get">
          <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-success" type="submit">Search</button>
          <p class="text-white my-0 mx-2">Welcome ' . $_SESSION['useremail'] . '</p>
          <a href="partial/_logout.php" class="btn btn-outline-success">Logout</a>
        </form>';
} else {
  echo '
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-success" type="submit">Search</button>
          <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
          <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#signupModal">SignUp</button>
        </form>';
}

echo '
      </div>
    </div>
  </div>
</nav>
';

if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
  echo '
  <div class="alert alert-success alert-dismissible fade show my-0" role="alert">
      <strong>Success!</strong> You can login now.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

if (isset($_GET['showerror']) && $_GET['showerror'] == "true") {
  $showError = "Passwords do not match.";
  echo '
  <div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
      <strong>' . $showError . '</strong> 
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

if (isset($_GET['showalert']) && $_GET['showalert'] == "true") {
  $showAlert = "Email already in use.";
  echo '
  <div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
      <strong>' . $showAlert . '</strong> 
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

if (isset($_GET['loginfail']) && $_GET['loginfail'] == "true") {
  $show = "Login Failed.";
  echo '
  <div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
      <strong>' . $show . '</strong> 
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}

if (isset($_GET['loginsuccess']) && $_GET['loginsuccess'] == "true") {
  echo '
  <div class="alert alert-success alert-dismissible fade show my-0" role="alert">
      <strong>Success!</strong> You successfully logged in.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
}
?>
