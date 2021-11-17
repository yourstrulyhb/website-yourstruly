<!--  Yourstruly by Hannah Bella Arceño
      A Web Development/Engineering Project
-->
<?php session_start(); // Start session to access session variables set from login.php 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Blog | Yourstruly</title>
   <link rel="icon" href="assets/images/favicon.png">
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="description" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="assets/css/style.css">
   <link rel="stylesheet" href="assets/css/responsive.css">

   <script src="https://kit.fontawesome.com/220d6b62fe.js" crossorigin="anonymous"></script>
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Chewy&family=Poppins:wght@200;300;600&display=swap" rel="stylesheet">
</head>
<?php

include 'components/db_connect.php'; // Connect to DB

// Set topicID if user searched by topic
$searchTerm = NULL;
$topicID = NULL;
if (isset($_GET['query']) and trim($_GET['query']) != '') {
   
   $searchTerm = $_GET['query'];
   if (str_starts_with($searchTerm, "topic:")) {
      $searchTerm = ltrim($searchTerm, "topic:");
      $topicIDQuery = "SELECT `id` FROM `topics` WHERE `name` = '$searchTerm' LIMIT 1;";  //Get topic ID from topic name
      $topicIDRes = $conn->query($topicIDQuery);
      $id = $topicIDRes->fetch_assoc();

      $topicID = $id['id'];   // Set topicID
   }
}

// Get posts with topicID similar to DB topic_id
$posts = NULL;
$posts_query = "";
if (isset($topicID)) {
   $posts_query = "SELECT * FROM `posts` AS P,`users` AS U WHERE P.user_id = U.id and `topic_id` = '$topicID' ORDER BY `publishDate` DESC;";
   
} elseif (isset($searchTerm)) {
   // If topicID unset, but 'search term'specified
   // Find posts using the 'search term' in author's name, title, and/or blog content
   $posts_query = "SELECT * FROM `posts` AS P,`users` AS U WHERE P.user_id = U.id and (`title` LIKE '%$searchTerm%' OR `body` LIKE '%$searchTerm%' OR U.username LIKE '%$searchTerm%') ORDER BY `publishDate` DESC;";
   
} else {
   // If topicID and searchTerm unspecified, get 3 recent posts
   $posts_query = "SELECT * FROM `posts` AS P,`users` AS U WHERE P.user_id = U.id ORDER BY publishDate DESC LIMIT 3;";
}
$posts = $conn->query($posts_query); // Get posts
?>
<body>
   <?php
   include 'components/header.php';

   // Session handling: logout after 5 mins. of inactivity
   // Check if a user is logged in
   if (isset($_SESSION["username"])) {       // If a user is logged in

      // Check current time with 'last activity timestamp' of user
      if ((time() - $_SESSION['last_login_timestamp']) > 300) {   // If 'current time' - 'last activity time' > 5 mins. (5 mins. * 60 s = 300 s)
         $_SESSION['inactive_user'] = true;     // For determining if user was logged out because of inactivity
         header("location: pages/logout.php");     // Logout user, redirect to logout page
         die();

      } else {
         $_SESSION['inactive_user'] = null;
         $_SESSION['last_login_timestamp'] = time();     // Update last activity time
      }

   } else {      // If no user is logged in
      header('location: pages/login.php');   // Redirect user to login page
   }
   ?>
   <div class="content">
      <div class="blogs clearfix" id="recent-blogs">
         <!-- <div class="content clearfix"> -->
         <h1 class="recent-post-title" id="current-blogs">
            <?php
            if (isset($searchTerm) and $posts == true and mysqli_num_rows($posts) > 0) {
               echo "Showing results for: " . "<i>'" . $searchTerm . "'</i>";
            } elseif (isset($searchTerm) and $posts == true and mysqli_num_rows($posts) == 0) {
               echo "No blogs related to: " . "<i>'" . $searchTerm . "'</i>";
            } else {
               echo "Most Recent";
            } ?>
         </h1>
         <div class="main-content">
            <?php if ($posts and mysqli_num_rows($posts) > 0) {
               while ($post = $posts->fetch_assoc()) { ?>

                  <div class="blog-post">
                     <div class="blog-image-holder">
                        <img src=<?php echo $post['image']; ?> alt="" class="post-image">
                     </div>
                     <h2 class="blog-title" title="<?php echo $post['title']; ?>"><a href="#"><?php echo $post['title']; ?></a></h2>
                     <div class="blog-details">
                        <div class="blog-metadata">
                           <i class="far fa-user"></i> &nbsp;
                           <span class="author"><?php echo $post['username']; ?></span>&nbsp; &nbsp; &nbsp;
                           <i class="far fa-calendar-alt"></i>&nbsp;
                           <span class="publish-date"><?php echo date('F j, Y', strtotime($post['publishDate'])); ?></span>
                        </div>
                        <p class="preview-text" cite=<?php echo $post['username']; ?>>
                           <?php echo html_entity_decode(substr($post['body'], 0, 350) . '...'); ?>
                        </p>
                     </div>
                     <div class="read-more">
                        <a href="#" class="btn" id="read-more-btn">Read more</a>
                     </div>
                  </div>
               <?php }
            } else { ?> <div style="height: 250px;"></div>
            <?php } ?>
         </div>
      </div>

      <aside>
         <form id="searchForm" name="searchForm" class="wrapper-search-bar" action='index.php' method='get'>
            <div class="search-bar">
               <input type="search" name="query" placeholder="Search blog title" class="search-term" id="search-box">
               <button type="button" class="btn" id="search-button" onclick=submitSearch()>
                  <i class="fa fa-search"></i>
               </button>
            </div>
         </form>
         <div class="wrapper-topics-dropdown">
            <div class="dropdownbox">
               <p>Search by topic</p>
            </div>
            <ul class="menu">
               <?php include 'components/topics.php';
               while ($topic = $topicsResult->fetch_assoc()) { ?>
                  <li><?php echo $topic['name']; ?></li>
               <?php } ?>
            </ul>
         </div>
      </aside>
   </div>

   <footer>
      <div class="footer-content">
         <div class="about">
            <h1 class="logo-text">Yourstruly</h1>
            <p><strong>Yourstruly</strong> is a website designed by Hannah Bella C. Arceño for an academic project.</p>
            <div class="contact">
               <i class="fas fa-phone"></i> &nbsp; +639 163 010 790<br>
               <i class="fas fa-envelope"></i> &nbsp; yourstrulyhb@gmail.com</span>
            </div>
            <div class="socials">
               <a href="#"><i class="fab fa-facebook"></i></a>
               <a href="#"><i class="fab fa-instagram"></i></a>
               <a href="#"><i class="fab fa-twitter"></i></a>
               <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
         </div>
         <div class="links">
            <h2>Site Pages</h2>
            <ul>
               <li><a href="#">Home</a></li>
               <li><a href="#">About</a></li>
               <li><a href="blog.html">Blog</a></li>
               <li><a href="#">Contact</a></li>
            </ul>
         </div>
         <!-- Contact Form -->
         <?php include 'components/feedback.php'; ?>
      </div>
      <div class="footer-bottom">
         &copy; 2021 yourstrulyhb.com | designed by Hannah Bella C. Arceño
      </div>
   </footer>

   <!-- JQuery -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous"></script>
   <!-- JS -->
   <script src='assets/js/responseScripts.js'></script>
   mysqli_close($conn);
</body>
</html>