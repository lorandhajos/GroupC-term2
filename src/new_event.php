<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php include "head.php" ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
  <div class="container">
    <header class="py-5 text-center">
      <div class="mb-4 d-flex justify-content-center align-items-center">
        <img src="images/bootstrap-logo.svg" alt="Gemorskos logo" width="72" height="57">
        <h2>emorskos</h2>
      </div>
      <h2>Create New Event</h2>
    </header>
    <main>
    	<div class="col-md-7 col-lg-8">
        <form class="needs-validation" novalidate method="POST" action="new_event.php">
          <h4 class="mb-3">Event Details</h4>
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

            <div class="col-md-5">
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
      </div> 
    </main>

    <footer class="py-3 mt-3 d-flex justify-content-end shadow border-top">
      <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
    </footer>
  </div>
</body>
</html>