<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// Try and connect using the info above.
$con= mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$name=$_POST['username'];
$password=$_POST['password'];
$email=$_POST['email'];
$query = "INSERT INTO accounts VALUES('$name','$password','$email')";
if(isset($_POST['SignUp']))
{
    if(mysqli_query($con,$query)) header('Location: index.html');
    else echo "error";
}
?>
<!-- reference operator
class 
object
exception handling
file handling -->
