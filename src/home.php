<?php
  session_start();

  // check if the user is already logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /");
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
          <div class="container">
            <?php
              if ($_SESSION["speciality"] !== "editor") {
                echo "<h2 class='my-4'>Your Events</h2>";
                echo "<div class='accordion' id='accordionExample1'>";

                $stmt = $conn->prepare("SELECT * FROM Events WHERE Events.journalist_id = :id OR Events.photographer_id = :id");
                $stmt->bindValue("id", $_SESSION["user_id"]);
                $stmt->execute();

                $yourEventId = 0;
                while ($results = $stmt->fetch()) {
                  $eventName = $results["name"];
                  $eventId = $results["event_id"];
                  $description = $results["description"];
                  $creationDate = $results["creation_date"];
                  $eventDate = $results["event_date"];
                  $eventCategory = $results["event_category"];

                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingYourEvent$yourEventId'>
                      <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseYourEvent$yourEventId' aria-expanded='true' aria-controls='collapseYourEvent$yourEventId'>
                        #$eventId $eventName <span class='badge text-bg-info mx-1'>$eventCategory</span>
                      </button>
                    </h2>
                    <div id='collapseYourEvent$yourEventId' class='accordion-collapse collapse' aria-labelledby='headingYourEvent$yourEventId' data-bs-parent='#accordionExample1'>
                      <div class='accordion-body'>
                        <p>$description</p>
                          <div class='d-flex flex-wrap gap-2 mb-2'>
                            <div class='input-group flex-nowrap calendarWidth'>
                                <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                                <p class='form-control mb-0'>$creationDate</p>
                            </div>
                            <div class='input-group flex-nowrap calendarWidth'>
                              <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                              <p class='form-control mb-0'>$eventDate</p>
                            </div>
                          </div>
                          <div class='d-flex flex-wrap gap-2'>
                            <form action='home' method='post' enctype='multipart/form-data'>
                              <input type='file' name='fileToUpload' id='fileToUpload' multiple>
                              <input type='submit' value='Upload' name='submit' class='btn btn-primary'>
                              <input type='hidden' name='eventId' value='$eventId'>
                            </form>
                        </div>
                      </div>
                    </div>
                  </div>";
                  $yourEventId++;
                } 

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  $eventId = filter_input(INPUT_POST, 'eventId', FILTER_VALIDATE_INT);
                  $target_dir = 'uploads/' . $eventId . '/';
                  $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
                  $error = '';
                  $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                  // Check if the user claimed the event
                  $stmt = $conn->prepare("SELECT * FROM Events WHERE (Events.photographer_id = :userId OR Events.journalist_id = :userId) AND Events.event_id = :eventId");
                  $stmt->bindValue("userId", $_SESSION["user_id"]);
                  $stmt->bindValue("eventId", $eventId);
                  $stmt->execute();
                  $results = $stmt->fetch(PDO::FETCH_OBJ);

                  //check if a file was selected
                  if ($_FILES['fileToUpload']['size'] == 0 && empty($_FILES['fileToUpload']['name']) ){
                    $error = "No file was selected";
                  } else {
                    //check if you are assigned to the event, so whether or not the value hasnt been changed with f12
                    if($stmt->rowCount() == 0) {
                      $error = "You don't have the rights to upload to this event";
                    }

                    //check if the directory uploads exists
                    if (is_dir('uploads/') == false){
                      mkdir('uploads/', 0777);
                    }

                    //check if the directory already exists. 
                    if (is_dir($target_dir) == false) {
                      // make dir with the name $target_dir
                      mkdir($target_dir);
                    }

                    //Check if file already exists
                    if (file_exists($target_file)) {
                      $error = "Sorry, file already exists.";
                    }

                    //Check whether the file size is above 128mb
                    if ($_FILES["fileToUpload"]["size"] > 128000000) {
                      $error = "Sorry, your file is too large, maximum filesize is 128mb.";
                    }

                    //Allow certain file formats
                    if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
                    && $FileType != "docx" && $FileType != "txt" && $FileType != "odt") {
                      $error = "Sorry, your files are not allowed.";
                    }                    
                  }

                  if (empty($error)) {
                    // if error is empty, try to upload file
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                      try {
                        $sql = "INSERT INTO Files(user_id, event_id, upload_date, file_name) VALUES (:user_id, :event_id, :upload_date, :file_name)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindValue(':user_id', $_SESSION["user_id"]);
                        $stmt->bindValue(':event_id', $results->event_id);
                        $stmt->bindValue(':upload_date', date("y-m-d"));
                        $stmt->bindValue(':file_name', basename($_FILES['fileToUpload']['name']));

                        if ($stmt->execute()) {
                          echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        }
                      } catch (PDOException $e) {
                        echo $e->getMessage();
                      }
                    }
                  } else {  
                    echo $error;
                  }
                }
                echo "</div>";
              }
            ?>
            <h2 class="mt-4 mb-4">Unclaimed Events</h2>
            <div class="accordion" id="accordionExample2">
              <?php
                // Get all events that can be claimed by the user and not claimed yet
                if ($_SESSION["speciality"] == "photographer") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 1 AND Events.photographer_id IS NULL) OR
                  (Events.claim_type = 3 AND Events.photographer_id IS NULL)";
                } else if ($_SESSION["speciality"] == "journalist") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 2 AND Events.journalist_id IS NULL) OR
                  (Events.claim_type = 3 AND Events.journalist_id IS NULL)";
                } else if ($_SESSION["speciality"] == "editor") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 1 AND Events.photographer_id IS NULL) OR
                  (Events.claim_type = 2 AND Events.journalist_id IS NULL) OR
                  (Events.claim_type = 3 AND (Events.journalist_id IS NULL OR Events.photographer_id IS NULL))";
                }

                $stmt = $conn->prepare($sql);
                $stmt->bindColumn("event_id", $eventId);
                $stmt->bindColumn("name", $eventName);
                $stmt->bindColumn("description", $description);
                $stmt->bindColumn("event_date", $eventDate);
                $stmt->bindColumn("creation_date", $creationDate);
                $stmt->bindColumn("event_category", $eventCategory);
                $stmt->bindColumn("claim_type", $claimType);
                $stmt->execute();

                $accordionId = 0;
                while ($results = $stmt->fetch()) {
                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='heading$accordionId'>
                      <button class='accordion-button collapsed d-flex' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$accordionId' aria-expanded='true' aria-controls='collapse$accordionId'>
                        #$eventId $eventName <span class='badge text-bg-info mx-1'>$eventCategory</span>
                      </button>
                    </h2>
                    <div id='collapse$accordionId' class='accordion-collapse collapse' aria-labelledby='heading$accordionId' data-bs-parent='#accordionExample2'>
                      <div class='accordion-body'>
                        <p>$description</p>
                        <div class='d-flex flex-wrap gap-2 mb-2'>
                          <div class='input-group flex-nowrap calendarWidth'>
                              <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                              <p class='form-control mb-0'>$creationDate</p>
                          </div>
                          <div class='input-group flex-nowrap calendarWidth'>
                            <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                            <p class='form-control mb-0'>$eventDate</p>
                          </div>
                        </div>
                        <div class='d-flex flex-wrap gap-2'>
                          ". ($_SESSION["speciality"]=="editor" ? "<a href='editEvent?edit=$eventId' class='btn btn-primary' role='button'>Edit</a>" : "") . "
                          <form action='claimEvents' method='POST'>
                            <input type='hidden' name='event_id' value='$eventId'>
                            " . ($_SESSION["speciality"]=="journalist" || $_SESSION["speciality"]=="photographer" ? "<input type='submit' name='submit' class='btn btn-primary' value='Claim Event'>" : "") . "
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  ";
                  $accordionId++;
                }
              ?>
            </div> 
            <h2 class="mt-4 mb-4">Claimed Events</h2>
            <div class="accordion" id="accordionExample3">
              <?php
                // Get all events that are claimed but not by us
                if ($_SESSION["speciality"] == "photographer") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 1 AND Events.photographer_id IS NOT NULL AND Events.photographer_id != :user_id) OR
                  (Events.claim_type = 3 AND Events.photographer_id IS NOT NULL AND Events.photographer_id != :user_id)";
                } else if ($_SESSION["speciality"] == "journalist") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 2 AND Events.journalist_id IS NOT NULL AND Events.journalist_id != :user_id) OR
                  (Events.claim_type = 3 AND Events.journalist_id IS NOT NULL AND Events.journalist_id != :user_id)";
                } else if ($_SESSION["speciality"] == "editor") {
                  $sql = "SELECT * FROM Events WHERE (Events.claim_type = 1 AND Events.photographer_id IS NOT NULL) OR
                  (Events.claim_type = 2 AND Events.journalist_id IS NOT NULL) OR
                  (Events.claim_type = 3 AND (Events.journalist_id IS NOT NULL AND Events.photographer_id IS NOT NULL))
                  AND (Events.photographer_id != :user_id AND Events.journalist_id != :user_id)";
                }

                $stmt = $conn->prepare($sql);
                $stmt->bindValue(':user_id', $_SESSION["user_id"]);
                $stmt->bindColumn("event_id", $eventId);
                $stmt->bindColumn("name", $eventName);
                $stmt->bindColumn("description", $description);
                $stmt->bindColumn("event_date", $eventDate);
                $stmt->bindColumn("creation_date", $creationDate);
                $stmt->bindColumn("event_category", $eventCategory);
                $stmt->execute();

                $claimedId = 0;
                while ($results = $stmt->fetch()) {
                  echo "
                  <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingClaimed$claimedId'>
                      <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapseClaimed$claimedId' aria-expanded='true' aria-controls='collapseClaimed$claimedId'>
                        #$eventId $eventName <span class='badge text-bg-info mx-1'>$eventCategory</span>
                      </button>
                    </h2>
                    <div id='collapseClaimed$claimedId' class='accordion-collapse collapse' aria-labelledby='headingClaimed$claimedId' data-bs-parent='#accordionExample3'>
                      <div class='accordion-body'>
                        <p>$description</p>
                        <div class='d-flex flex-wrap gap-2 mb-2'>
                          <div class='input-group flex-nowrap calendarWidth'>
                              <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                              <p class='form-control mb-0'>$creationDate</p>
                          </div>
                          <div class='input-group flex-nowrap calendarWidth'>
                            <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                            <p class='form-control mb-0'>$eventDate</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  ";
                  $claimedId++;
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
