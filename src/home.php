<?php
$host = $_ENV["DB_SERVER"];
$user = $_ENV["DB_USER"];
$pass = $_ENV["DB_PASSWORD"];
$db = $_ENV["DB_NAME"];

try {
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e) {
  echo $e->getMessage();
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <title>Home Page</title>
</head>
<body>
  <header class="py-4 shadow-sm">

  </header>
  <main>
    <div class="container">
      <h1 class="mt-4 mb-4">Your Events</h1>
      <!-- Add php to display your events -->
      <h1 class="mt-4 mb-4">Unclaimed Events</h1>
      <div class="accordion" id="accordionExample2">
        <?php
        $stmt = $conn->prepare("SELECT * FROM Events");

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
              <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse$accordionId' aria-expanded='true' aria-controls='collapse$accordionId'>
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
                  <a href='home.php?edit=$eventId' class='btn btn-primary' role='button'>Claim Event</a>
                </div>
              </div>
            </div>
          </div>
          ";
          $accordionId ++;
        }

        ?>
      </div> 
      <h1 class="mt-4 mb-4">Claimed Events</h1>
      <!-- Add php to display claimed events -->
    </div>
  </main>
  <footer class="py-3 mt-5 d-flex justify-content-end shadow border-top">
    <p class="mb-0 me-4">Copyright 2022 - Gemorskos. All rights reserved</p>
  </footer>
</body>
</html>
