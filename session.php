<?php
include('connection.php');

session_start();
if (!isset($_SESSION['id'])){
header('location:loginpage.php');
}
$session_id = $_SESSION['id'];
$session_query = $con->query("select * from users where userID = '$session_id'");
$user_row = $session_query->fetch_assoc();
$username = $user_row['username']." ".$user_row['user_name'];

?>