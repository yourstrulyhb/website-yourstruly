<?php
include 'db_connect.php';

$topicsQuery = "SELECT `name` FROM `topics` ORDER BY `name`;";
$topicsResult = $conn -> query($topicsQuery);
?>