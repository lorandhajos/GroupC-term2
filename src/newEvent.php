<?php
  session_start();
  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
  }
  // get env variables from config.php and 
  // setup a database connection using a PDO
  include("pages/config.php");
  try {
    $connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  }
  catch (PDOexception $e) {
    echo $e. "<br>";
  }
  //get the user speciality for future use
  try {
    $sql = "SELECT speciality FROM Users WHERE user_id=:user_id;";
    $stmt = $connection->prepare($sql);
    $stmt->bindValue(":user_id", $_SESSION["user_id"]);
    $stmt->execute();
  }
  catch (PDOexception $e) {
    echo $e. "<br>";
  }
  $userSpeciality = $stmt->fetch(PDO::FETCH_ASSOC)["speciality"];

  // form validation
  if (isset($_POST["submit"])) {
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
    /*
    if all the relevant information has been sent, the page should go to a database and create it
     */
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
      // add the event into Events table
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
      // claim the event as the user if said user has photographer or journalist speciality
      if ($userSpeciality == "photographer" || $userSpeciality == "journalist") {
        try {
          $sql = "INSERT INTO Claims(user_id, event_id) VALUES (:user_id, :event_id)";
          $stmt = $connection->prepare($sql);
          $stmt->bindValue(':user_id', $_SESSION["user_id"]);
          $stmt->bindValue(':event_id', $eventID);
          $stmt->execute();
        } 
        catch (Exception $e) {
          echo $e;
        }
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <?php include "pages/head.php" ?>
  <title>Create event</title>
</head>
<body>
  <main>
    <div class="row m-0">
      <div class="col-auto p-0">
        <nav class="sidebar vh-100 overflow-hidden">
          <?php include "pages/navMenu.php" ?>
        </nav>
      </div>
      <div class="col p-0 d-flex flex-column justify-content-between">
        <div>
          <header class="headerheight shadow-sm"></header>
          <div class="mx-5">
            <form class="needs-validation" novalidate method="POST" action="newEvent.php">
              <h2 class="my-4">Event Details</h2>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="eventTitle" class="form-label">Event Title</label>
                  <input type="text" class="form-control" name="eventTitle" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Event title is required.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="eventDate" class="form-label">Date</label>
                  <input type="date" class="form-control" name="eventDate" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Date is required.
                  </div>
                </div>
                <div class="col-12">
                  <label for="eventDesc" class="form-label">Details</label>
                  <textarea class="form-control" name="eventDesc" rows="5"> </textarea>
                  <div class="invalid-feedback">
                    Please provide more details regarding the event.
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="eventCategory" class="form-label">Event Category</label>
                  <select class="form-select" name="eventCategory" required>
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
                <div class="col-md-6">
                  <label class="md-3">Claims</label>
                  <?php
                    // the photographers and journalists are allowed to create events, but they automatically claim it upon creation
                    // output the warning and the necessary buttons to allow the other role to claim the event
                    if ($userSpeciality == "photographer") {
                      echo '<p>You will claim the event as a photographer</p>';
                      echo '
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqJournalists">
                        <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
                      </div>
                      ';
                    }
                    elseif ($userSpeciality == "journalist") {
                      echo '<p>You will claim the event as a journalist</p>';
                      echo '
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqPhotographers">
                        <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
                      </div>
                      ';
                    }
                    else {
                      echo '
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqJournalists">
                        <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
                      </div>
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqPhotographers">
                        <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
                      </div>
                      ';
                    }
                  ?>
                </div>
              <button class="w-auto mt-3 btn btn-primary btn-lg" type="submit" name="submit" value="submit">Create Event</button>
              <?php
              if (isset($_POST["submit"]) && count($errs)==0) {
                echo '
                <div class="alert alert-success">
                  Event created successfully!
                </div>';
              }
               ?>
              </div>
            </form>
          </div>
          <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar ">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
