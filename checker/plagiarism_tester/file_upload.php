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
#connection to the database
$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");

session_start();
if (!isset($_SESSION['profile'])) {
    header("Location: index.php");
} else {
    $profile = unserialize($_SESSION['profile']);
	$id = $profile[0];
	$name = $profile[01];
	$email = $profile[2];
}

include "navbar.php";

echo "Welcome ".$name."<br>" ; 



if (!empty($_POST["assignment"])&&!empty($_POST["course"])) {
	$course = $_POST["course"];
	$assignment = $_POST["assignment"];
}else{
	header("Location: select_course.php");
}

echo $course."<br>";
echo $assignment;

$submittion_detail = array($course,$assignment);
$_SESSION['submittion_detail'] = serialize($submittion_detail);

?>
  <div class="wrapper coloured">
    <article id="cta" class="hoc container clear"> 
		
        <form method="post" action="questions.php" enctype="multipart/form-data">
          <p>File upload</p>
          <h6 class="heading"><span>&ldquo;</span>Upload a file and continue<span>&bdquo;</span></h6>
          <input type="file" name="file_up" id="file" class="btn file"/>
          <br></br>
          <p><input type="submit" class="white_transparent" name="upload" value="Continue"></input></p>

        </form>
		<form method="post" action="questions.php" >
          <p>Zip repository upload</p>
          <h6 class="heading"><span>&ldquo;</span>Fill in github information and continue<span>&bdquo;</span></h6>
			  <input type="text" name="username" id="username" placeholder ="username"  class="btn file"/></label><br>
			  <input type="text" name="accessToken" id="accessToken" placeholder ="Access Token" class="btn file"/></label><br>
			  <input type="text" name="repository" id="repository" placeholder ="Repository" class="btn file"/></label>
          <br></br>
          <p><input type="submit" class="white_transparent" name="gitupload" value="Continue"></input></p>

        </form>
    </article>
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