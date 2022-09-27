<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

?>
<!DOCTYPE html>
<html lang="">
<head>
<title>Plagiarism Checker</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/login_style.css" rel="stylesheet" type="text/css">
<style>
	* {
	  margin: 0;
	  padding: 0;
	  box-sizing: border-box;
	  -webkit-font-smoothing: antialiased;
	}

	.background-color {
	  background: #b0ca35;
	  font-family: 'Rubik', sans-serif;
	}

	.login-form {
	  background: #fff;
	  width: 500px;
	  margin: 65px auto;
	  display: -webkit-box;
	  display: flex;
	  -webkit-box-orient: vertical;
	  -webkit-box-direction: normal;
			  flex-direction: column;
	  border-radius: 4px;
	  box-shadow: 0 2px 25px rgba(0, 0, 0, 0.2);
	}
	.login-form h1 {
	  padding: 35px 35px 0 35px;
	  font-weight: 300;
	}
	.login-form .content {
	  padding: 35px;
	  text-align: center;
	}
	.login-form .input-field {
	  padding: 12px 5px;
	}
	.login-form .input-field input {
	  font-size: 16px;
	  display: block;
	  font-family: 'Rubik', sans-serif;
	  width: 100%;
	  padding: 10px 1px;
	  border: 0;
	  border-bottom: 1px solid #747474;
	  outline: none;
	  -webkit-transition: all .2s;
	  transition: all .2s;
	}
	.login-form .input-field input::-webkit-input-placeholder {
	  text-transform: uppercase;
	}
	.login-form .input-field input::-moz-placeholder {
	  text-transform: uppercase;
	}
	.login-form .input-field input:-ms-input-placeholder {
	  text-transform: uppercase;
	}
	.login-form .input-field input::-ms-input-placeholder {
	  text-transform: uppercase;
	}
	.login-form .input-field input::placeholder {
	  text-transform: uppercase;
	}
	.login-form .input-field input:focus {
	  border-color: #222;
	}
	.login-form a.link {
	  text-decoration: none;
	  color: #747474;
	  letter-spacing: 0.2px;
	  text-transform: uppercase;
	  display: inline-block;
	  margin-top: 20px;
	}
	.login-form .action {
	  display: -webkit-box;
	  display: flex;
	  -webkit-box-orient: horizontal;
	  -webkit-box-direction: normal;
			  flex-direction: row;
	}
	.login-form .action input[type=submit] {
	  width: 100%;
	  border: none;
	  padding: 18px;
	  font-family: 'Rubik', sans-serif;
	  cursor: pointer;
	  text-transform: uppercase;
	  background: #e8e9ec;
	  color: #777;
	  border-bottom-left-radius: 4px;
	  border-bottom-right-radius: 0;
	  letter-spacing: 0.2px;
	  outline: 0;
	  -webkit-transition: all .3s;
	  transition: all .3s;
	}
	.login-form .action input[type=submit]:hover {
	  background: #d8d8d8;
	}
	.login-form .action input[type=submit]:nth-child(2) {
	  background: #2d3b55;
	  color: #fff;
	  border-bottom-left-radius: 0;
	  border-bottom-right-radius: 4px;
	}
	.login-form .action input[type=submit]:nth-child(2):hover {
	  background: #3c4d6d;
	}
</style>
</head>
<?php
		ob_start();
		if (!empty($_POST["register"])) {
			$email = $_POST["email"];
			$password = $_POST["password"];
			$cpassword = $_POST["cpassword"];
			$studentId = $_POST["id"];
			$name = $_POST["name"];
			$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
			$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");
			$chk_email = "select * from student where email='$email'";
            $get_email = mysqli_query($conn,$chk_email);
			if($l = mysqli_fetch_row($get_email)){
				echo '<script>alert("Email have already being used by another account.")</script>';
				
			}elseif($password != $cpassword){
				echo '<script>alert("Confirm password does not match password.")</script>';
			}else{
				$create_sql = "INSERT INTO `student` (`student_id`, `name`,`email`,`password`) VALUES ('$studentId', '$name', '$email', '$password')";
				
				if (mysqli_query($conn,$create_sql)){
					mysqli_close($conn);
					echo '<script>alert("Account Created")</script>';
					
					
					header("Location:login.php");
				}else{
					mysqli_close($conn);
					echo '<script>alert("An error have occur please try again")</script>';
				}
			}
		}

?>
<body id="top">
  <div class="bgded" style="background-image:url('images/demo/backgrounds/01.webp');"> 
    <div class="wrapper overlay row0">
      <div id="topbar" class="hoc clear">
        <div class="fl_left"> 
          
        </div>
      </div>
    </div>
  </div>

  <div class="wrapper overlay">
    <div id="pageintro2" class="hoc"> 
      <article>
        <h3 class="heading">Code Submission System</h3>
      </article>
    </div>
  </div>
	

  <div class="background-color">
		<br>
		<div class="login-form">
			<form method="post" action="create_account.php" novalidate="novalidate">
				<h1 class="login-label">Create Student Account</h1>
				<div class="content">
					<div class="input-field">
						<input type="email" placeholder="Email" name ="email" autocomplete="nope">
					</div>
					<div class="input-field">
						<input type="text" placeholder="Student ID" name = "id" autocomplete="nope">
					</div>
					<div class="input-field">
						<input type="text" placeholder="Student Name" name = "name" autocomplete="nope">
					</div>
					<div class="input-field">
						<input type="password" placeholder="password" name = "password" autocomplete="nope">
					</div>
					<div class="input-field">
						<input type="password" placeholder="confirm password" name = "cpassword" autocomplete="nope">
					</div>
				
					<div class="action">
					  <input type="submit" value="Register" name="register">
					</div>
			</form>
		</div>
		<br>
	</div>
	
	
	
  <div class="wrapper row5">
    <div id="copyright" class="hoc clear"> 
      <p class="fl_left">Copyright &copy; 2018 - All Rights Reserved - <a href="#">Domain Name</a></p>
      <p class="fl_right">Template by <a target="_blank" href="https://www.os-templates.com/" title="Free Website Templates">OS Templates</a></p>
    </div>
  </div>

<a id="backtotop" href="#top"><i class="fas fa-chevron-up"></i></a>

<script src="layout/scripts/jquery.min.js"></script>
<script src="layout/scripts/jquery.backtotop.js"></script>
<script src="layout/scripts/jquery.mobilemenu.js"></script>
<script src="layout/scripts/jquery.easypiechart.min.js"></script>


</body>
</html>



