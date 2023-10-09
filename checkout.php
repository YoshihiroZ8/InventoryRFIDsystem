<?php 

include('connection.php'); 
include('session.php'); 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Get the product ID from the URL
    $productId = $_GET['id'];

    // Get the current date in MySQL format (YYYY-MM-DD)
    $checkoutDate = date("Y-m-d"); 

    // Update the product status to "Checkout"
    $updateQuery = "UPDATE product SET product_status = 'Checkout', product_out = '$checkoutDate' WHERE product_id = $productId";

    if (mysqli_query($con, $updateQuery)) {
        echo "<script> alert('Product Checkout successfully.');window.location='checkoutpage.php'</script>";
    
    } else {
        echo "Error updating product status: " . mysqli_error($con);
    }

    mysqli_close($con);
    // Redirect back to the same page
    header("Location: checkoutpage.php"); // Replace with the actual URL of the original page
    exit();

} else {
    echo "Invalid request.";
}

?>
