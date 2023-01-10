<?php
  session_start();
  include_once('config.php');

  // check whether the form has been submitted
  if (isset($_POST["submit"])) {
    try {
      // preparing the sql query
      // put the value of the superglobal $_SESSION["user_id"] into the user_id that will be connected to the event
      // and then again but for the event_id
      $sql = "INSERT INTO Claims(user_id, event_id) VALUES (:user_id, :event_id)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':user_id', $_SESSION["user_id"]);
      $stmt->bindValue(':event_id', $_POST["event_id"]);
      $stmt->execute();
      header("location: /home.php");
    } catch (Exception $e) {
      echo $e;
    }
  }
?>
