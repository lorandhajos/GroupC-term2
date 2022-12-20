<?php
  session_start();
  include_once "config.php";
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
      <div class="col p-0">
        <header class="py-4 shadow-sm">
        </header>
        <div class="p-4">
          <div>
            <?php
            echo "<p><strong>Name: " . $_SESSION['name'] . "</strong></p>";
            echo "<p><strong>Email: " . $_SESSION['email'] . "</strong></p>";
            echo "<p><strong>Specialty: " . $_SESSION['speciality'] . "</strong></p>";
            /*try {
              $sql = "SELECT COUNT(file_id) FROM Files WHERE user_id=:session_id";
              $stmt = $conn->prepare($sql);
              $stmt->bindValue(':session_id', $_SESSION['user_id']);
              $stmt->execute();
            } catch (Exception $e) {
              $error = "Failed to connect to the database";
            }
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            echo $result["COUNT(file_id)"];
            */
            
            
            ?> 
			
			 </div>
          <hr class="my-4">
          <div>
            <p><strong>Change Password</strong></p>
            <div class="col-sm-6">
              <input type="text" name="currentPassword" class="form-control" id="CurrentPassword" placeholder="Current Password..." value="">
            </div>
            <p></p>
            <div class="col-sm-6">
              <input type="text" name="newPassword" class="form-control" id="NewPassword" placeholder="New Password..." value="">
            </div>
            <p></p>
            <div class="col-sm-6">
              <input type="text" name="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="Confirm Password..." value="">
            </div>
            <p></p>
            <button type="button" class="btn btn-primary" type="submit">Submit</button>
          </div>
        </div>
        
        <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar p-0">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html> 
