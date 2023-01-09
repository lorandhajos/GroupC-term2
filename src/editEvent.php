<?php
  session_start();
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
  }

  include("config.php");

  if(isset($_GET['edit'])) {
    if (is_numeric($_GET['edit'])) {
      $id = $_GET['edit'];
    } else {
      header("Location: home.php");
    }
  }
  

  $stmt = $conn->prepare("SELECT * FROM Events WHERE event_id=:id");

  $stmt->bindParam("id", $id, PDO::PARAM_INT);
  $stmt->execute();
  
  $stmt->bindColumn("name", $eventName);
  $stmt->bindColumn("description", $description);
  $stmt->bindColumn("event_date", $eventDate);

  $stmt->fetch();

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $editedName = filter_input(INPUT_POST, "eventName");
    $editedDate = filter_input(INPUT_POST, "eventDate");
    $editedDescription = filter_input(INPUT_POST, "description");

    //if (some checks) {
        $stmt = $conn->prepare("UPDATE Events SET `name`=:editedName, `description`=:editedDescription, event_date=:editedDate WHERE event_id=:id");

        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->bindParam("editedName", $editedName, PDO::PARAM_STR);
        $stmt->bindParam("editedDate", $editedDate, PDO::PARAM_STR);
        $stmt->bindParam("editedDescription", $editedDescription, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: home.php");

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <title>Edit Event</title>
</head>
<body>
  <main>
    <div>
      <div class="row m-0">
        <div class="col-auto p-0">
          <nav class="sidebar">
            <?php include "navMenu.php" ?>
          </nav>
        </div>
        <div class="col p-0">
          <header class="py-4 shadow-sm">
          </header>
          <form class="needs-validation p-3" novalidate method="POST" action="">
            <h4 class="mb-3">Event Details</h4>
            <div class="row g-3">
              <div class="col-sm-6">
                <label for="eventTitle" class="form-label">Event Title</label>
                <input type="text" class="form-control" name ="eventName" id="eventTitle" value="<?php echo $eventName; ?>" required>
              </div>
              <div class="col-sm-6">
                <label for="eventDate" class="form-label">Date</label>
                <input type="text" class="form-control" name ="eventDate" id="eventDate" value="<?php echo $eventDate; ?>" required>
              </div>
              <div class="col-12">
                <label for="eventDesc" class="form-label">Details</label>
                <textarea class="form-control" id="eventDesc" name ="description" rows="5"><?php echo $description; ?></textarea>
              </div>
              <div class="col-md-5">
                <label for="eventCategory" class="form-label">Event Category</label>
                <select class="form-select" id="eventCategory" required>
                  <option value="">Not Specified</option>
                  <option>Sports</option>
                  <option>Politics</option>
                  <option>Disasters</option>
                  <option>Health</option>
                </select>
              </div>
            </div>
            <hr class="my-4">
            <h4 class="mb-3">Claims</h4>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="reqJournalists">
              <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="reqPhotographers">
              <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
            </div>
            <hr class="my-4">
            <button class="w-100 btn btn-primary btn-lg" type="submit">Create Event</button>
          </form>
          <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar ">
            <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
          </footer>
        </div>
      </div> 
    </div>
  </main>
</body>
</html>

</body>