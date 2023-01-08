<?php
  session_start();
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errs = array();
    if (empty($_POST["eventTitle"])) {
      $errs[] = "please enter a title for the event";
    }
    else {
      $eventTitle = filter_input(INPUT_POST, "eventTitle", FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if (empty($_POST["eventDate"])) {
      $errs[] = "please provide the date for the event";
    }
    else {
      $eventDate = filter_input(INPUT_POST, "eventDate", FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if (empty($_POST["eventDesc"])) {
      $errs[] = "please provide a short event description";
    }
    else {
      $eventDesc = filter_input(INPUT_POST, "eventDesc", FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if (empty($_POST["eventCategory"])) {
      $errs[] = "please provide an event category";
    }
    else {
      $eventCat = filter_input(INPUT_POST, "eventCategory", FILTER_SANITIZE_SPECIAL_CHARS);
    }
    $reqJournalists = isset($_POST["reqJournalists"]);
    $reqPhotographers = isset($_POST["reqPhotographers"]);
    foreach ($errs as $err) {
      echo $err;
      echo "<br>";
    }
    $creationDate = date("y-m-d");
    include("config.php");
    try {
      $connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    }
    catch (PDOexception $e) {
      echo $e. "<br>";
    }
    if (count($errs) == 0) {
      // generate an event ID by using the maximum ID the database and adding 1
      // could be replaced with something more sophisticated
      try {
        $sql="SELECT MAX(event_id) FROM Events;";
        $stmt=$connection->prepare($sql);
        $stmt->execute();
      }
      catch (PDOexception $e){
        echo $e. "<br>";
      }
      if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $eventID = $result["MAX(event_id)"];
        $eventID += 1;
      }
      else {
        $eventID = 1;
      }
      try {
        $sql = "INSERT INTO Events (event_id, name, description, event_date, creation_date) VALUES (:event_id, :event_title, :event_desc, :ev_date, :ev_created)";
        $stmt=$connection->prepare($sql);
        $stmt->bindValue("event_id", $eventID);
        $stmt->bindValue("event_title", $eventTitle);
        $stmt->bindValue("event_desc", $eventDesc);
        $stmt->bindValue("ev_date", $eventDate);
        $stmt->bindValue("ev_created", $creationDate); 
        $stmt->execute();
      }
        catch (PDOexception $e) {
        echo $e. "<br>";
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <?php include "head.php" ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create event</title>
</head>
<body>
  <main>
    <div class="container">
      <form class="needs-validation" novalidate method="POST" action="newEvent.php">
        <h4 class="mb-3">Event Details</h4>
        <div class="row g-3">
          <div class="col-sm-6">
            <label for="eventTitle" class="form-label">Event Title</label>
            <input type="text" class="form-control" name="eventTitle" id="eventTitle" placeholder="" value="" required>
            <div class="invalid-feedback">
              Event title is required.
            </div>
          </div>
          <div class="col-sm-6">
            <label for="eventDate" class="form-label">Date</label>
            <input type="date" class="form-control" name="eventDate" id="eventDate" placeholder="" value="" required>
            <div class="invalid-feedback">
              Date is required.
            </div>
          </div>
          <div class="col-12">
            <label for="eventDesc" class="form-label">Details</label>
            <textarea class="form-control" name="eventDesc" id="eventDesc" rows="5"> </textarea>
            <div class="invalid-feedback">
              Please provide more details regarding the event.
            </div>
          </div>
          <div class="col-md-5">
            <label for="eventCategory" class="form-label">Event Category</label>
            <select class="form-select" name="eventCategory" id="eventCategory" required>
              <option value="">Not Specified</option>
              <option>Sports</option>
              <option>Politics</option>
              <option>Disasters</option>
              <option>Health</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid category
            </div>
          </div>
        </div>
        <hr class="my-4">
        <h4 class="mb-3">Claims</h4>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="reqJournalists" value="true">
          <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="reqPhotographers" value="true">
          <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
        </div>
        <hr class="my-4">
        <button class="w-100 btn btn-primary btn-lg" type="submit">Create Event</button>
      </form>
    </div> 
  </main>
</body>
</html>
