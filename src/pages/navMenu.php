<div class="d-flex flex-column flex-shrink-0 text-bg-dark h-100 justify-content-between">
  <div>
    <div class="brandlink">
      <a href="home" class="d-flex m-3 text-white text-decoration-none">
        <span class="fs-4"><img src="../images/bootstrap-logo.svg" alt="G" width="44" height="44">emorskos</span>
      </a>
    </div>
    <div>
      <div class="profile">
        <a href="profile" class="d-flex align-items-center text-white text-decoration-none p-3">
          <?php echo "<strong>" . $_SESSION['name'] . "</strong>";?>
        </a>
      </div>
      <div class="brandlink"></div>
      <ul class="nav nav-pills flex-column mx-2 my-1">
        <li class="nav-item">
          <a href="home" class="nav-link">Events</a>
        </li>
        <li class="nav-item">
          <a href="newEvent" class="nav-link">Create event</a>
        </li>
        <?php
          if ($_SESSION["speciality"] == "editor") {
            echo '
            <li class="nav-item">
              <a href="manageAccounts" class="nav-link">Manage accounts</a>
            </li>
            <li class="nav-item">
              <a href="createAccount" class="nav-link">Create account</a>
            </li>'; 
          }
        ?>
      </ul>
    </div>
  </div>
  <div class="d-flex align-items-end">
    <a href="pages/logout.php" class="nav-link signOutText p-3">
      Sign Out <i class="fa-solid fa-right-from-bracket"></i>
    </a>
  </div>
</div>
