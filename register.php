<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Design by foolishdeveloper.com -->
    <title>Register Page</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/samples.css">

</head>
<body>

    <?php 
    include('connection.php');    

	if(isset($_POST['signup'])){
	extract($_POST);
    if(strlen($username)<3){ // Change Minimum Lenghth   
            $error[] = 'Please enter Username using at least 3 charaters.';
        }
        if(strlen($username)>30){ // Change Max Length 
            $error[] = 'Username : Max length 50 Characters Not allowed';
        }
        if(!preg_match("/^^[^0-9][a-z0-9]+([_-]?[a-z0-9])*$/", $username)){
            $error[] = 'Invalid Entry for Username. Enter lowercase letters without any space and No number at the start- Eg - myusername, okuniqueuser or myusername123';
        }  
	    if(strlen($user_name)<1){ // Minimum 
		$error[] = 'Please enter Name using atleast 1 charaters.';
        }
		if(strlen($user_name)>50){  // Max 
			$error[] = 'Name: Max length 20 Characters Not allowed';
        }
		if(!preg_match("/^[A-Za-z _]*[A-Za-z ]+[A-Za-z _]*$/", $user_name)){
            $error[] = 'Invalid Entry Name. Please Enter letters without any Digit or special symbols like ( 1,2,3#,$,%,&,*,!,~,`,^,-,)';
        }    
		if(strlen($email)>70){  // Max 
            $error[] = 'Email: Max length 50 Characters Not allowed';
        }
		if($passwordConfirm ==''){
            $error[] = 'Please confirm the password.';
        }
        if($password != $passwordConfirm){
            $error[] = 'Passwords do not match.';
        }
          if(strlen($password)<5){ // min 
            $error[] = 'The password is 6 characters long.';
        }
         if(strlen($password)>20){ // Max 
            $error[] = 'Password: Max length 20 Characters Not allowed';
        }
		if (strlen($mobile) != 10) {
               $error[] =  "Mobile Number. must contain 10 digits.";
           }
        
        /// prevent username and email duplicate from registered ID
        $sql= "select * from users where (username='$username' or email='$email');";
		$res= mysqli_query($con,$sql);
		if (mysqli_num_rows($res) > 0) {
		$row = mysqli_fetch_assoc($res);

		if($username==$row['username']){
           $error[] ='Username alredy Exists.';
			} 
		
		if($email==$row['email']){
            $error[] ='Email alredy Exists.';
			} 
		}
		
         if(!isset($error)){ 
            $date=date('Y-m-d');
            $result = mysqli_query($con,"INSERT INTO users(username,user_name,password,email,mobile) VALUES ('$username','$user_name','$password','$email','$mobile')");
			if($result){
				$done=2; 
			}
			else{
				$error[] ='Failed : Something went wrong';
				}
			}
		} ?>

		<div class="col-sm-4">
     
        <?php 
        if(isset($error)){ 
        foreach($error as $error){ 
            echo '<p class="errmsg">&#x26A0;'.$error.' </p>'; 
            }
        }
        ?>
		</div>

	<div class="col-sm-4">
    <?php if(isset($done)) 
      { 
	?>
	  
    <div class="successmsg"><span style="font-size:100px;">&#9989;</span> <br> You have registered successfully . <br> <a href="loginpage.php">Login here... </a> </div>
      <?php } else { ?>
    
    <form action="register.php" method="POST">
    <h3>Register Here</h3>
    <div>
        <label for="username">Username</label>
        <input type="text" placeholder="Username" name="username" value="<?php if(isset($error)){ echo $_POST['username'];}?>" required="">
    </div>
    
    <div>
        <label for="user_name">Name</label>
        <input type="text" placeholder="Your Name" name="user_name" value="<?php if(isset($error)){ echo $_POST['user_name'];}?>" required="">
    </div>
    
    <div>
        <label for="password">Password </label>
        <input type="password" name="password" placeholder="enter password" required="">
	</div>
    
    <div>
        <label for="password">Confirm Password </label>
        <input type="password" name="passwordConfirm" placeholder="confirm password" required="">
	</div>

	<div>
        <label for="email">Email </label>
        <input type="email" name="email" placeholder="enter email" value="<?php if(isset($error)){ echo $_POST['email'];}?>" required="">
	</div>
  
	<div>
        <label for="mobile">Mobile No. </label>
        <input type="text" name="mobile" placeholder="enter mobile" value="<?php if(isset($error)){ echo $_POST['mobile'];}?>" required="">
	</div>
  
	<button type="submit" name="signup" class="btn btn-primary btn-group-lg form_btn">SignUp</button>
    <p>Have an account?  <a href="loginpage.php">Log in</a> </p>
    </form>
    
    <?php } ?> 
	</div>
</body>
</html>
