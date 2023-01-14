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
          <div class="mx-5">
            <?php
              $targetColumn = "";
              if ($_SESSION["speciality"] === "journalist") {
                $targetColumn = "journalist_id";
              } elseif ($_SESSION["speciality"] === "photographer") {
                $targetColumn = "photographer_id";
              }
              if (!$targetColumn) {
                $unclaimedSQL = "SELECT * FROM Events WHERE (photographer_id = $claimVacant OR photographer_id = $claimRestricted) AND (journalist_id = $claimVacant OR journalist_id = $claimRestricted);";
                $claimedSQL = "SELECT * FROM Events WHERE (photographer_id <> $claimVacant AND photographer_id <> $claimRestricted) OR (journalist_id <> $claimVacant AND journalist_id <> $claimRestricted);";
              } else {
                $unclaimedSQL = "SELECT * FROM Events WHERE $targetColumn = $claimVacant";
                $claimedSQL = "SELECT * FROM Events WHERE NOT $targetColumn = $claimVacant AND NOT $targetColumn = $claimRestricted;";
                echo '
                  <h2 class="my-4">Your Events</h2>
                  <div class="accordion" id="accordionExample1">';
                $stmt = $conn->prepare("SELECT * FROM Events WHERE $targetColumn = :user_id;");
                $stmt->bindValue("user_id", $_SESSION['user_id']);
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
                            <div class='d-flex justify-content-between'>
                              <div class='d-flex'>
                                <div class='input-group flex-nowrap me-2'>
                                    <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                                    <p class='form-control mb-0'>$creationDate</p>
                                </div>
                                <div class='input-group flex-nowrap'>
                                  <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                                  <p class='form-control mb-0'>$eventDate</p>
                                </div>
                              </div>
                              <form action='home' method='post' enctype='multipart/form-data'>
                                <input type='file' name='fileToUpload' id='fileToUpload' multiple>
                                <input type='submit' value='Upload' name='submit' class='btn btn-primary'>
                                <input type='hidden' name='eventId' value='$eventId'>
                              </form>
                          </div>
                        </div>
                      </div>
                    </div>";
                  $yourEventId ++;
                } 
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                  $eventId = filter_input(INPUT_POST, 'eventId', FILTER_VALIDATE_INT);
                  $target_dir = 'uploads/' . $eventId . '/';
                  $target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
                  $error = '';
                  $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                  //connection to make an array with all the eventid's of the events that you have claimed
                  $stmt = $conn->prepare("SELECT Claims.event_id FROM Claims INNER JOIN Users ON Claims.user_id = Users.user_id INNER JOIN Events ON Claims.event_id = Events.event_id WHERE Users.user_id = :id");
                  $stmt->bindValue("id", $_SESSION["user_id"]);
                  $stmt->execute();
                  $test = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

                  //check if a file was selected
                  if ($_FILES['fileToUpload']['size'] == 0 && empty($_FILES['fileToUpload']['name']) ){
                    $error = "No file was selected";
                  } else {
                    //check if you are assigned to the event, so whether or not the value hasnt been changed with f12
                    if(!in_array($eventId, $test)) {
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
                      $error = "Sorry, file already exists. ";
                    }

                    //Check whether the file size is above 128mb
                    if ($_FILES["fileToUpload"]["size"] > 128000000) {
                      $error = "Sorry, your file is too large, maximum filesize is 128mb. ";
                    }

                    //Allow certain file formats
                    if($FileType != "jpg" && $FileType != "png" && $FileType != "jpeg"
                    && $FileType != "docx" && $FileType != "txt" && $FileType != "odt") {
                      $error = "Sorry, your files are not allowed. ";
                    }                    
                  }

                  if (!empty($error)) {
                    echo "$error";
                  } else {  
                    //if error is empty, try to upload file
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                    }
                  }
                }
                //echo '</div>';
              }
            ?>
            <h2 class="mt-4 mb-4">Unclaimed Events</h2>
            <div class="accordion" id="accordionExample2">
              <?php
                $stmt = $conn->prepare($unclaimedSQL);
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
                        <div class='d-flex justify-content-between'>
                            <div class='d-flex'>
                              <div class='input-group flex-nowrap me-2'>
                                  <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                                  <p class='form-control mb-0'>$creationDate</p>
                              </div>
                              <div class='input-group flex-nowrap'>
                                <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                                <p class='form-control mb-0'>$eventDate</p>
                              </div>
                            </div>
                          <form action='claimevents.php' method='POST'>
                            <input type='hidden' name='event_id' value='$eventId'>
                            <input type='submit' name='submit' class='btn btn-primary' value='Claim Event'>
                          </form>
                        </div>" . ($_SESSION["speciality"]=="editor" ? "<a href='editEvent?edit=$eventId' class='btn btn-primary mt-2' role='button'>Edit</a>" : "") . "
                      </div>
                    </div>
                  </div>
                  ";
                  $accordionId ++;
                }
              ?>
            </div> 
            <h2 class="mt-4 mb-4">Claimed Events</h2>
            <div class="accordion" id="accordionExample3">
              <?php
                $stmt = $conn->prepare($claimedSQL);
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
                        <div class='d-flex justify-content-between'>
                          <div class='d-flex'>
                            <div class='input-group flex-nowrap me-2'>
                                <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-plus'></i></span>
                                <p class='form-control mb-0'>$creationDate</p>
                            </div>
                            <div class='input-group flex-nowrap'>
                              <span class='input-group-text' id='addon-wrapping'><i class='fa-regular fa-calendar-days'></i></span>
                              <p class='form-control mb-0'>$eventDate</p>
                            </div>
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
