<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "header.php" ?>
  <link href="styles/login.css" rel="stylesheet">
  <title>Login</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <form>
      <div class="mb-4 d-flex justify-content-center align-items-center">
        <img src="images/bootstrap-logo.svg" alt="Gemorskos logo" width="72" height="57">
        <h2>emorskos</h2>
      </div>
      <p class="mb-3 fw-normal text-start">Please sign in</p>
      <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>
      <button class="mt-3 w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">© IT1C 2022</p>
    </form>
  </main>
</body>
</html>
