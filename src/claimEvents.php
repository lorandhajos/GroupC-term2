<?php
  session_start();

  // check if the user is already logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /");
  }

  include_once('pages/config.php');

  // check whether the form has been submitted
  if (isset($_POST["submit"])) {
    try {
      $targetColumn = "";
      if ($_SESSION["speciality"] === "journalist") {
        $targetColumn = "journalist_id";
      } elseif ($_SESSION["speciality"] === "photographer") {
        $targetColumn = "photographer_id";
      }
      // preparing the sql query
      // put the value of the superglobal $_SESSION["user_id"] into the user_id that will be connected to the event
      // and then again but for the event_id
      $sql = "UPDATE Events SET $targetColumn = :user_id WHERE event_id = :event_id;";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':user_id', $_SESSION["user_id"]);
      $stmt->bindValue(':event_id', $_POST["event_id"]);
      $stmt->execute();
      header("location: /home");
    } catch (Exception $e) {
      echo $e;
    }
  }
?>
