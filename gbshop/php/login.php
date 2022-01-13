<?php
include_once("dbconnect.php");
session_start();

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim(sha1($_POST['password']));
    $sqllogin = "SELECT * FROM tbl_user WHERE email = '$email' AND password = '$password'";

    $select_stmt = $conn->prepare($sqllogin);
    $select_stmt->execute();
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
    if ($select_stmt->rowCount() > 0) {
        $_SESSION["session_id"] = session_id();
        $_SESSION["email"] = $email;
        $_SESSION["name"] = $row['name'];
        $_SESSION["phone"] = $row['phone'];
        $_SESSION["datereg"] = $row['date_reg'];
        $_SESSION["pass"] = $row['password'];
        echo "<script> alert('Login successful')</script>";
        echo "<script> window.location.replace('../php/mainpage.php')</script>";
    } else {
        session_unset();
        session_destroy();
        echo "<script> alert('Login fail')</script>";
        echo "<script> window.location.replace('../php/login.php')</script>";
    }
}
if (isset($_GET["status"])) {
    if (($_GET["status"] == "logout")) {
        session_unset();
        session_destroy();
        echo "<script> alert('Session Cleared')</script>";
    }
}

?>
<!DOCTYPE html>
<html>

<head>
	<title>Login Form</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="../js/myscript.js"></script>
	<link rel="stylesheet" href="../css/mainpage.css">
</head>

<body>
	<div class="header">
	<h1> "Surprises Gift BOX-BOUQUET"</h1>
		<p>Myshop 2021.</p>
	</div>
	<div class="topnavbar">
        <a href="../index.php">HOME</a>
    
        <a href="../php/register.php" class="right">Register</a>
    </div>
	<div class="main">
		<center>
			<img src="../images/login.jpg">
		</center>
		<div class="container">
			<form name="loginForm" action="../php/login.php" onsubmit="return validateLoginForm()" method="post">
				<div class="row">
					<div class="col-25">
						<label for="femail"><b>Email</b></label>
					</div>
					<div class="col-75">
						<input type="text" id="idemail" name="email" placeholder="Your email..">
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="lname"><b>Password</b></label>
					</div>
					<div class="col-75">
						<input type="password" id="idpass" name="password" placeholder="Your password..">
					</div>
				</div>
				<div class="row">
					<div class="col-75">
						<div>
							<label>
								<input type="checkbox" checked="checked" name="remember"><i> Remember me</i>
							</label>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<input type="submit" name="submit" value="Submit">
				</div>
			</form>
		</div>
		

	</div>

	<div class="bottomnavbar">
		<a href="#contact"></a>
	</div>

</body>

</html>
