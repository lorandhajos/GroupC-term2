<?php
  $host = $_ENV["DB_SERVER"];
  $user = $_ENV["DB_USER"];
  $pass = $_ENV["DB_PASSWORD"];
  $db = $_ENV["DB_NAME"];

  // Constants
  $claimVacant = -1; // used to show that no one claimed the event
  $claimRestricted = -2; // the creator does not allow to claim the event

  try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $e) {
    echo $e->getMessage();
    exit();
  }
?>
