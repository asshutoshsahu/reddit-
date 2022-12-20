<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>OmniShea</h1>
				<a href="home.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>


<div class="split-pane">
        <h1>Create a new post</h1>
        <form class="register-form" method="POST">
            <input id="post-title" type="text" placeholder="title" name="title"> <br> <br>
            <textarea id="post-content" type="text" placeholder="text" name="content"></textarea> <br> <br>
            <!--<input id="post-subreddit" type="text" placeholder="subreddit" name="subreddit">-->
            <button type="submit" class="btnblue" id="sign-up-in-btn" value="Sign up" name="submit-post">Submit Post</button>
        </form>
		<div id="failure"></div>
		<?php
    	// this will trigger when submit button click
    	if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['submit-post'])){
            $link = mysqli_connect("localhost", "root", "", "phplogin");
            // Check connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
            // Escape user inputs for security
            $username = $_SESSION['uname'];
            $title = mysqli_real_escape_string($link, $_REQUEST['title']);
            $content = mysqli_real_escape_string($link, $_REQUEST['content']);
            // $subreddit = mysqli_real_escape_string($link, $_REQUEST['subreddit']);
			// $subreddit = 'all';
            // $posttype = '1';
			if ($title === '' || $content === '') {
				echo '<script>document.getElementById("failure").innerHTML = "<p>Title or post content not entered.</p>";</script>';
			} else {
	            // attempt insert query execution
	            $sql = "INSERT INTO post(postuser,postcontent,posttitle) VALUES ('$username', '$content', '$title')";
	            if(mysqli_query($link, $sql)) {
	                // echo "Records added successfully.";
	                echo "<script> alert('Post Submitted Succesfully'); window.location.assign('home.php'); </script>";
	            } else {
	                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	            }
			}
    	}
    	?>
    </div>
	</body>
</html>