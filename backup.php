<?php

include('connection.php');   

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

// Create a timestamp
$time = date("Y-m-d H:i:s");
$register_n = "No"; 


if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        
        // Get data from the POST request
        $card_data = $_POST['card_data'];
        $reader = $_POST['reader'];
        $action = $_POST['action']; // Add this line to get the action parameter

        if ($action == "Checkout") {
            // Handle the "check_out" action
            $sql_update = "UPDATE rfid SET availability = 'Checked Out', checkout_d = '" . $time . "' WHERE tag_ID = '" . $card_data . "'";
            if ($con->query($sql_update) === TRUE) {
                // Successfully updated the tag status to "checked out"
                $response = array("status" => "success", "message" => "Tag checked out successfully");
                echo json_encode($response);
            } else {
                // Error updating the tag status
                $response = array("status" => "error", "message" => "Error updating tag status: " . $con->error);
                echo json_encode($response);
            }
        } else {
            // Handle the "insert_or_update" action (as before)
            // Check if the tag ID already exists in the database
            $sql_check = "SELECT * FROM rfid WHERE tag_ID = '" . $card_data . "'";
            $result_check = $con->query($sql_check);

            if ($result_check->num_rows > 0) {
                // The tag ID already exists, update the stockroom location
                $sql_update = "UPDATE rfid SET stockroom_no = '" . $reader . "', logdate = '" . $time . "' WHERE tag_ID = '" . $card_data . "'";
                if ($con->query($sql_update) === TRUE) {
                    // Successfully updated the stockroom location
                    $response = array("status" => "success", "message" => "Stockroom location updated successfully");
                    echo json_encode($response);
                } else {
                    // Error updating the stockroom location
                    $response = array("status" => "error", "message" => "Error updating stockroom location: " . $con->error);
                    echo json_encode($response);
                }
            } else {
                // The tag ID is new, insert it as a new record
                $sql_insert = "INSERT INTO rfid (tag_ID, logdate, register, stockroom_no) VALUES ('" . $card_data . "','" . $time . "','" . $register_n . "','" . $reader . "')";
                if ($con->query($sql_insert) === TRUE) {
                    // Successfully inserted a new record
                    $response = array("status" => "success", "message" => "Data inserted successfully");
                    echo json_encode($response);
                } else {
                    // Error inserting a new record
                    $response = array("status" => "error", "message" => "Error inserting data: " . $con->error);
                    echo json_encode($response);
                }
            }
        }
    
        $con->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>
