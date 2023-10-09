<?php include('connection.php'); ?>
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
          <div>
                <form action="analysispage.php" method="post">
                <input type="text" name="search" class="search-input" placeholder="Enter products/RFID ID to search..."/>
                <input type="submit" name="submit" value="Submit" />
                </form>
          </div>
          <hr>
        <section>
              <table class="table">
                          <thead>
                              <tr>
                              <th>Stockroom No</th>
                               <th>Total Products</th>
                               <th>Checkedout Products</th>
                               <th>Stockroom Status</th>
                               <th>Action</th>
                            </tr>
                        </thead>
                    <thead>
                <tbody>
                  <!-- php search function -->
                  <?php 
                  if (isset($_POST['submit'])) {
                    $search = $_POST['search']; 
                  }
                  else{
                    $search = "";
                  }

                  $query = $con->query("SELECT * FROM stockroom WHERE stockroom_no LIKE '%$search%' or product_num  LIKE '%$search%'");
                  if ($query->num_rows > 0){
                  while($row = $query->fetch_assoc() ){
                    echo "<tr>
                      <td>" . $row["stockroom_no"] . "</td>
                      <td>" . $row["product_num"] . "</td>
                      <td>" . $row["checkout_num"] . "</td>
                      <td>" . $row["stockroom_status"] . "</td>
                      <td>
                          <a href='Details'>Edit</a>
                        </td>
                      </tr>";
                  }
                    } else {
                      echo "0 records";
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


<!-- php search function -->
