<?php
  session_start();

  // check if the user is already logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
  }

  // include database connection
  include_once('pages/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "pages/head.php" ?>
  <title>Home Page</title>
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
            <h2 class="my-4">Your Events</h2>
            <div class="accordion" id="accordionExample1">
              <?php
                $stmt = $conn->prepare("SELECT * FROM Claims INNER JOIN Users ON Claims.user_id = Users.user_id INNER JOIN Events ON Claims.event_id = Events.event_id WHERE Users.user_id = :id");
                $stmt->bindValue("id", $_SESSION["user_id"]);
                $stmt->execute();

                $yourEventId = 0;
                while ($results = $stmt->fetch()) {
                  $eventName = $results["name"];
                  $eventId = $results["event_id"];
                  $description = $results["description"];
                  $creationDate = $results["creation_date"];
                  $eventDate = $results["event_date"];

                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingYourEvent$yourEventId'>
                      <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseYourEvent$yourEventId' aria-expanded='true' aria-controls='collapseYourEvent$yourEventId'>
                      $eventName #$eventId
                      </button>
                    </h2>
                    <div id='collapseYourEvent$yourEventId' class='accordion-collapse collapse' aria-labelledby='headingYourEvent$yourEventId' data-bs-parent='#accordionExample1'>
                      <div class='accordion-body'>
                        <p>$description</p>
                        <p><strong>Event created on:</strong> $creationDate</p>
                        <div class='d-flex justify-content-between'>
                          <div class='input-group flex-nowrap' style='width: 150px;'>
                          <span class='input-group-text' id='addon-wrapping'><i class='fa-solid fa-calendar-days'></i></span>
                          <p class='form-control mb-0'>$eventDate</p>
                          </div>
                          <a href='home.php' class='btn btn-primary' role='button'>Upload Files</a>
                        </div>
                      </div>
                    </div>
                  </div>";
                  $yourEventId ++;
                }
              ?>
            </div>
            <h2 class="mt-4 mb-4">Unclaimed Events</h2>
            <div class="accordion" id="accordionExample2">
              <?php
                $stmt = $conn->prepare("SELECT * FROM Events WHERE NOT EXISTS (SELECT * FROM Claims WHERE Events.event_id = Claims.event_id)");
                $stmt->bindColumn("event_id", $eventId);
                $stmt->bindColumn("name", $eventName);
                $stmt->bindColumn("description", $description);
                $stmt->bindColumn("event_date", $eventDate);
                $stmt->bindColumn("creation_date", $creationDate);
                
                $stmt->execute();
                $results = $stmt->execute();

                $accordionId = 0;
                while ($results = $stmt->fetch()) {
                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='heading$accordionId'>
                      <button class='accordion-button collapsed d-flex' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$accordionId' aria-expanded='true' aria-controls='collapse$accordionId'>
                      $eventName #$eventId
                      </button>
                    </h2>
                    <div id='collapse$accordionId' class='accordion-collapse collapse' aria-labelledby='heading$accordionId' data-bs-parent='#accordionExample2'>
                      <div class='accordion-body'>
                        <p>$description</p>
                        <p><strong>Event created on:</strong> $creationDate</p>
                        <div class='d-flex justify-content-between'>
                          <div class='input-group flex-nowrap' style='width: 150px;'>
                          <span class='input-group-text' id='addon-wrapping'><i class='fa-solid fa-calendar-days'></i></span>
                          <p class='form-control mb-0'>$eventDate</p>
                          </div>
                          <form action='pages/claimEvents.php' method='POST'>
                            <input type='hidden' name='event_id' value='$eventId'>
                            <input type='submit' name='submit' class='btn btn-primary' value='Claim Event'>
                          </form>
                        </div>" . ($_SESSION["speciality"]=="editor" ? "<a href='editEvent.php?edit=$eventId' class='btn btn-primary mt-2' role='button'>Edit</a>" : "") . "
                      </div>
                    </div>
                  </div>
                  ";
                  $accordionId ++;
                }
              ?>
            </div> 
            <h2 class="mt-4 mb-4">Claimed Events</h2>
            <div class="accordion" id="accordionExample2">
              <?php
                $stmt = $conn->prepare("SELECT * FROM Events WHERE EXISTS (SELECT * FROM Claims WHERE Events.event_id = Claims.event_id)");
                $stmt->bindColumn("event_id", $eventId);
                $stmt->bindColumn("name", $eventName);
                $stmt->bindColumn("description", $description);
                $stmt->bindColumn("event_date", $eventDate);
                $stmt->bindColumn("creation_date", $creationDate);
                
                $stmt->execute();
                $results = $stmt->execute();

                $claimedId = 0;
                while ($results = $stmt->fetch()) {
                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingClaimed$claimedId'>
                      <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseClaimed$claimedId' aria-expanded='true' aria-controls='collapseClaimed$claimedId'>
                      $eventName #$eventId
                      </button>
                    </h2>
                    <div id='collapseClaimed$claimedId' class='accordion-collapse collapse' aria-labelledby='headingClaimed$claimedId' data-bs-parent='#accordionExample3'>
                      <div class='accordion-body'>
                        <p>$description</p>
                        <p><strong>Event created on:</strong> $creationDate</p>
                        <div class='d-flex justify-content-between'>
                          <div class='input-group flex-nowrap' style='width: 150px;'>
                          <span class='input-group-text' id='addon-wrapping'><i class='fa-solid fa-calendar-days'></i></span>
                          <p class='form-control mb-0'>$eventDate</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  ";
                  $claimedId ++;
                }
              ?>
            </div> 
          </div>
        </div>
        <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar p-0">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
