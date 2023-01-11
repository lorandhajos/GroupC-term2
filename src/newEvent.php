<?php
  session_start();

  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
  }

  // get env variables from config.php and 
  // setup a database connection using a PDO
  include("pages/config.php");

  //get the user speciality for future use
  try {
    $sql = "SELECT speciality FROM Users WHERE user_id=:user_id;";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":user_id", $_SESSION["user_id"]);
    $stmt->execute();
  } catch (PDOexception $e) {
    echo $e. "<br>";
  }
  $userSpeciality = $stmt->fetch(PDO::FETCH_ASSOC)["speciality"];
  
  $creationDate = date("y-m-d");

  if (isset($_POST["submit"])) {
    // initialize variables from the form
    $eventTitle = filter_input(INPUT_POST, "eventTitle", FILTER_SANITIZE_SPECIAL_CHARS);
    $eventDate = filter_input(INPUT_POST, "eventDate", FILTER_SANITIZE_SPECIAL_CHARS);
    $eventDesc = filter_input(INPUT_POST, "eventDesc", FILTER_SANITIZE_SPECIAL_CHARS);
    $eventCat = filter_input(INPUT_POST, "eventCategory", FILTER_SANITIZE_SPECIAL_CHARS);
    $reqJournalists = isset($_POST["reqJournalists"]);
    $reqPhotographers = isset($_POST["reqPhotographers"]);
    $err = "";
    if (empty($eventTitle)) {
      $err = "please enter a title for the event";
    }
    elseif (empty($eventDate)) {
      $err = "please provide the date for the event";
    }
    elseif (empty($eventDesc)) {
      $err = "please provide a short event description";
    }
    elseif (empty($eventCat)) {
      $err = "please provide an event category";
    }
  
    /*
    if all the relevant information has been sent, the page should go to a database and insert another event into the Events table
     */
    if (!$err) {
      // add the event into Events table
      try {
        $sql = "INSERT INTO Events (name, description, event_date, creation_date) VALUES (:event_title, :event_desc, :ev_date, :ev_created)";
        $stmt=$conn->prepare($sql);
        $stmt->bindValue("event_title", $eventTitle);
        $stmt->bindValue("event_desc", $eventDesc);
        $stmt->bindValue("ev_date", $eventDate);
        $stmt->bindValue("ev_created", $creationDate); 
        $stmt->execute();
      } catch (PDOexception $e) {
        echo $e. "<br>";
      }
      // claim the event as the user if said user has photographer or journalist speciality
      if ($userSpeciality !== "editor") {
        try {
          $sql = "INSERT INTO Claims(user_id, event_id) VALUES (:user_id, :event_id)";
          $stmt = $conn->prepare($sql);
          $stmt->bindValue(':user_id', $_SESSION["user_id"]);
          $stmt->bindValue(':event_id', $eventID);
          $stmt->execute();
        } catch (Exception $e) {
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
                  <input type="text" class="form-control" name="eventTitle" placeholder="" value="">
                </div>
                <div class="col-sm-6">
                  <label for="eventDate" class="form-label">Date</label>
                  <input type="date" class="form-control" name="eventDate" placeholder="" value="">
                </div>
                <div class="col-12">
                  <label for="eventDesc" class="form-label">Details</label>
                  <textarea class="form-control" name="eventDesc" rows="5"> </textarea>
                </div>
                <div class="col-md-6">
                  <label for="eventCategory" class="form-label">Event Category</label>
                  <select class="form-select" name="eventCategory">
                    <option value="">Not Specified</option>
                    <option value="sports">Sports</option>
                    <option value="politics">Politics</option>
                    <option value="disasters">Disasters</option>
                    <option value="health">Health</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="md-3">Claims</label>
                  <?php
                    // the photographers and journalists are allowed to create events, but they automatically claim it upon creation
                    // output the warning and the necessary buttons to allow other roles to claim the event
                    if ($userSpeciality != "editor") {
                      echo '
                      <div class="form-check">
                        <input type="checkbox" name="autoclaim" class="form-check-input" checked disabled>
                        <label class="form-check-label" for="autoclaim">You will claim the event as a ' . $userSpeciality . '</label>
                      </div>';
                    }
                    if ($userSpeciality != "photographer") {
                      echo '
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqPhotographers">
                        <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
                      </div>
                      ';
                    }
                    if ($userSpeciality != "journalist") {
                      echo '
                      <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="reqJournalists">
                        <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
                      </div>
                      ';
                      
                    }
                  ?>
                </div>
              <button class="w-auto mt-3 btn btn-primary btn-lg" type="submit" name="submit" value="submit">Create Event</button>
              <?php
              if (isset($_POST["submit"]) && !$err) {
                echo '
                <div class="alert alert-success">
                  Event created successfully!
                </div>';
              }
              elseif ($err) {
                echo '<div class="alert alert-danger">' . $err . '</div>';
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
