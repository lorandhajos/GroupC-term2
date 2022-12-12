<?php
  session_start();

  // check if the user is already logged in
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit();
  }

  // include database connection
  include_once('config.php');

  // define empty error variable
  $error;
  
  // check if the user has submitted the form
  if (isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    // check if email and password are NOT empty
    if (!empty($email) && !empty($password)) {
      // trim email and password
      $email = trim($email);
      $password = trim($password);

      // hash the password
      $password = hash('sha256', $password);

      // check if email exists in the database
      try {
        $sql = "SELECT * FROM Users WHERE email=:email AND password=:password LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->execute();
      } catch (Exception $e) {
        $error = "Failed to connect to the database";
      }

      // if there are no errors fetch data from the database
      if (empty($error)) {
        if ($result = $stmt->fetch(PDO::FETCH_OBJ)) {
          // store user data in session
          $_SESSION["user_id"] = $result->user_id;
          $_SESSION["name"] = $result->name;
          $_SESSION["speciality"] = $result->speciality;
          $_SESSION["email"] = $result->email;
          $_SESSION['loggedin'] = true;
          
          // redirect to home page
          header("location: home.php");
        } else {
          $error = "Invalid email or password";
        }
      }
    } else {
      $error = "Please fill in all the fields";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <link href="styles/login.css" rel="stylesheet">
  <title>Login</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <form method="POST">
      <div class="mb-4 d-flex justify-content-center align-items-center">
        <img src="images/bootstrap-logo.svg" alt="Gemorskos logo" width="72" height="57">
        <h2>emorskos</h2>
      </div>
      <p class="mb-3 fw-normal text-start">Please sign in</p>
      <?php
        // alert the users if there is an error
        if (!empty($error)) {
          echo "<div class='alert alert-danger' role='alert'>$error</div>";
        }
      ?>
      <div class="form-floating mb-3">
        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" required>
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
        <label for="floatingPassword">Password</label>
      </div>
      <button type="submit" name="submit" class="mt-3 w-100 btn btn-lg btn-primary">Sign in</button>
      <p class="mt-5 mb-3 text-muted">© IT1C 2022</p>
    </form>
  </main>
</body>
</html>
