<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Latest RFID Data</title>
</head>
<body>
    <h1>Latest RFID Data</h1>
    <label for="tag_data">Tag Data:</label>
    <input type="text" id="card_data" readonly>

	<label for="product_type">Product Type:</label>
    <input type="text" id="product_type">

    <label for="product_type">Product Company:</label>
    <input type="text" id="product_type">

    <label for="stockroom_no">Stockroom No:</label>
    <input type="text" id="stockroom_no" readonly>

	<input type="submit" name="save" value="Save">

    <script>
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
                        document.getElementById('stockroom_no').value = latestData.stockroom_no;
                    }
                }
            };
            xhr.open('GET', 'main-datafetch.php', true);
            xhr.send();
        }

        // Initially fetch the latest data
        fetchLatestRFIDData();

        // Periodically fetch the latest data every 5 seconds (adjust as needed)
        setInterval(fetchLatestRFIDData, 5000); // Update every 5 seconds
    </script>
</body>
</html>