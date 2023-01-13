<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  session_start();

  // include database connection
  include_once('pages/config.php');

  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  // check if the user has submitted the form
  if (isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);

    // check if email and password are NOT empty
    if (!empty($email)) {
      // Initialize Mailer, passing `true` enables exceptions
      $mail = new PHPMailer(false);

      // Generate random password
      $tempPassword = generateRandomString();

      try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["MAILER_SENDER"];
        $mail->Password = $_ENV["MAILER_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($_ENV["MAILER_SENDER"], "IT1C Gemorskos");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Password reset";
        $mail->Body = "Here is your new temporary password: <b>$tempPassword</b>. Please change it!";
        $mail->AltBody = "Here is your new temporary password: $tempPassword. Please change it!";

        $mail->send();
        echo "Message has been sent!";

        $hashedPassword = password_hash($tempPassword, PASSWORD_BCRYPT);

        // Update password in database
        $stmt = $conn->prepare("UPDATE Users SET password=:hashedPassword WHERE email=:email");
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "pages/head.php" ?>
  <script src="scripts/redirect.js"></script>
  <title>Password Reset</title>
</head>
<body>
  
</body>
</html>
