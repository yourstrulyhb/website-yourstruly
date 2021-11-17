<?php session_start(); // Start session so other variables can be set later ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | Yourstruly</title>
   <link rel="stylesheet" href="../assets/css/style-auth.css">

   <script src="https://kit.fontawesome.com/220d6b62fe.js" crossorigin="anonymous"></script>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Poppins:wght@200;300;600&display=swap" rel="stylesheet">
</head>
<?php
include '../components/db_connect.php';      // Connect to DB, for checking that user is registered  

$login_user = $login_pass =  NULL;     // Username can be username|email
$err_invalid_username = $err_invalid_password = "";

// Verify request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $login_user = $_POST['login_username'];
   $login_pass = $_POST['login_password'];

   // Get user w/ same username or email from DB
   $user_query = "SELECT `username`, `email`, `password` FROM `users`
                     WHERE `username` = '$login_user' or `email` = '$login_user'";
   $user = $conn->query($user_query);

   // Check if query result is not empty
   $user_arr = $user->fetch_array(MYSQLI_ASSOC);

   if (!isset($user_arr)) {      // If array returned is empty = username/email not in DB
      $err_invalid_username = "Can't find username/email. Please try again.";    // Set invalid username error

   } else if (password_verify($login_pass, $user_arr['password'])) {    // If username exists & password valid, log in user
      $err_invalid_username = $err_invalid_password = "";    // Ensure error messages are unset

      // Set session variables
      $_SESSION['username'] = $user_arr['username'];  // Initialize username of current user session
      $_SESSION['last_login_timestamp'] = time();  // Initialize 'last login time' or 'last activity time'

      // Direct user to index.php
      $conn->close();
      header("Location: ../index.php");
      die();

   } else {    // If username was found but password is invalid
      $err_invalid_password = "Invalid password. Please try again.";    // Set invalid password error
   }
}
?>
<body>
   <?php include '../components/header.php'; ?>

   <div class="auth-container">
      <h2>Login</h2>

      <form name="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
         <!-- Input registered username/email -->
         <div class="form-fields">
            <label for="Username">Username or email</label>
            <input type="text" name="login_username" class="text-input" placeholder="name0123" required>
            <label for="input-error" class="error-labels" id="err-invalid-username">
               <?php echo $err_invalid_username ?>
            </label>
         </div>
         <!-- Input valid password -->
         <div class="form-fields">
            <label for="Password">Password</label>
            <div class="password-wrapper">
               <div class="password-container">
                  <input type="password" id="auth_pass" name="login_password" class="text-input" required>
                  <span id="password-toggle" onclick="passwordToggle()">
                     <i class="fas fa-eye"></i>
                  </span>
               </div>
            </div>
            <label for="input-error" class="error-labels" id="err-invalid-password">
               <?php echo $err_invalid_password ?>
            </label>
         </div>
         <div>
            <button type="submit" name="login-button" id="login-btn">Login</button>
         </div>
         <!-- Allow register new user -->
         <p>Not a member? <a href="register.php">Sign up now</a>.</p>
      </form>
   </div>

   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
   <!-- JS -->
   <script src='../assets/js/auth.js'></script>
</body>
</html>