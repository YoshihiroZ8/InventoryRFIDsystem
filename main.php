<?php
include('connection.php'); 
include('session.php');

// Query the database to get the total available products count
$query = "SELECT COUNT(*) AS total_available_products FROM rfid WHERE availability = 'Available'";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalAvailableProducts = $row['total_available_products'];
} else {
    $totalAvailableProducts = 0; // Default value if there's an issue with the query
}


// Query the database to get the total available products count
$query_checkout = "SELECT COUNT(*) AS total_checkedout_products FROM rfid WHERE availability = 'Checked Out'";
$result_checkout = mysqli_query($con, $query_checkout);

if ($result) {
    $row = mysqli_fetch_assoc($result_checkout);
    $totalCheckoutProducts = $row['total_checkedout_products'];
} else {
    $totalCheckoutProducts = "No Products Checked out"; // Default value if there's an issue with the query
}

?>

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
                <h1>Welcome Inventory Info Dashboard</h1>
            </header>
            <section class="cards">
                <div class="card">
                    <h2>Available Products</h2>
                    <p>Total Products: <?php echo $totalAvailableProducts; ?></p>
                </div>
                <div class="card">
                    <h2>Checkout Products</h2>
                    <p>Checkedout: <?php echo $totalCheckoutProducts; ?></p>
                </div>
                <div class="card">
                    <h2>Date Today</h2>
                    <p id="current-time">Date: </p>
                </div>
            </section>
            <section>
            
            <h2>Stockroom A</h2>
                <!---Stockroom A data Display--->
            <table class="table" cellspacing="5" cellpadding="5">
              <thead>
                  <tr>
                      <th>Tag/Card ID</th>
                      <th>Log Date</th>
                      <th>Product Type</th>
                      <th>Product Company</th>
                      <th>Stockroom No.</th>
                      <th>Movement</th>
                      <th>Checkout Date</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <thead>
              <tbody id="stockroomA-data"></tbody>
              </thead>
            </table>

            <h2>Stockroom B</h2>
            <!---Stockroom B data Display--->
            <table class="table" cellspacing="5" cellpadding="5">
              <thead>
                  <tr>
                      <th>Tag/Card ID</th>
                      <th>Log Date</th>
                      <th>Product Type</th>
                      <th>Product Company</th>
                      <th>Stockroom No.</th>
                      <th>Movement</th>
                      <th>Checkout Date</th>
                      <th>Action</th>
                  </tr>
              </thead>

              <thead>
              <tbody id="stockroomB-data"></tbody>
              </thead>
            </table>

            <h2>Stockroom C</h2>
            <table class="table" cellspacing="5" cellpadding="5">
                <!---Stockroom C data Display--->
              <thead>
                  <tr>
                      <th>Tag/Card ID</th>
                      <th>Log Date</th>
                      <th>Product Type</th>
                      <th>Product Company</th>
                      <th>Stockroom No.</th>
                      <th>Movement</th>
                      <th>Checkout Date</th>
                      <th>Action</th>
                  </tr>
              </thead>

              <thead>
              <tbody id="stockroomC-data"></tbody>
              </thead>
            </table>
            </section>
        </div>
    </div>
</body>
</html>

<script>
    //function for fetch stockroom A data and display
    function fetchStockroomA() {
        // Send an AJAX request to your PHP script to fetch data
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
                            <td>${data.product_type}</td>
                            <td>${data.product_from}</td>
                            <td>${data.stockroom_no}</td>
                            <td>${data.movem_status}</td>
                            <td>${data.checkout_d}</td>
                            <td>
                                <a href='*' name='details'>Details</a>
                            </td>
                        </tr>`;
                }

                // Update the table body with the generated rows
                var tableBody = document.getElementById('stockroomA-data');
                tableBody.innerHTML = tableRows;
            }
        };
        xhr.open('GET', 'stockroom-a-fetcher.php', true);
        xhr.send();
    }


    //function for fetch stockroom B data and display
    function fetchStockroomB() {
        // Send an AJAX request to your PHP script to fetch data
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
                            <td>${data.product_type}</td>
                            <td>${data.product_from}</td>
                            <td>${data.stockroom_no}</td>
                            <td>${data.movem_status}</td>
                            <td>${data.checkout_d}</td>
                            <td>
                                <a href='*' name='details'>Details</a>
                            </td>
                        </tr>`;
                }

                // Update the table body with the generated rows
                var tableBody = document.getElementById('stockroomB-data');
                tableBody.innerHTML = tableRows;
            }
        };
        xhr.open('GET', 'stockroom-b-fetcher.php', true);
        xhr.send();
    }


    //function for fetch stockroom C data and display
    function fetchStockroomC() {
        // Send an AJAX request to your PHP script to fetch data
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
                            <td>${data.product_type}</td>
                            <td>${data.product_from}</td>
                            <td>${data.stockroom_no}</td>
                            <td>${data.movem_status}</td>
                            <td>${data.checkout_d}</td>
                            <td>
                                <a href='*' name='details'>Details</a>
                            </td>
                        </tr>`;
                }

                // Update the table body with the generated rows
                var tableBody = document.getElementById('stockroomC-data');
                tableBody.innerHTML = tableRows;
            }
        };
        xhr.open('GET', 'stockroom-c-fetcher.php', true);
        xhr.send();
    }


    //function for display real time date time on main page
    function updateCurrentTime() {
        const timeElement = document.getElementById('current-time');
        const currentTime = new Date().toLocaleString();
        timeElement.textContent = 'Date: ' + currentTime;
        }   

    // Update the time immediately and then every second
    updateCurrentTime();
    // Initially fetch data
    fetchStockroomA();
    fetchStockroomB();
    fetchStockroomC();

    // Periodically fetch data every 1 seconds (adjust as needed)
    setInterval(fetchStockroomA, 1000); 
    setInterval(fetchStockroomB, 1000); 
    setInterval(fetchStockroomC, 1000); 
    setInterval(updateCurrentTime, 1000); 
</script>



