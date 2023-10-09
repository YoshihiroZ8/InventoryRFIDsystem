<?php    
include('connection.php');    
session_start();

if(isset($_POST['sublogin']))
{
    $username = $_POST['username'];  
    $password = $_POST['password'];  
      
        //to prevent from mysqli injection  
        $username = stripcslashes($username);  
        $password = stripcslashes($password);  
        $username = mysqli_real_escape_string($con, $username);  
        $password = mysqli_real_escape_string($con, $password);  
      
        $sql = "select * from users where username = '$username' and password = '$password'";  
        $result = mysqli_query($con, $sql);    

        if($result->num_rows > 0){

            while ($row = $result->fetch_assoc()) {
                $userid = $row['userID'];
                $_SESSION['id'] = $userid;  
                echo "<script> alert('Login successful');window.location='main.php'</script>";
            }
        }
        else{
            echo "<script> alert('Login Failed, Username & Password incorrect');window.location='loginpage.php'</script>";
        }

}  
?>  	