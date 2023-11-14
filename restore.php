<?php 

include('connection.php'); 
include('session.php'); 

// Check if the 'id' parameter is set in the URL
if (isset($_GET['tag_id'])) {
    // Get the product ID from the URL
    $tagID = $_GET['tag_id'];

    // Get the current date in MySQL format (YYYY-MM-DD)
    $time = date("Y-m-d"); 

    // Update the product status to "Checkout"
    $updateQuery = "UPDATE rfid SET availability = 'Available', logdate = '$time' WHERE tag_ID = '$tagID'";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script> alert('Product restored available successfully.');window.location='checkoutpage.php'</script>";
    
    } else {
        echo "Error updating product status: " . mysqli_error($con);
    }

    // mysqli_close($con);
    // // Redirect back to the same page
    // header("Location: checkoutpage.php"); // Replace with the actual URL of the original page
    // exit();

} else {
    echo "Invalid request.";
}

?>
