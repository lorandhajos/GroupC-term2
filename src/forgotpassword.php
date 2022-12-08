<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <link href="styles/login.css" rel="stylesheet">
  <title>Forgot your password</title>
</head>
<body class="text-center">
  <main class="form-signin w-100 m-auto">
    <form method="POST">
      <div class="mb-4 d-flex justify-content-center align-items-center">
        <img src="images/bootstrap-logo.svg" alt="Gemorskos logo" width="72" height="57">
        <h2>emorskos</h2>
      </div>
      <p class="mt-3 mb-3 text-muted">Change password</p>
      <?php
        // alert the users if there is an error
        if (!empty($error)) {
          echo "<div class='alert alert-danger' role='alert'>$error</div>";
        }
      ?>
      <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control" id="floatingInput" placeholder="Password" required>
        <label for="floatingInput">New password...</label>
      </div>
      <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
        <label for="floatingPassword">Confirm password</label>
      </div>
      <p class="mt-3 mb-2 text-muted">Please don't share your password with anyone!</p>
      <button type="submit" name="submit" class="mt-3 w-100 btn btn-lg btn-primary">Change password</button>
      <p class="mt-5 mb-3 text-muted">© IT1C 2022</p>
    </form>
  </main>
</body>
</html>
