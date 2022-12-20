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
				<a href="submit.php"><i class="fa-solid fa-poo"></i>Create Post</a>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		</div>
	<div class="content-container">
		<?php

		function timeSince($time) {
			$time = time() - $time; // to get the time since that moment
			$time = ($time<1)? 1 : $time;
			$tokens = array (31536000 => 'year', 2592000 => 'month', 604800 => 'week', 86400 => 'day', 3600 => 'hour', 60 => 'minute', 1 => 'second');
			foreach ($tokens as $unit => $text) {
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
			}
		}

		$link = mysqli_connect("localhost", "root", "", "phplogin");
		$query = "SELECT postid, postuser, postcontent, postts, posttitle, upvotes, downvotes FROM post ORDER BY log10(abs(upvotes-downvotes) + 1)*sign(upvotes-downvotes)+(unix_timestamp(postts)/300000) DESC";
		$result = mysqli_query($link, $query);
		if ($link === false) {
			die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		if(mysqli_query($link, $query)) {
			while ($row = mysqli_fetch_array($result)) {
				$id = htmlspecialchars($row['postid'], ENT_QUOTES, 'UTF-8');
				$username = htmlspecialchars($row['postuser'], ENT_QUOTES, 'UTF-8');
				$title = htmlspecialchars($row['posttitle'], ENT_QUOTES, 'UTF-8');
				// $score = htmlspecialchars($row['postscore'], ENT_QUOTES, 'UTF-8');
				$score =0;
				echo '<div class="row" id="post_' . $id  . '"' . '><div class="score-container"><form method="POST" id="votearrow"' . '"><input name="updoot" class="upvoteinput" type="image" id="updoot-';
				echo $id  . '" value="updoot" src="images/upvote.gif"/></form><span class="score">' . $score . '</span><form method="post" id="votearrow"><input name="downdoot" class="upvoteinput" type="image" id="downdoot-';
				echo $id . '" value="downdoot" src="images/downvote.gif"/></form></div><div class="post-container"><a href="viewpost.php?postid=' . $id . '">' . $title . '</a><p id="submission-info">
				<i class="fa fa-user"></i> submitted by <a href="?profile=' . $username . '">' . $username . '</a> <i class="fa fa-calendar"></i> ';
				echo timeSince(strtotime($row['postts'])) . ' ago, <a href="viewpost.php?postid=' . $id  . '"> add a comment</a></p></div></div>';
			}
		} else {
			echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
		}
		$id = $row['id'];
		?>
		<?php 
		if (isset($_SESSION['uname'])) { echo "<input type='hidden' id='username' value='".$_SESSION['uname']."'/>"; }?>
		<script>
		$(document).ready(function() {
			$('.upvoteinput').click(function() {
				var id2 = $(this).attr('id');
				var id = id2.substr(id2.indexOf("-") + 1);
				var username = $('#username').val();
				if (username == null) {
					return false;
				}
				var votevalue = 0;
				if (id2.startsWith("updoot")) votevalue = 1;
				if (id2.startsWith("downdoot")) votevalue = -1;
				$.ajax({
					type: "POST",
					url: "vote.php?postid=" + id + "&username=" + username +"&vote=" + votevalue,
					data: "",
					success: function(msg){},
					error: function(msg){}
				});
			});
		});
		</script>
	</div>
	</body>
</html>

<!-- <p>Welcome back, 
	<?=$_SESSION['name']?>
	!</p> -->
	