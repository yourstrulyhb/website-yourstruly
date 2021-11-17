<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register | Yourstruly</title>
   <link rel="stylesheet" href="../assets/css/style-auth.css">

   <script src="https://kit.fontawesome.com/220d6b62fe.js" crossorigin="anonymous"></script>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Poppins:wght@200;300;600&display=swap" rel="stylesheet">
</head>
<?php
include '../components/db_connect.php'; // Connect to database

// Check: username and email are not in DB
// Accept: username and email not in DB
// Else, decline and show error: username and/or email
$reg_username = $reg_email = $reg_pass = "";
$err_username_exists = $err_email_exists = "";
// $register_success = false;

// Verify request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {     
   $reg_username = $_POST['reg_username'];
   $reg_email = $_POST['reg_email'];
   $reg_password = $_POST['reg_password'];

   // Check in DB if username and email doesn't exist

   // Count username in database that are the same with given username
   $username_query = "SELECT COUNT(`username`) AS `total_un` FROM `users`
                  WHERE `username` = '$reg_username'";
   // $user = $conn->query($user_query);
   $username_res = mysqli_query($conn, $username_query);

   // Count email in database that are the same with given email
   $email_query = "SELECT COUNT(`email`) AS `total_email` FROM `users`
                  WHERE `email` = '$reg_email'";
   $email_res = mysqli_query($conn, $email_query);

   // Counts for email and username
   $count_un = $username_res->fetch_array(MYSQLI_ASSOC);
   $count_email = $email_res->fetch_array(MYSQLI_ASSOC);

   // Check total count for username and email
   // If total count for each is zero (0), then user not yet in database => acceptance
   if ($count_un['total_un'] > 0 || $count_email['total_email'] > 0) {  // If either of the two queries returned count > 0
      // If 'username count' > 0
      if ($count_un['total_un'] > 0) {
         $err_username_exists = "Sorry, username already exists. Use another.";  // Set username error message
      }

      // If 'email count' > 0
      if ($count_email['total_email'] > 0) {
         $err_email_exists = "Sorry, email already used. Try another.";    // Set email error message
      }
      // Free up results
      mysqli_free_result($username_res);  
      mysqli_free_result($email_res);

   } else {   // If the two counts were equal to 0, add user to DB
      // Create hash for the password
      $pass_hash = password_hash($reg_password, PASSWORD_DEFAULT);

      // Create query to DB to add user
      $add_user = "INSERT INTO `users` (`admin`, `username`, `email`, `password`) VALUES ('0', '$reg_username', '$reg_email', '$pass_hash')";   // admin = 0 to not allow user have admin privileges

      // Submit the query
      if ($register_user = mysqli_query($conn, $add_user)) {

         mysqli_close($conn);
         header("Location: register_success.php");    // Direct user to page telling they have successfully registered
         die();
      }
   }
}
?>
<body>
   <?php include '../components/header.php'; ?>

   <div class="auth-container" id="register-container">
      <h2>Register</h2>
      <form name="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class>
         <div class="form-fields">
            <label for="Username">Username</label>
            <input type="text" name="reg_username" class="text-input" placeholder="name0123" value="<?php echo $reg_username; ?>" required>
            <label for="input-error" class="error-labels" id="err-existing-username"><?php echo $err_username_exists; ?></label>
         </div>
         <div class="form-fields">
            <label for="Email">Email</label>
            <input type="email" name="reg_email" class="text-input" placeholder="user@domain.com" value="<?php echo $reg_email; ?>" required>
            <label for="input-error" class="error-labels" id="err-existing-email"><?php echo $err_email_exists; ?></label>
         </div>
         <div class="form-fields">
            <label for="Password">Password</label>

            <div class="password-wrapper">
               <div class="password-container">
                  <input type="password" id="auth_pass" name="reg_password" class="text-input" oninput="passwordStrengthChecker()" required>
                  <span id="password-toggle" onclick="passwordToggle()">
                     <i class="fas fa-eye"></i>
                  </span>
               </div>
               <div id="password-check-bar"></div>
            </div>
            <label for="passwordRequirement">*min. of 8 characters, with atleast 1 capital letter, a number, and a symbol</label>
         </div>
         <div>
            <button type="submit" name="register-button" id="register-btn" disabled>Register</button>
         </div>
         <p>Already a member? <a href="login.php">Login here</a>.</p>
      </form>
   </div>
   <!-- <div class="confirm-container" id="confirm-registration">
      <h2>Registration success!</h2>
      <h3>You may now <a href="login.php">login</a> to the system.</h3>
   </div> -->
   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
   <!-- JS -->
   <script src='../assets/js/auth.js'></script>
</body>
</html>