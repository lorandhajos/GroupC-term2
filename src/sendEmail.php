<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;

  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  session_start();

  // exit if the user is not logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    exit();
  }

  // check if the user has submitted the form
  if (isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);

    // check if email and password are NOT empty
    if (!empty($email)) {
      // Initialize Mailer, passing `true` enables exceptions
      $mail = new PHPMailer(false);

      try {
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV["MAILER_SENDER"];
        $mail->Password = $_ENV["MAILER_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($_ENV["MAILER_SENDER"], "IT1C Gemorskos");
        $mail->addAddress($_SESSION["email"]);

        $mail->isHTML(true);
        $mail->Subject = "Password reset";
        $mail->Body = "Here is your password reset link: <b>link</b>";
        $mail->AltBody = "Here is your password reset link: link";

        $mail->send();
        echo "Message has been sent!";
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }
  }
?>
