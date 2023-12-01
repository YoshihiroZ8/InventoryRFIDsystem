<?php
include('connection.php');

// Handle the case where the Show Report button is pressed
if (isset($_POST['Show'])) {
    $selectedDate = $_POST['selectedDate'];

    // Query to get stockroom status
    $stockroomStatusQuery = "SELECT stockroom_no, stockroom_status FROM stockroom";
    $stockroomResult = $con->query($stockroomStatusQuery);

    // Calculate total available products
    $availableProductsQuery = "SELECT COUNT(*) AS total_available_products FROM rfid WHERE availability = 'Available' AND logdate = '$selectedDate'";
    $result_available_products = mysqli_query($con, $availableProductsQuery);
    $totalAvailableProducts = mysqli_fetch_assoc($result_available_products)['total_available_products'];

    // Calculate total checkout from the stockroom
    $checkoutQuery = "SELECT COUNT(*) AS total_checkedout_products FROM rfid WHERE availability = 'Checked Out' AND logdate = '$selectedDate'";
    $result_checkout = mysqli_query($con, $checkoutQuery);
    $totalCheckoutProducts = mysqli_fetch_assoc($result_checkout)['total_checkedout_products'];

    // Calculate total unregister products
    $unregisterQuery = "SELECT COUNT(*) AS total_unregistered_products FROM rfid WHERE register = 'No' AND logdate = '$selectedDate'";
    $result_unregister = mysqli_query($con, $unregisterQuery);
    $totalUnregisterProducts = mysqli_fetch_assoc($result_unregister)['total_unregistered_products'];

    // Initialize variables to store stockroom statuses
    $stockroomAStatus = '';
    $stockroomBStatus = '';
    $stockroomCStatus = '';

    // Display the report (no insertion into daily_report table)
}

// Handle the case where the Generate Report button is pressed
if (isset($_POST['Generate'])) {
    $selectedDate = $_POST['selectedDate'];

    // Query to get stockroom status
    $stockroomStatusQuery = "SELECT stockroom_no, stockroom_status FROM stockroom";
    $stockroomResult = $con->query($stockroomStatusQuery);

    // Calculate total available products
    $availableProductsQuery = "SELECT COUNT(*) AS total_available_products FROM rfid WHERE availability = 'Available'";
    $result_available_products = mysqli_query($con, $availableProductsQuery);
    $totalAvailableProducts = mysqli_fetch_assoc($result_available_products)['total_available_products'];

    // Calculate total checkout from the stockroom
    $checkoutQuery = "SELECT COUNT(*) AS total_checkedout_products FROM rfid WHERE availability = 'Checked Out'";
    $result_checkout = mysqli_query($con, $checkoutQuery);
    $totalCheckoutProducts = mysqli_fetch_assoc($result_checkout)['total_checkedout_products'];

    // Calculate total unregister products
    $unregisterQuery = "SELECT COUNT(*) AS total_unregistered_products FROM rfid WHERE register = 'No'";
    $result_unregister = mysqli_query($con, $unregisterQuery);
    $totalUnregisterProducts = mysqli_fetch_assoc($result_unregister)['total_unregistered_products'];

    // Initialize variables to store stockroom statuses
    $stockroomAStatus = '';
    $stockroomBStatus = '';
    $stockroomCStatus = '';

    // Insert data into the daily_report table
    while ($row = $stockroomResult->fetch_assoc()) {
        $stockroomNo = $row['stockroom_no'];
        $stockroomStatus = $row['stockroom_status'];

        // Store stockroom status in respective variables
        if ($stockroomNo === 'Stockroom A') {
            $stockroomAStatus = $stockroomStatus;
        } elseif ($stockroomNo === 'Stockroom B') {
            $stockroomBStatus = $stockroomStatus;
        } elseif ($stockroomNo === 'Stockroom C') {
            $stockroomCStatus = $stockroomStatus;
        }
    }

    // Insert data into the daily_report table
    $insertQuery = "INSERT INTO daily_report (report_date, status_A, status_B, status_C, total_products, total_checkout, unregister_p)
        VALUES ('$selectedDate', '$stockroomAStatus', '$stockroomBStatus', '$stockroomCStatus', '$totalAvailableProducts', '$totalCheckoutProducts', '$totalUnregisterProducts')";

    $con->query($insertQuery);
}

// Fetch data from the report table for the selected date
$reportDate = date('Y-m-d'); 

if (isset($_POST['selectedDate'])) {
    $reportDate = $_POST['selectedDate'];
}

// Fetch data from the report table
$reportQuery = "SELECT * FROM daily_report WHERE report_date = '$reportDate'";
$reportResult = $con->query($reportQuery);

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .report-container {
            margin-top: 20px;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
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
                <h1>Daily Inventory Report</h1>
            </header>
            <section class="report-container">
                <h2>Analytics Report</h2>
                <form method="post" action="">
                    <label for="selectedDate">Select Date:</label>
                    <input type="date" id="selectedDate" name="selectedDate" value="<?= $reportDate ?>" required>
                    <button type="submit" name="Show">Show Report</button>
                    <button type="submit" name="Generate">Generate Report</button>
                </form>
                <p>Showing data for <?= $reportDate ?></p>
                <table class="table1" cellspacing="5" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Report Date</th>
                            <th>Stockroom A</th>
                            <th>Stockroom B</th>
                            <th>Stockroom C</th>
                            <th>Total Available Products</th>
                            <th>Total Checkout Products</th>
                            <th>Total Unregister Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $reportResult->fetch_assoc()) : ?>
                            <tr>
                                <td><?= $row['report_date'] ?></td>
                                <td><?= $row['status_A'] ?></td>
                                <td><?= $row['status_B'] ?></td>
                                <td><?= $row['status_C'] ?></td>
                                <td><?= $row['total_products'] ?></td>
                                <td><?= $row['total_checkout'] ?></td>
                                <td><?= $row['unregister_p'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</body>
</html>