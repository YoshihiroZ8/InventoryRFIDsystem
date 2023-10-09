<?php include('connection.php'); 

	
	$query = $con->query("SELECT * FROM users WHERE fname LIKE '%$search%' or lname  LIKE '%$search%'");
	if ($query->num_rows > 0){ 
	while($row = $query->fetch_assoc()){
	$results = $row['name']." ".$row['email'];
        }
    }

?>

