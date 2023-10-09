<?php
	$dbHost = 'localhost';
	$dbName = 'fyp_ims';
	$dbUsername = 'root';
	$dbPassword = '';
	
    $con = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
    if ( mysqli_connect_errno() ) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
?>