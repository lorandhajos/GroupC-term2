<!DOCTYPE html>
<html lang="en">
  <head>
  <?php include "head.php" ?>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Page</title>
  </head>
  
        <body>
    <main> 
  <?php "navMenu.php" ?>
    </main>
    <p id="Name"><strong>Name:</strong></p>
	<p id="Email"><strong>Email:</strong></p>
	<p id="Speacialty"><strong>Specialty:</strong></p>
	<p id="Claimed Events"><strong>Claimed Events:</strong></p>
	<p id="Events"><strong>Events Created:</strong></p>
	<form action="profile.php" method="post"> </p>
		<p> <input type="text" name="pass" placeholder="Current password"> </p>
		<p> <input type="text" name="newPass" placeholder="New password"> </p>
		<p> <input type="text" name="confirmPass" placeholder="Confirm password"> </p>
		<p> <input type="submit" value="Change Password">
	</form>
</html>