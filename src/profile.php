<?php
  session_start();
  include_once "config.php";

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $test = $conn->prepare("SELECT password FROM Users WHERE email=:email");
	$test->bindParam(':email', $_SESSION['email']);   
	$test->execute();
	
	if ($result = $test->fetch(PDO::FETCH_OBJ)) {
      $curr_pass = filter_input(INPUT_POST, "currentPassword");

	  if (password_verify($curr_pass, $result->password)) {
        echo "yes";
	  } else {
        echo "no";
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
      <div class="col p-0">
	    <header class="py-4 shadow-sm"></header>
        <div class="p-4">
          <div>
	        <?php
		      echo "<strong>Name:</strong>";
	          echo "<input type='text' class='form-control' value='" . $_SESSION['name'] . "' disabled>";
              echo "<strong>Email:</strong>";
		      echo "<input type='text' class='form-control' value='" . $_SESSION['email'] . "' disabled>";
		      echo "<strong>Specialty:</strong>";
		      echo "<input type='text' class='form-control' value='" . $_SESSION['speciality'] . "' disabled>";
	        ?>
	      </div>
          <hr class="my-4">
	      <div>
	        <p style="font-size: 2rem;"><strong>Change Password</strong></p><br>
	          <form action="profile.php" method="POST">
	            <div class="col-sm-6">
                  <input type="text" name="currentPassword" class="form-control" id="CurrentPassword" placeholder="Current Password..." value=""><br>
	            </div>
	            <div class="col-sm-6">
		          <input type="text" name="newPassword" class="form-control" id="NewPassword" placeholder="New Password..." value=""><br>
	            </div>
	            <div class="col-sm-6">
		          <input type="text" name="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="Confirm Password..." value=""><br>
	            </div>
	            <button class="btn btn-primary" type="submit">Submit</button>
	          </form>
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
