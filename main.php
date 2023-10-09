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
                <h1>Welcome Inventory Info Dashboard</h1>
            </header>
            <section class="cards">
                <div class="card">
                    <h2>Available Products</h2>
                    <p>Total Products: </p>
                </div>
                <div class="card">
                    <h2>Checkout Products</h2>
                    <p>Checkedout: </p>
                </div>
                <div class="card">
                    <h2>Date Today</h2>
                    <p>Date: </p>
                </div>
            </section>
            <section>
            <table class="table" cellspacing="5" cellpadding="5">
              <thead>
                  <tr>
                      <th>Tag/Card ID</th>
                      <th>Log Date</th>
                      <th>Product Type</th>
                      <th>Stockroom No.</th>
                      <th>Checkout Date</th>
                      <th>Action</th>
                  </tr>
              </thead>

              <thead>
              <tbody id="rfid-data"></tbody>
              </thead>
            </table>
            </section>
        </div>
    </div>
</body>
</html>

<script>
    function fetchRFIDData() {
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
                            <td>${data.stockroom_no}</td>
                            <td>${data.checkout_d}</td>
                            <td>
                                <a href='*' name='details'>Details</a>
                            </td>
                        </tr>`;
                }

                // Update the table body with the generated rows
                var tableBody = document.getElementById('rfid-data');
                tableBody.innerHTML = tableRows;
            }
        };
        xhr.open('GET', 'main-datafetch.php', true);
        xhr.send();
    }

    // Initially fetch data
    fetchRFIDData();

    // Periodically fetch data every 2 seconds (adjust as needed)
    setInterval(fetchRFIDData, 2000); // Update every 2 seconds
</script>

