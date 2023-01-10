<?php
  session_start();

  // check if the user is logged in
  if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../index.php");
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
                  <input type="text" class="form-control" id="eventTitle" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Event title is required.
                  </div>
                </div>
                <div class="col-sm-6">
                  <label for="eventDate" class="form-label">Date</label>
                  <input type="date" class="form-control" id="eventDate" placeholder="" value="" required>
                  <div class="invalid-feedback">
                    Date is required.
                  </div>
                </div>
                <div class="col-12">
                  <label for="eventDesc" class="form-label">Details</label>
                  <textarea class="form-control" id="eventDesc" rows="5"> </textarea>
                  <div class="invalid-feedback">
                    Please provide more details regarding the event.
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="eventCategory" class="form-label">Event Category</label>
                  <select class="form-select" id="eventCategory" required>
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
                  <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="reqJournalists">
                  <label class="form-check-label" for="reqJournalists">Allow journalists to claim the event</label>
                </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="reqPhotographers">
                <label class="form-check-label" for="reqPhotographers">Allow photographers to claim the event</label>
              </div>
              </div>
              <button class="w-auto mt-3 btn btn-primary btn-lg" type="submit">Create Event</button>
              </div>
            </form>
          </div>
        </div>
        <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top navbar ">
          <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
        </footer>
      </div>
    </div>
  </main>
</body>
</html>
