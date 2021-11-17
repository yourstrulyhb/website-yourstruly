<?php
session_start(); // Start session to get variables set in index.php

$notify_logout = "Logged out successfully.";
if (isset($_SESSION['inactive_user'])) {  // If user was logged out due to inactivity
   $notify_logout = "You were logged out due to inactivity.";    // Tell user they were logged out due to inactivity
}
unset($_SESSION['username']);    // Unset username so it doesn't show in nav. bar
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Logout | Yourstruly</title>
   <link rel="stylesheet" href="../assets/css/style-logout.css">

   <script src="https://kit.fontawesome.com/220d6b62fe.js" crossorigin="anonymous"></script>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Poppins:wght@200;300;600&display=swap" rel="stylesheet">
</head>
<body>
   <?php include '../components/header.php'; ?>

   <div class="logout-message">
      <h3><?php echo $notify_logout; ?></h3>
      <br>
      <p>Login anytime to join again.</p>
   </div>

   <?php
   $_SESSION = array();    // Unset all session variables
   session_destroy();   // Destroy session
   ?>

   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
   <!-- JS -->
   <script src='assets/js/responseScripts.js'></script>
   <script src='assets/js/register.js'></script>
</body>

</html>