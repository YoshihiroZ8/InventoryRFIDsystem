<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
/// back to login page
echo "<script> alert('Logout successful, Thank You');window.location='loginpage.php'</script>";
exit;
?>