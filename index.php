<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>

<?php

require('config.php');

session_start();
if (isset($_POST['username'])){
  $username = stripslashes($_REQUEST['username']);
  $username = mysqli_real_escape_string($conn, $username);
  $password = stripslashes($_REQUEST['password']);
  $password = mysqli_real_escape_string($conn, $password);

  $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
  $result = mysqli_query($conn,$query) or die(mysql_error());
  $rows = mysqli_num_rows($result);
  if($rows==1){
      $_SESSION['username'] = $username;
      header("Location: map.php");
  }else{
    $message = "Le nom d'utilisateur ou le mot de passe est incorrect.";
  }
}
?>

<div class="main" style="">
	<div class="logo"></div>
	<div class="title">Login</div>
	<form method="POST" name="login">
	<div class="credentials">
		<div class="username">
			<span class="glyphicon glyphicon-user"></span>
			<input type="text" name="username" placeholder="Username" required="" >
		</div>
		<div class="password">
			<span class="glyphicon glyphicon-lock"></span>
			<input type="password" name="password" placeholder="Password" required="">
		</div>
	</div>
	<button class="submit">Submit</button>
	<?php if (! empty($message)) { ?>
    <p class="errorMessage"><?php echo $message; ?></p>
	<?php } ?>
	</form>
</div>

</body>
</html>
