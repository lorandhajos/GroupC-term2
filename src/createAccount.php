<?php
  session_start();

  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
  }

  //only the editors should access this page, all others get redirected towards index.php
  if ($_SESSION["speciality"] != "editor") {
    header("location: index.php");
  }

  // get env variables and database connection from config.php
  include("pages/config.php");
  
  // form validation
  if (isset($_POST["submit"])) {
    $err = "";
    $userName = filter_input(INPUT_POST, "userName", FILTER_SANITIZE_SPECIAL_CHARS);
    $userEmail = filter_input(INPUT_POST, "userEmail", FILTER_VALIDATE_EMAIL);
    $rawPassword = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $accountType = filter_input(INPUT_POST, "accountType", FILTER_SANITIZE_SPECIAL_CHARS);

    // check if all the fields are not empty
    if (empty($userName)) {
      $err = "Please provide a name for the user.";
    } elseif (empty($userEmail)) {
      $err = "Please provide a valid email address.";
    } elseif (empty($rawPassword)) {
      $err = "Please provide a password.";
    } elseif (empty($confirmPassword)) {
      $err = "Please confirm your password.";
    } elseif (empty($accountType)) {
      $err = "Please select an account type.";
    } elseif ($rawPassword != $confirmPassword) {
      // error if the passwords do not match
      $err = "Passwords do not match.";
    } else {
      //hash the password using the bcrypt algorithm
      $password = password_hash($rawPassword, PASSWORD_BCRYPT);

      // add the user into Users table
      try {
        $sql = "INSERT INTO Users (name, email, password, speciality) VALUES (:userName, :userEmail, :pass, :userSpeciality)";
        $stmt=$conn->prepare($sql);
        $stmt->bindValue(":userName", $userName);
        $stmt->bindValue(":userEmail", $userEmail);
        $stmt->bindValue(":pass", $password);
        $stmt->bindValue(":userSpeciality", $accountType); 
        $stmt->execute();
      } catch (PDOexception $e) {
        echo $e. "<br>";
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <?php include "pages/head.php" ?>
  <title>Create account</title>
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
            <form class="needs-validation" novalidate method="POST" action="createAccount.php">
              <h2 class="my-4">Create Account</h2>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="userName" class="form-label">Name</label>
                  <input type="text" class="form-control" name="userName" placeholder="John Smith" value="">
                </div>
                <div class="col-sm-6">
                  <label for="userEmail" class="form-label">Email</label>
                  <input type="text" class="form-control" name="userEmail" placeholder="john.smith@example.com" value="">
                </div>
                <div class="col-sm-6">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="" value="">
                </div>
                <div class="col-sm-6">
                  <label for="confirmPassword" class="form-label">Confirm Password</label>
                  <input type="password" class="form-control" name="confirmPassword" placeholder="" value="">
                </div>
                <div class="col-md-6">
                  <label for="accountType" class="form-label">Account Type</label>
                  <select class="form-select" name="accountType">
                    <option value=""></option>
                    <option value="journalist">Journalist</option>
                    <option value="photographer">Photographer</option>
                    <option value="editor">Editor</option>
                  </select>
                </div>
              <p><button class="w-auto mt-3 btn btn-primary btn-lg" type="submit" name="submit" value="submit">Create Account</button></p>
              <?php
              if (isset($_POST["submit"]) && !$err) {
                echo '
                <div class="alert alert-success">
                  Account created successfully!
                </div>';
              }
              elseif (isset($_POST["submit"])) {
                echo "
                <div class='alert alert-danger'>
                  $err
                </div>";
              }
               ?>
              </div>
            </form>
          </div>
        </div>
        <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar ">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
