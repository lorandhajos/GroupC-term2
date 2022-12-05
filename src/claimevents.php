<?php
// user_id being the id of the person that is logged in

include_once('config.php');

if (isset($_POST["submit"])) {
    try {
        $sql = "INSERT INTO Claims(user_id, event_id) VALUES (:user_id, :event_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $_SESSION["user_id"]);
        $stmt->bindValue(':event_id', $_POST["event_id"]);
        $stmt->execute();
      } catch (Exception $e) {
        $error = "Failed to sent data to the database/claim event";
      }
}

?>
