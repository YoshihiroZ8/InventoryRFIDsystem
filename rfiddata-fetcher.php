<?php
include('connection.php');

// Query the database for all RFID data
$sql = "SELECT * FROM rfid ORDER BY logdate DESC"; 

$result = $con->query($sql);

if ($result->num_rows > 0) {
    // Initialize an array to store all RFID data
    $rfidData = array();

    // Fetch each row from the result set and add it to the array
    while ($row = $result->fetch_assoc()) {
        $rfidData[] = array(
            'card_data' => $row['tag_ID'],
            'logdate' => $row['logdate'],
            'product_type' => $row['product_type'],
            'stockroom_no' => $row['stockroom_no']
        );
    }

    // Return the array as JSON
    echo json_encode($rfidData);
} else {
    // No RFID data available
    echo json_encode(array());
}

$con->close();
?>