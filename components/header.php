<header>
   <div class="logo">
      <a href="<?php
               if (isset($_SESSION['username'])) {   
                  echo 'index.php';
               } else {    // Is no user logged in, display login only
                  echo '../index.php';
               } ?>">
         <h1>Yourstruly</h1>
      </a>
      <!-- Responsiveness -->
   </div>
   <i class="fas fa-bars menu-toggle"></i>
   <ul class="nav">
      <li><a href="#">About</a></li>
      <li><a href="#">Blog</a></li>
      <li><a href="#">Contact</a></li>
      <li>
         <?php
         if (isset($_SESSION['username'])) {    // Is a user is logged in, show their username on nav. bar
            echo '<a href="#">' . $_SESSION['username'] . ' <i class="fas fa-chevron-down" style="font-size: 15px; margin: 2px;" id="dropdown-icon"></i>';
         } else {    // Is no user logged in, display login only
            echo '<a href="login.php">Login';
         } ?> &nbsp;

         <?php
         echo "</a>";
         if (isset($_SESSION['username'])) {    // If user logged in, show additional user options including logout option
            echo '<ul class="user-dropdown">
                        <li><a href="#">Dashboard</a></li>
                        <li><a href="#">Settings</a></li>
                        <li><a href="pages/logout.php" class="logout">Logout</a></li>
                     </ul>';
         }
         ?>
      </li>
   </ul>
</header>