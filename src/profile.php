<?php
  session_start();

  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /");
  }

  include_once "pages/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "pages/head.php" ?>
  <link href="styles/home.css" rel="stylesheet">
  <title>Profile Page</title>
</head>
<body>
  <main>
    <div class="row m-0">
      <div class="col-auto p-0">
        <nav class="sidebar vh-100 overflow-hidden">
          <?php include "pages/navMenu.php" ?>
        </nav>
      </div>
      <div class="col p-0 d-flex flex-column justify-content-between">
        <div>
          <header class="headerheight shadow-sm"></header>
          <div class="container">
            <div>
              <h2 class="my-4">Profile</h2>
                <div class="mt-3">
                  <label class='form-label'>Name</label>
                  <?php echo "<input type='text' class='form-control' value='" . $_SESSION['name'] . "' disabled>"; ?>
                </div>
                <div class="mt-3">
                  <label class='form-label'>Email</label>
                  <?php echo "<input type='text' class='form-control' value='" . $_SESSION['email'] . "' disabled>"; ?>
                </div>
                <div class="mt-3">
                  <label class='form-label'>Specialty</label>
                  <?php echo "<input type='text' class='form-control' value='" . ucfirst($_SESSION['speciality']) . "' disabled>" ?>
                </div>
            </div>
            <div>
              <h2 class="my-4">Change Password</h2>
              <form action="profile" method="POST">
                <div class="mt-3">
                  <label class='form-label'>Current Password</label>
                  <input type="password" name="currentPassword" class="form-control" id="CurrentPassword">
                </div>
                <div class="mt-3">
                  <label class='form-label'>New Password</label>
                  <input type="password" name="newPassword" class="form-control" id="NewPassword">
                </div>
                <div class="mt-3">
                  <label class='form-label'>Confirm Password</label>
                  <input type="password" name="confirmPassword" class="form-control" id="ConfirmPassword">
                </div>
                <button class="btn btn-primary mt-3" type="submit">Submit</button>
              </form>
              <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') {
                  $test = $conn->prepare("SELECT password FROM Users WHERE email=:email");
                  $test->bindParam(':email', $_SESSION['email']);
                  $test->execute();

                  if ($result = $test->fetch(PDO::FETCH_OBJ)) {
                    $curr_pass = filter_input(INPUT_POST, "currentPassword");
                    $new_pass = filter_input(INPUT_POST, "newPassword");
                    $confirm_pass = filter_input(INPUT_POST, "confirmPassword");

                    if($new_pass == $confirm_pass) {
                      if (password_verify($curr_pass, $result->password)) {
                        $new_pass_crypt = password_hash($new_pass, PASSWORD_BCRYPT);
                        $query_change = $conn->prepare("UPDATE Users SET password=:new_pass WHERE email=:user_email AND name=:user_name");
                        $query_change->bindParam(':new_pass', $new_pass_crypt);
                        $query_change->bindParam(':user_email', $_SESSION['email']);
                        $query_change->bindParam(':user_name', $_SESSION['name']);
                        $query_change->execute();

                        echo "<div class='alert alert-success my-3'>Password Change Successfully!</div>";
                      }
                    } else {
                      echo "<div class='alert alert-danger my-3'>Passwords don't match</div>";
                    }
                  }
                }
              ?>
            </div>
          </div>
        </div>
        <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar p-0">
          <p class="mb-0 me-4">Copyright © 2022- Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
