<?php include('session.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Dashboard</title>
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
                <h1>Checked out Products List</h1>
            </header>
            <section>
            <table class="table">
              <thead>
                  <tr>
                    <td>Tag ID</td>
                    <td>Log Date</td>
                    <td>Product Type</td>
                    <td>Product Company</td>
                    <td>Availability</td>
                    <td>Stockroom No</td>
                    <td>Checkout Date time</td>
                    <td>Action</td>
                  </tr>
              </thead>

              <thead>
                <tbody>
                <?php   
                  $query = "SELECT * FROM rfid WHERE availability = 'Checked Out'";
                  $result = mysqli_query($con, $query);

                  //read data of each row
                  if(mysqli_num_rows($result) > 0)
                  { 
                    foreach($result as $row)
                    {
                      ?>
                              
                      <tr>
                      <td><?= $row['tag_ID']; ?></td>
                      <td><?= $row['logdate']; ?></td>
                      <td><?= $row['product_type']; ?></td>
                      <td><?= $row['product_from']; ?></td>
                      <td><?= $row['availability']; ?></td>
                      <td><?= $row['stockroom_no']; ?></td>
                      <td><?= $row['checkout_d']; ?></td>
                      <td>
                      <a name="restore" href="restore.php?tag_id=<?= $row['tag_ID']; ?>">Restore</a>
                      </td>
                      </tr>

                    <?php 
                     }
                    }else
                    {
                      echo"<h5>No Checked out Product Found...</h5>";
                    }
                    ?>

                </tbody>
              </thead>
            </table>
            </section>
        </div>
    </div>
</body>
</html>