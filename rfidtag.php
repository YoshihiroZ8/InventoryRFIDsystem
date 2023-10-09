<?php include('session.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h1>Inventory Management System</h1>
            <ul>
                <li><a href="main.php">Inventory</a></li>
                <li><a href="analysispage.php">Analytics</a></li>
                <li><a href="rfidtag.php">RFID Tag Register/Edit</a></li>
                <li><a href="checkoutpage.php">Check Out</a></li>
                <li><a href="logout.php">Exit</a></li>
            </ul>
        </div>
        <div class="main-content">
            <header>
                <h1>RFID Tag registration/ modification</h1>
            </header>

            <section>
            <div class="edit_form">
                <form method="POST" action="rfidtag.php">
                <div id="notification" class="notification"></div>
                    <div class="row">
                        <div class="col-25">
                        <label for="tag_id">RFID Tag ID</label>
                        </div>
                        <div class="col-75">
                        <input type="text" id="card_data" name="card_data" class="input1" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                        <label for="product_type">Product Type</label>
                        </div>
                        <div class="col-75">
                        <select id="product_type" name="product_type" class="input1">
                            <option value="">Select Product Type...</option>
                            <option value="Monitor">Monitor </option>
                            <option value="PC Case">PC Case</option>
                            <option value="Processor">Processor</option>
                            <option value="Graphic Card">Graphic Card</option>
                            <!-- Add more options as needed -->
                        </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                        <label for="product_from">Product's Production Company</label>
                        </div>
                        <div class="col-75">
                        <select id="product_from" name="product_from" class="input1">
                            <option value="">Select Company from...</option>
                            <option value="Amazon">AMAZON </option>
                            <option value="Microsoft Corp">Microsoft Corp</option>
                            <option value="Dell Technologies Inc">Dell Technologies Inc</option>
                            <option value="INTEL Technologies Inc">INTEL Technologies Inc</option>
                            <!-- Add more options as needed -->
                        </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-25">
                        <label for="stockroom_no">Stockroom No.</label>
                        </div>
                        <div class="col-75">
                        <input type="text" id="stockroom_no" name="stockroom_no" class="input1">
                        </div>
                    </div>
                <br>
                <div class="row">
                    <input type="submit"  name="save" value="Save">
                </div>
                </form>
            </div>
            </section>

            <secion>
            <h2>Unregister Tag ID List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Card Data</th>
                        <th>Time</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody id="rfid-data"></tbody>
            </table>
                <script>

                    function fetchRFIDData() {
                        // Send an AJAX request to your data-fetcher.php script to fetch data
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                // Parse the JSON response
                                var rfidData = JSON.parse(xhr.responseText);
                                console.log(rfidData); // Log the parsed data
                                
                                // Initialize an empty table row string
                                var tableRows = '';

                                // Loop through the RFID data array and create rows for each entry
                                for (var i = 0; i < rfidData.length; i++) {
                                    var data = rfidData[i];
                                    tableRows += `
                                        <tr>
                                            <td>${data.card_data}</td>
                                            <td>${data.logdate}</td>
                                            <td>${data.stockroom_no}</td>
                                            <td>
                                            <a href='*' name='register'>Register</a>
                                            </td>
                                        </tr>`;

                                }
                                
                                // Update the table body with the generated rows
                                var tableBody = document.getElementById('rfid-data');
                                tableBody.innerHTML = tableRows;
                            }
                        };          
                        xhr.open('GET', 'rfiddata-fetcher.php', true);
                        xhr.send();
                    }

                    
                    function fetchLatestRFIDData() {
                    var xhr = new XMLHttpRequest();
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var rfidData = JSON.parse(xhr.responseText);

                            // Check if there is data to display
                            if (rfidData.length > 0) {
                                // Get the latest RFID data
                                var latestData = rfidData[0];
                                
                                // Display the latest tag data in the input box
                                document.getElementById('card_data').value = latestData.card_data;
                                document.getElementById('product_type').value = latestData.product_type;
                                document.getElementById('product_from').value = latestData.product_type;
                                document.getElementById('stockroom_no').value = latestData.stockroom_no;

                                 // Display a custom notification
                                displayNotification('New RFID tag scanned!');
                            }
                        }
                    };
                    xhr.open('GET', 'rfiddata-fetcher.php', true);
                    xhr.send();
                }

                function displayNotification(message) {
                var notification = document.getElementById('notification');
                notification.innerHTML = message;
                notification.style.display = 'block';

                // Automatically hide the notification after a few seconds (adjust as needed)
                setTimeout(function () {
                    notification.style.display = 'none';
                }, 3000); // 3 seconds  
}

                    // Initially fetch data
                    fetchRFIDData();
                    fetchLatestRFIDData();

                    // Periodically fetch data every 2 seconds (adjust as needed)
                    setInterval(fetchRFIDData, 3000); 
                    setInterval(fetchLatestRFIDData, 5000); 
                </script>
            </section>
        </div>
    </div>
</body>
</html>

<?php
/// Update Data function in PHP
include('connection.php'); // Include your database connection file

if (isset($_POST['save'])) {
    // Get the form data
    $card_data = $_POST['card_data'];
    $product_from = $_POST['product_from'];
    $register_y = "Yes";

    // Construct the SQL update query
    $sql = "UPDATE rfid SET logdate = '$date', register = '$register_y', product_type = '$product_from' WHERE tag_ID = '$card_data'";

    // Perform the update
    if ($con->query($sql) === TRUE) {
        echo "<script> alert('Update Data successful');window.location='main.php'</script>";
    } else {
        echo "Error updating data: " . $con->error;
    }

    // Close the database connection
    $con->close();
}
?>