<?php
  session_start();

  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /");
  }

  if ($_SESSION["speciality"] != "editor") {
    header("location: /");
  }

  include_once("pages/config.php");

  if(isset($_GET['edit'])) {
    if (is_numeric($_GET['edit'])) {
      $id = $_GET['edit'];
    } else {
      header("Location: /home");
    }
  }

  $stmt = $conn->prepare("SELECT * FROM Events WHERE event_id=:id");

  $stmt->bindParam("id", $id, PDO::PARAM_INT);
  $stmt->execute();
  
  $stmt->bindColumn("name", $eventName);
  $stmt->bindColumn("description", $description);
  $stmt->bindColumn("event_date", $eventDate);

  $stmt->fetch();
  $err = "";

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editedName = filter_input(INPUT_POST, "eventName", FILTER_SANITIZE_SPECIAL_CHARS);
    $editedDate = filter_input(INPUT_POST, "eventDate", FILTER_SANITIZE_NUMBER_INT);
    $editedDescription = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
    $editedCategory = filter_input(INPUT_POST, "eventCategory", FILTER_SANITIZE_SPECIAL_CHARS);
    $reqJournalists = filter_input(INPUT_POST, "reqJournalists", FILTER_SANITIZE_SPECIAL_CHARS);
    $reqPhotographers = filter_input(INPUT_POST, "reqPhotographers", FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($editedName)) {
      $err = "Please enter a title for the event";
    } elseif (empty($editedDate)) {
      $err = "Please provide the date for the event";
    } elseif (empty($editedDescription)) {
      $err = "Please provide a short event description";
    } elseif (empty($editedCategory)) {
      $err = "Please provide an event category";
    } elseif (!($reqJournalists || $reqPhotographers)) {
      $err = "Either the journalists or the photographers should be able to claim the event";
    }
    if (!$err) {
      $photographerClaim = $reqPhotographers ? $claimVacant : $claimRestricted;
      $journalistClaim = $reqJournalists ? $claimVacant : $claimRestricted;

      $stmt = $conn->prepare("UPDATE Events SET `name`=:editedName, `description`=:editedDescription, `event_date`=:editedDate, `event_category`=:category, `journalist_id`=:journalist_claim, `photographer_id`=:photographer_claim WHERE event_id=:id;");

      $stmt->bindParam("id", $id, PDO::PARAM_INT);
      $stmt->bindParam("editedName", $editedName, PDO::PARAM_STR);
      $stmt->bindParam("editedDate", $editedDate, PDO::PARAM_STR);
      $stmt->bindParam("category", $editedCategory, PDO::PARAM_STR);
      $stmt->bindValue("journalist_claim", $journalistClaim, PDO::PARAM_INT);
      $stmt->bindValue("photographer_claim", $photographerClaim, PDO::PARAM_INT);
      $stmt->bindParam("editedDescription", $editedDescription, PDO::PARAM_STR);
      $stmt->execute();

      header("Location: /home");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "pages/head.php" ?>
  <title>Edit Event</title>
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
            <form class="needs-validation" novalidate method="POST" action="">
              <h2 class="my-4">Event Details</h2>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label for="eventTitle" class="form-label">Event Title</label>
                  <input type="text" class="form-control" name="eventName" id="eventTitle" value="<?php echo $eventName; ?>">
                </div>
                <div class="col-sm-6">
                  <label for="eventDate" class="form-label">Date</label>
                  <input type="date" class="form-control" name="eventDate" id="eventDate" value="<?php echo $eventDate; ?>">
                </div>
                <div class="col-12">
                  <label for="eventDesc" class="form-label">Details</label>
                  <textarea class="form-control" id="eventDesc" name="description" rows="5"><?php echo $description; ?></textarea>
                </div>
                <div class="col-md-6">
                  <label for="eventCategory" class="form-label">Event Category</label>
                  <select class="form-select" name="eventCategory">
                    <option value="sports">Sports</option>
                    <option value="politics">Politics</option>
                    <option value="disasters">Disasters</option>
                    <option value="health">Health</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Claims</label>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="reqJournalists" value="y">
                    <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="reqPhotographers" value="y">
                    <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
                  </div>
                </div>
                <button class="w-auto mt-3 btn btn-primary btn-lg" name="submit" type="submit">Change Event</button>
                <?php
                  if (isset($_POST["submit"]) && $err) {
                    echo '<div class="alert alert-danger">' . $err . '</div>';
                  }
                ?>
              </div>
            </form>
          </div>
        </div>
        <footer class="py-3 d-flex justify-content-end shadow border-top navbar ">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
