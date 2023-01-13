<?php
  session_start();
  include_once "config.php";

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $test = $conn->prepare("SELECT password FROM Users WHERE email=:email");
    $test->bindParam(':email', $_SESSION['email']);
    $test->execute();

  if ($result = $test->fetch(PDO::FETCH_OBJ)) {
    $curr_pass = filter_input(INPUT_POST, "currentPassword");
    $new_pass= filter_input(INPUT_POST, "newPassword");
    $confirm_pass= filter_input(INPUT_POST, "confirmPassword");
    if($new_pass==$confirm_pass){
      if (password_verify($curr_pass, $result->password)) {
        $new_pass_crypt=password_hash($new_pass, PASSWORD_BCRYPT);
        $query_change=$conn->prepare("UPDATE Users SET password=:new_pass WHERE email=:user_email AND name=:user_name");
        $query_change->bindParam(':new_pass', $new_pass_crypt);
        $query_change->bindParam(':user_email', $_SESSION['email']);
        $query_change->bindParam(':user_name', $_SESSION['name']);
        $query_change->execute();
        echo"Password changed successfully";
      } else {
        echo "Error";
      }
      }else {
        echo"<h5>Passwords not matching</h5>";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <link href="styles/home.css" rel="stylesheet">
  <title>Profile Page</title>
</head>
<body>
  <main>
  <div class="row m-0">
    <div class="col-auto p-0">
      <nav class="sidebar">
      <?php include "navMenu.php" ?>
      </nav>
    </div>
    <div class="col p-0 d-flex flex-column justify-content-between">
      <div>
        <header class="py-4 shadow-sm"></header>
          <div class="px-4">
            <div>
              <p class="fs-2 my-1">Profile</p>
              <?php
                echo "<label class='form-label my-1'>Name:</label>";
                echo "<input type='text' class='form-control' value='" . ucfirst($_SESSION['name']) . "' disabled>";
                echo "<label class='form-label my-1'>Email:</label>";
                echo "<input type='text' class='form-control' value='" . $_SESSION['email'] . "' disabled>";
                echo "<label class='form-label my-1'>Specialty:</label>";
                echo "<input type='text' class='form-control' value='" . ucfirst($_SESSION['speciality']) . "' disabled>";
              ?>
            </div>
            <div>
              <p class="fs-2 my-3">Change Password</p>
                <form action="profile.php" method="POST">
                  <div class="col-sm-6 my-3">
                    <input type="password" name="currentPassword" class="form-control" id="CurrentPassword" placeholder="Current Password...">
                  </div>
                  <div class="col-sm-6 my-3">
                    <input type="password" name="newPassword" class="form-control" id="NewPassword" placeholder="New Password...">
                  </div>
                  <div class="col-sm-6 my-3">
                    <input type="password" name="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="Confirm Password...">
                  </div>
                  <button class="btn btn-primary" type="submit">Submit</button>
                </form>
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
