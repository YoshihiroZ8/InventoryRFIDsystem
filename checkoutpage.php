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
                <h1>Available Products List</h1>
            </header>
            <section>
            <table class="table">
              <thead>
                  <tr>
                    <td>Product ID</td>
                    <td>Product Type</td>
                    <td>Date In</td>
                    <td>Date Out</td>
                    <td>Product Status</td>
                    <td>Action</td>
                  </tr>
              </thead>

              <thead>
                <tbody>
                <?php   
                  $query = "SELECT * FROM product WHERE product_status = 'Available'";
                  $result = mysqli_query($con, $query);

                  //read data of each row
                  if(mysqli_num_rows($result) > 0)
                  { 
                    foreach($result as $product)
                    {
                      ?>
                              
                      <tr>
                      <td><?= $product['product_id']; ?></td>
                      <td><?= $product['stockroom_no']; ?></td>
                      <td><?= $product['product_in']; ?></td>
                      <td><?= $product['product_out']; ?></td>
                      <td><?= $product['product_status']; ?></td>
                      <td><a name="checkout_product" href="checkout.php?id=<?= $product['product_id']; ?>">Checkout</a>
                        </td>
                      </tr>

                    <?php 
                     }
                    }else
                    {
                      echo"<h5>No Record Found...</h5>";
                    }
                    ?>

                </tbody>
              </thead>
            </table>
            </section>

            <h2>Checked out Products List</h2>
            <section>
            <table class="table">
              <thead>
                  <tr>
                    <td>Product ID</td>
                    <td>Product Type</td>
                    <td>Date In</td>
                    <td>Date Out</td>
                    <td>Product Status</td>
                  </tr>
              </thead>

              <thead>
                <tbody>
                <?php   
                  $query = "SELECT * FROM product WHERE product_status != 'Available'";
                  $result = mysqli_query($con, $query);

                  //read data of each row
                  if(mysqli_num_rows($result) > 0)
                  { 
                    foreach($result as $product)
                    {
                      ?>
                              
                      <tr>
                      <td><?= $product['product_id']; ?></td>
                      <td><?= $product['stockroom_no']; ?></td>
                      <td><?= $product['product_in']; ?></td>
                      <td><?= $product['product_out']; ?></td>
                      <td><?= $product['product_status']; ?></td>

                      </tr>

                    <?php 
                     }
                    }else
                    {
                      echo"<h5>No Record Found...</h5>";
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