<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);

?>
<?php
#connection to the database
$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");


?>

<!DOCTYPE html>
<html lang="">
<head>
<title>Plagiarism Checker</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css">

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
	.login-form .input-field select {
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
	
	<?php
		session_start();
		if (!isset($_SESSION['profile'])) {
			header("Location: login.php");
		} else {
			$profile = unserialize($_SESSION['profile']);
			$convenor_id = $profile[0];
			$convenor_name = $profile[1];
			$convenor_email = $profile[2];
			
			$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
			$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");
			$sql_course = "select * from course where convenor='$convenor_id' ";
			$get_course = mysqli_query($conn,$sql_course);
			$rows = mysqli_fetch_row($get_course);
			$courses = array();
			$count = 0;
			while($rows){
				$courses[$count] = $rows[0]."--".$rows[1]; 
				$rows = mysqli_fetch_row($get_course);
				$count+=1;
			}
		}
		
		if (!empty($_POST["create"])) {
			$assignment_id = $_POST["id"];
			$assignment_course_id = $_POST["course"];
			$assignment_desciption = $_POST["description"];
			$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
			$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");
			$chk_assignment = "select * from assignment where assignment_id='$assignment_id'";
            $get_assignment= mysqli_query($conn,$chk_assignment);
			if($l = mysqli_fetch_row($get_assignment)){
				echo '<script>alert("Assignment id have already being create.")</script>';
				
			}else{
				$create_sql = "INSERT INTO `assignment` (`assignment_id`, `course`,`description`) VALUES ('$assignment_id', '$assignment_course_id', '$assignment_desciption')";
				
				if (mysqli_query($conn,$create_sql)){
					mysqli_close($conn);
					echo '<script>alert("assignment Created")</script>';
					
					
				}else{
					mysqli_close($conn);
					echo '<script>alert("An error have occur please try again")</script>';
				}
			}
		}
	?>
	<div class="background-color">
		<br>
		<div class="login-form">
			<form method="post" action="create_assignment.php" novalidate="novalidate">
				<h1 class="login-label">Create Assignment</h1>
				<div class="content">
					<div class="input-field">
						<input type="text" placeholder="Assignment ID" name = "id" autocomplete="nope" required>
					</div>
					<div class="input-field">
						<select  name="course" id="course" required >
							<option value="" disabled selected hidden>Choose a course</option>
							<?php
								foreach($courses as $course){
									echo '<option value='.$course.'>'.$course.'</option>';
								}
							?>
						</select>
					</div>
					<div class="input-field">
						<input type="text" placeholder="Course Description" name = "description" autocomplete="nope" required>
					</div>
				
					<div class="action">
					  <input type="submit" value="create" name="create">
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