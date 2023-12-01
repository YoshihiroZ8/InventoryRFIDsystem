<?php

include('connection.php');   

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. 
// If you change this value, the ESP32 sketch needs to match
$api_key_value = "tPmAT5Ab3j7F9";

// Create a timestamp
date_default_timezone_set("Asia/Kuala_Lumpur");
$time = date("Y-m-d H:i:s");

$register_n = "No"; 
$movement_out = "Out";
$movement_in = "In";


if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        
        // Get data from the POST request
        $card_data = $_POST['card_data'];
        $reader = $_POST['reader'];
        $action = $_POST['action']; // Add this line to get the action parameter


        if ($action == "checkout") {
            
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

        } else if ($_POST['action'] == 'in') {
            
            // Handle "in" action
            // Check if the tag is already in the database, if not, insert it
            $checkSql = "SELECT * FROM rfid WHERE tag_ID = '" . $card_data . "'";
            $result = $con->query($checkSql);
    
            if ($result->num_rows > 0) {
                
                // Tag exists, update the stockroom
                $updateSql = "UPDATE rfid SET stockroom_no = '" . $reader . "', movem_status = '" . $movement_in . "', logdate = '" . $time . "' WHERE tag_ID = '" . $card_data . "'";
                if ($con->query($updateSql) === TRUE) {
                    $response = array("status" => "success", "message" => "Tag has been scanned In");
                    echo json_encode($response);
                    echo 'Tag status updated to "in" for Stockroom ' . $reader;

                    // Update the stockroom's total_products when products in total_product + 1
                    $updateStockroomSql = "UPDATE stockroom SET total_products = total_products + 1 WHERE stockroom_no = '" . $reader . "'";
                    if ($con->query($updateStockroomSql) === TRUE) {
                        // Update successful
                    } else {
                        echo 'Error updating stockroom total_products: ' . $con->error;
                    }

                } else {
                    echo 'Error updating tag status: ' . $con->error;
                }
            } else {

                // Tag doesn't exist, insert it
                $insertSql = "INSERT INTO rfid (tag_ID, logdate, register, stockroom_no, movem_status) VALUES ('" . $card_data . "','" . $time . "','" . $register_n . "','" . $reader . "','" . $movement_in . "')";
                if ($con->query($insertSql) === TRUE) {
                    echo 'New Tag inserted status "in" for ' . $reader;

                      // Update the stockroom's total_products when products in total_product + 1
                    $updateStockroomSql = "UPDATE stockroom SET total_products = total_products + 1 WHERE stockroom_no = '" . $reader . "'";
                    if ($con->query($updateStockroomSql) === TRUE) {
                        // Update successful
                    } else {
                        echo 'Error updating stockroom total_products: ' . $con->error;
                    }


                } else {
                    echo 'Error inserting tag: ' . $con->error;
                }
            }
        } else if ($_POST['action'] == 'out') {
            
            // Handle "out" action
            // Update the tag's status to "out" in the database
            $updateSql = "UPDATE rfid SET movem_status =  '" . $movement_out . "', logdate = '" . $time . "' WHERE tag_ID = '" . $card_data . "'";
        
            if ($con->query($updateSql) === TRUE) {
                echo 'Tag has been "out" from '. $reader;

                // Update the stockroom's total_products when products out total_product -1
                $updateStockroomSql = "UPDATE stockroom SET total_products = total_products - 1, out_product = out_product + 1 WHERE stockroom_no = '" . $reader . "'";
                if ($con->query($updateStockroomSql) === TRUE) {
                    // Update successful
                } else {
                    echo 'Error updating stockroom total_products: ' . $con->error;
                }


            } else {
                echo 'Error updating tag status: ' . $con->error;
            }
        }
            // ------------------------------------ latest 10:50pm 26Oct2023 added
            // Check if the stockroom is understock or fullstock
            $checkStockroomSql = "SELECT total_products FROM stockroom WHERE stockroom_no = '" . $reader . "'";
            $resultStockroom = $con->query($checkStockroomSql);
            if ($resultStockroom->num_rows > 0) {
                $row = $resultStockroom->fetch_assoc();
                $totalProducts = $row["total_products"];
                    
                if ($totalProducts < 3) {
                    // Update the stockroom status to "understock"
                    $updateStatusSql = "UPDATE stockroom SET stockroom_status = 'understock' WHERE stockroom_no = '" . $reader . "'";
                    if ($con->query($updateStatusSql) === TRUE) {
                        // Status updated to "understock"
                    } else {
                        echo 'Error updating stockroom status: ' . $con->error;
                    }
                } else if ($totalProducts >= 8) {
                    // Update the stockroom status to "fullstock"
                    $updateStatusSql = "UPDATE stockroom SET stockroom_status = 'fullstock' WHERE stockroom_no = '" . $reader . "'";
                    if ($con->query($updateStatusSql) === TRUE) {
                        // Status updated to "fullstock"
                    } else {
                        echo 'Error updating stockroom status: ' . $con->error;
                    }
                } else {
                    // Update the stockroom status to "available"
                    $updateStatusSql = "UPDATE stockroom SET stockroom_status = 'available' WHERE stockroom_no = '" . $reader . "'";
                    if ($con->query($updateStatusSql) === TRUE) {
                        // Status updated to "available"
                    } else {
                        echo 'Error updating stockroom status: ' . $con->error;
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
