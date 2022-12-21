<div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark brandlink">
  
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-4"><img src="../images/bootstrap-logo.svg" alt="G" width="44" height="44">emorskos</span>
    </a>

</div>
  <div class="d-flex flex-column flex-shrink-0 text-bg-dark h-100 justify-content-between ">
  <!-- <hr> -->
  <div>
  <div class="profile ">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none p-3">
      <?php echo "<strong>" . $_SESSION['name'] . "</strong>";?>
    </a>
  </div>
  <!-- <hr> -->
  <div class="brandlink"></div>
  <ul class="nav nav-pills flex-column">
    <li class="nav-item">
      <a href="../home.php" class="nav-link text-white" aria-current="page">
        Events
      </a>
    </li>
    <li>
      <a href="../pages/newEvent.php" class="nav-link text-white">
        Create event
      </a>
    </li>
  </ul>
</div>
  <div class="d-flex align-items-end">
    <a href="../logout.php" class="nav-link text-white signOutText pb-5 mb-5 p-3">
      Sign Out
      <i class="fa-solid fa-right-from-bracket"></i>
    </a>
  </div>
</div>
<!-- 
HELP IT WORKS IN CHROME, NOT IN FIREFOX. ALSO THE NEW EVENT PAGE IS ALSO STILL FUCKED !!!!!!!!!!!!!!!!!!!!!
 -->