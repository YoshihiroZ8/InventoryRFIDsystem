<?php include('connection.php'); ?>
<?php include('session.php'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    /* Hide the entire table by default */
    #searchResultsTable {
        display: none;
    }
</style>
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
              <table id="searchResultsTable" class="table">
                <thead>
                  
                          <!-- php search function -->
                  <?php 

                  // Initialize a flag to check if search results are found
                  $searchResultsFound = false;

                  if (isset($_POST['submit'])) {
                    $search = $_POST['search']; 
                  
                  //query for search data from stockroom table
                  $query = "SELECT * FROM stockroom WHERE stockroom_no LIKE '%$search%' or total_products  LIKE '%$search%'";
                  $result = $con->query($query);
                  if ($result->num_rows > 0) {
                    echo "
                    <tr>
                        <th>Stockroom No</th>
                        <th>Total Products</th>
                        <th>Checkedout Products</th>
                        <th>Stockroom Status</th>
                    </tr>
                    </thead>
                    <tbody>";

                    while ($row = $result->fetch_assoc()) {
                      $searchResultsFound = true; // Set the flag to true when results are found  
                      echo "
                        <tr>
                            <td>" . $row["stockroom_no"] . "</td>
                            <td>" . $row["total_products"] . "</td>
                            <td>" . $row["out_product"] . "</td>
                            <td>" . $row["stockroom_status"] . "</td>
                        </tr>";
                    }
                    echo "</tbody>";
                    } else {
                        echo "No Stockroom Record Found";
                    }
                      
                   //query for search data from stockroom table
                   $query2 = "SELECT * FROM rfid WHERE tag_ID LIKE '%$search%' or product_type  LIKE '%$search%' or product_from  LIKE '%$search%'";
                   $result2 = $con->query($query2);
                   if ($result2->num_rows > 0) {
                    echo "
                    <tr>
                        <th>RFID Tag ID</th>
                        <th>Log Date</th>
                        <th>Register</th>
                        <th>Product Type</th>
                        <th>Product Company</th>
                        <th>Availability</th>
                        <th>Stockroom No</th>
                        <th>Checkout Date</th>
                    </tr>
                    </thead>
                    <tbody>";

                    while ($row = $result2->fetch_assoc()) {
                      $searchResultsFound = true; // Set the flag to true when results are found
                      echo "
                        <tr>
                            <td>" . $row["tag_ID"] . "</td>
                            <td>" . $row["logdate"] . "</td>
                            <td>" . $row["register"] . "</td>
                            <td>" . $row["product_type"] . "</td>
                            <td>" . $row["product_from"] . "</td>
                            <td>" . $row["availability"] . "</td>
                            <td>" . $row["stockroom_no"] . "</td>
                            <td>" . $row["checkout_d"] . "</td>
                        </tr>";
                    }
                        echo "</tbody>";
                    } else {
                        echo "No RFID Data Found";
                    }
                  
                  }
                  ?>


              </thead>
            </table>
           </section> 
          </div>
    </div>
    
</body>
</html>


<script>
    window.onload = function () {
        // Check if search results were found
        var searchResultsFound = <?php echo json_encode($searchResultsFound); ?>;

        // Get a reference to the entire table
        var searchResultsTable = document.getElementById("searchResultsTable");

        // Show the entire table if search results were found, hide it otherwise
        if (searchResultsFound == true) {
            searchResultsTable.style.display = "table"; // Show the table
        } else {
            searchResultsTable.style.display = "none"; // Hide the table
        }
    };
</script>