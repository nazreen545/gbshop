<?php
    include_once("../php/dbconnect.php");
    if (isset($_POST['submit'])) {
        if (!(isset($_POST["name"]) || isset($_POST["email"]) || isset($_POST["phone"]) || isset($_POST["passworda"]) || isset($_POST["passwordb"]))) {
            echo "<script> alert('Please fill in all required information')</script>";
            echo "<script> window.location.replace('../php/register.php')</script>";
        } else {
            if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
				$name = $_POST["name"];
                $email = $_POST["email"];
                $phone = $_POST["phone"];
                $passa = $_POST["passworda"];
                $passb = $_POST["passwordb"];
                $shapass = sha1($passa);
                $otp = rand(1000, 9999);
                $sqlregister = "INSERT INTO tbl_user(email,phone,name,password,otp) VALUES('$email','$phone','$name','$shapass','$otp')";
                try {
                    $conn->exec($sqlregister);
                    uploadImage($email);
                    echo "<script>alert('Registration successful')</script>";
                    echo "<script>window.location.replace('../php/login.php')</script>";
                } catch (PDOException $e) {
                    echo "<script>alert('Registration failed')</script>";
                    echo "<script>window.location.replace('../php/register.php')</script>";
                }
            } else {
                echo "<script>alert('No image selected')</script>";
                echo "<script>window.location.replace('../php/register.php')</script>";
            }
        }
    }
    function uploadImage($email)
    {
        $target_dir = "../images/profile/";
        $target_file = $target_dir . $email . ".png";
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
    }
    ?>
    <!DOCTYPE html>
<html>

<head>
	<title>Registration Form</title>
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
        <a href="#"></a>
        <a href="#"></a>
        <a href="../php/login.php" class="right">Login</a>
    </div>

	<div class="main">
		<center><img src="../images/login.jpg"></center>
		<div class="container">
			<form name="registerForm" action="../php/register.php" onsubmit="return validateRegForm()" method="post" enctype="multipart/form-data">
			<div class="row-single">
                            <img class="imgselection" src="../images/profile/profile.png" ><br>
                            <input type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>	
            <div class="row">
					<div class="col-25">
						<label for="fname"><b>Name</b></label>
					</div>
					<div class="col-75">
						<input type="text" id="idname" name="name" placeholder="Your name..">
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="lname"><b>Email</b></label>
					</div>
					<div class="col-75">
						<input type="text" id="idemail" name="email" placeholder="Your last name..">
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="lphone"><b>Phone</b></label>
					</div>
					<div class="col-75">
						<input type="tel" id="idphone" name="phone" placeholder="Your phone number..">
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="lpassword"><b>Password</b></label>
					</div>
					<div class="col-75">
						<input type="password" id="idpass" name="passworda" placeholder="Your password..">
					</div>
				</div>
				<div class="row">
					<div class="col-25">
						<label for="lpassword"><b>Password</b></label>
					</div>
					<div class="col-75">
						<input type="password" id="idpassb" name="passwordb" placeholder="Re-enter password">
					</div>
				</div>

				<div class="row">
					<div><input type="submit" name="submit" value="Submit"></div>
				</div>

			</form>

		</div>
		</div>


	</div>


	<div class="bottomnavbar">
		<a href="../index.php"></a>
		<a href="#news"></a>
		<a href="#contact"></a>
	</div>

</body>

</html>