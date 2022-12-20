<?php
  session_start();

  // check if the user is already logged in
  if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <link href="styles/login.css" rel="stylesheet">
  <title>Forgot your password</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <form method="POST" action="sendEmail.php">
      <div class="mb-4 d-flex justify-content-center align-items-center">
        <img src="images/bootstrap-logo.svg" alt="Gemorskos logo" width="72" height="57">
        <h2>emorskos</h2>
      </div>
      <p class="mb-3 fw-normal text-start text-muted">Change password</p>
      <?php
        // alert the users if there is an error
        if (!empty($error)) {
          echo "<div class='alert alert-danger' role='alert'>$error</div>";
        }
      ?>
      <div class="form-floating">
        <input type="email" name="email" class="form-control" id="floatingInput" placeholder="Email" required>
        <label for="floatingInput">Email address</label>
      </div>
      <a href="/" class="d-block mt-3 text-start fw-normal">Go back</a>
      <button type="submit" name="submit" class="mt-3 w-100 btn btn-lg btn-primary">Send</button>
      <p class="mt-5 mb-3 text-muted">© IT1C 2022</p>
    </form>
  </main>
</body>
</html>
