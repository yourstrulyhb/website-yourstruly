<header>
   <div class="logo">
      <a href="index.php">
         <h1>Yourstruly</h1>
      </a>
      <!-- Shown only when responsive -->
      <i class="fas fa-bars menu-toggle"></i>
   </div>
   <ul class="nav">
      <li><a href="#">About</a></li>
      <li><a href="#">Blog</a></li>
      <li><a href="#">Contact</a></li>
      <li>
         <a href="#">
            <?php echo $_SESSION['username'];?>&nbsp;
            <i class="fas fa-chevron-down" style="font-size: 15px; margin: 2px;" id="dropdown-icon"></i>
         </a>
         <ul class="user-dropdown">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="logout.php" class="logout">Logout</a></li>
         </ul>
      </li>
   </ul>
</header>