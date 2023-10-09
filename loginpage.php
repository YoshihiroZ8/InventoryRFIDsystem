<!DOCTYPE html>
<html lang="en">
<head>

    <title>RFID Inventory Management System</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/samples.css">

</head>
<body>
 
    <form action="login.php" method="POST">
        <h3>Login Here</h3>

        <label for="username">Username</label>
        <input type="text" placeholder="Username" name="username" id="username" required="">

        <label for="password">Password</label>
        <input type="password" placeholder="Password" name="password" id="password" required="">

        <button type="submit" name="sublogin">Log In</button>
		<hr>
		<p>Don't have an account? <a href="register.php">Register</a> </p>
    </form>
</body>
</html>
