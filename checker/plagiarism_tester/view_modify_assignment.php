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
	  width: 70%;
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
	  width: 80%;
	  padding: 10px 1px;
	  border: 0;
	  border-bottom: 1px solid #747474;
	  outline: none;
	  -webkit-transition: all .2s;
	  transition: all .2s;
	  
	}
	.login-form .input-field label {
	  font-size: 16px;
	  display: block;
	  font-family: 'Rubik', sans-serif;
	  padding: 10px 1px;
	  border: 0;
	  outline: none;
	  -webkit-transition: all .2s;
	  transition: all .2s;
	  
	  text-align: left;
	  float:left;
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
		
		if (!empty($_POST["edit"])) {
			$assignment_id = $_POST["assignment_id"];
			$course_id = $_POST["course_id"];
			$description = $_POST["description"];
			$sql = "UPDATE `assignment` SET `description` = '$description' WHERE `assignment`.`assignment_id` = $assignment_id; ";
			
			$update_sql = mysqli_query($conn, $sql);
			if ($update_sql) {
			  echo '<script>alert("Assignment updated")</script>';
			} else {
			  echo '<script>alert("An error have occur please try again")</script>';
			}

		}
		
		if (!empty($_POST["delete"])) {
			$assignment_id = $_POST["assignment_id"];
			$sql = "DELETE FROM `assignment` WHERE `assignment`.`assignment_id` = $assignment_id; ";
			
			$update_sql = mysqli_query($conn, $sql);
			if ($update_sql) {
			  echo '<script>alert("Assignment deleted")</script>';
			} else {
			  echo '<script>alert("An error have occur please try again")</script>';
			}

		}
	
	?>
	<div class="background-color">
		<?php
			foreach($courses as $course){
				echo $course."<br>";
				$sql_get = "select * from assignment where course = '$course' ";
				$get = mysqli_query($conn,$sql_get);
				$rows = mysqli_fetch_row($get);
				$holders = array();
				$count = 0;
				$assignmentId = array();
				while($rows){
					$holders[$count] = array($rows[0],$rows[1],$rows[2]); 
					$assignmentId[$count] = $rows[0];
					$rows = mysqli_fetch_row($get);
					$count+=1;
				}
				foreach($holders as $holder){
					?>
					<div class="login-form">
					<form method="post" action="view_modify_assignment.php" novalidate="novalidate">
					<h1 class="login-label"><?php echo $holder[0]."--".$holder[2]?></h1>
						<div class="content">
						<div class="input-field">
							<label>Assignment ID:</label><input type="text"  readonly value="<?php echo $holder[0]?>" name = "assignment_id" autocomplete="nope"> 
						</div>
						<div class="input-field">
							<label>Course ID:</label><input type="text"  readonly value="<?php echo $holder[1]?>" name = "course_id" autocomplete="nope">
						</div>
						<div class="input-field">
							<label>Assignment Description:</label><textarea cols="100" rows="4"  name = "description" autocomplete="nope" required><?php echo $holder[2]?></textarea>
						</div>
					
						<div class="action">
						  <input type="submit" value="edit" name="edit">
						  <input type="submit" value="delete" name="delete">
						</div>
						</div>
					</form>
					</div>
					<?php
				}
			}						
		?>
		
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