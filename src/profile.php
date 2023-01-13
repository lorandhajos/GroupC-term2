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
	   	}else {
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
    <div class="col p-0">
	    <header class="py-4 shadow-sm"></header>
        <div class="p-4">
          <div><br>
		  
	        <?php
<<<<<<< HEAD
		      	echo "<h1 class='mt-1' style='font-size: 1.3rem;'><strong>Name:</strong></h1>";
	          echo "<input style='width:42vw;'  type='text' class='form-control' value='" . $_SESSION['name'] . "'  disabled>";
            echo "<h1 class='mt-1' style='font-size: 1.3rem;'><strong>Email:</strong></h1>";
		      	echo "<input style='width:42vw;' type='text' class='form-control' value='" . $_SESSION['email'] . "' disabled>";
		      	echo "<h1 class='mt-1' style='font-size: 1.3rem;'><strong>Specialty:</strong></h1>";
		      	echo "<input style='width:42vw;' type='text' class='form-control' value='" . $_SESSION['speciality'] . "' disabled>";
=======
		      echo "<h1 style='font-size: 1.3rem;'><strong>Name:</strong></h1>";
	          echo "<input style='width:42.1vw;'  type='text' class='form-control' value='" . $_SESSION['name'] . "'  disabled>";
              echo "<h1 style='font-size: 1.3rem;'><strong>Email:</strong></h1>";
		      echo "<input style='width:42.1vw;' type='text' class='form-control' value='" . $_SESSION['email'] . "' disabled>";
		      echo "<h1 style='font-size: 1.3rem;'><strong>Specialty:</strong></h1>";
		      echo "<input style='width:42.1vw;' type='text' class='form-control' value='" . $_SESSION['speciality'] . "' disabled>";
>>>>>>> b3bb8553d43d088812ed6cbdddcba6c5fd15ebf5
	        ?>

	      	</div><br>
          <hr class="my-4">
<<<<<<< HEAD
					<div>
						<p style="font-size: 1.3rem;"><strong>Change Password</strong></p><br>
							<form action="profile.php" method="POST">
								<div class="col-sm-6">
									<input type="text" name="currentPassword" class="form-control" id="CurrentPassword" placeholder="Current Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
								</div>
								<div class="col-sm-6">
									<input type="text" name="newPassword" class="form-control" id="NewPassword" placeholder="New Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
								</div>
								<div class="col-sm-6">
									<input type="text" name="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="Confirm Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
								</div>
								<button class="btn btn-primary" type="submit">Submit</button>
							</form>
					</div>
=======
	      <div>
	        <p style="font-size: 1.3rem;"><strong>Change Password</strong></p><br>
	          <form action="profile.php" method="POST">
	            <div class="col-sm-6">
                  <input type="password" name="currentPassword" class="form-control" id="CurrentPassword" placeholder="Current Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
	            </div>
	            <div class="col-sm-6">
		          <input type="password" name="newPassword" class="form-control" id="NewPassword" placeholder="New Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
	            </div>
	            <div class="col-sm-6">
		          <input type="password" name="confirmPassword" class="form-control" id="ConfirmPassword" placeholder="Confirm Password..." value="" style="box-shadow: inset 0 0 3px gray;"><br>
	            </div>
	            <button class="btn btn-primary" type="submit">Submit</button>
	          </form>
	      </div>
>>>>>>> b3bb8553d43d088812ed6cbdddcba6c5fd15ebf5
        </div>
	    <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar p-0">
        <p class="mb-0 me-4">Copyright © 2022- Gemorskos. All rights reserved</p>
      </footer>
	  </div>
	</div>
  </main>
</body>
</html>
