<?php
  session_start();

  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
  }

  //only the admins should access this page, all others get redirected towards index.php
  if ($_SESSION["speciality"] != "editor") {
    header("location: index.php");
  }

  // get env variables and database connection from config.php
  include("pages/config.php");
  
  // accounts to delete
  if (isset($_POST["submit"])) {
    $targetID = filter_input(INPUT_POST, "submit", FILTER_SANITIZE_SPECIAL_CHARS);
    if ($targetID) {
    // delete the user from the Users table
      try {
        $sql = "DELETE FROM Users WHERE user_id = :target_id;";
        $stmt=$conn->prepare($sql);
        $stmt->bindValue(":target_id", $targetID); 
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
  <title>Manage accounts</title>
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
            <h2 class="my-4">Manage Accounts</h2>
            <form action="manageAccounts.php" method="POST">
              <?php
                try {
                  $sql = "SELECT * FROM Users;";
                  $stmt=$conn->prepare($sql);
                  $stmt->execute();
                } catch (PDOexception $e) {
                  echo $e . "<br>";
                }
                echo '
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Speciality</th>
                      <th scope="col">Delete</th>
                    </tr>
                  </thead>
                  <tbody>';
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $userID = $row["user_id"];
                  $name = $row["name"];
                  $email = $row["email"];
                  $password = $row["password"];
                  $speciality = $row["speciality"];
                  echo "
                  <tr>
                    <th scope='row'>$name</th>
                    <td>$email</td>
                    <td>$speciality</td>
                    <td><button class='btn btn-danger' name='submit' type='submit' value='$userID'>Delete</button></td>
                  </tr>
                ";
                }
                echo '
                  </tbody>
                </table>';
              ?>
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
