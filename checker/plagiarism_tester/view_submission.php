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
    header("Location: login.php");
} else {
    $profile = unserialize($_SESSION['profile']);
	$student_id = $profile[0];
	$student_name = $profile[1];
	$student_email = $profile[2];
}


$sql_getenrollement = "select * from submission where student='$student_id'";
$get_enroollement = mysqli_query($conn,$sql_getenrollement);
$rows = mysqli_fetch_row($get_enroollement);
$courses = array();
$count = 0;
while($rows){
	$courses[$count] = array($rows[0],$rows[1],$rows[2],$rows[3],$rows[4]); 
	$rows = mysqli_fetch_row($get_enroollement);
	$count+=1;
}




?>
<div class="wrapper coloured">
    <article id="cta" class="hoc container clear"> 
          <h6 class="heading"><span>&ldquo;</span>View Submission<span>&bdquo;</span></h6>
		  
	<table>
		<tr>
			<th>Submission ID</th>
			<th>Assignment ID</th>
			<th>Submssion</th>
			<th>Click to Download</th>
		</tr>
		
		<?php
			if($courses == null){
				echo "<tr>You have not submit any assignment</tr>";
			}else{
				foreach($courses as $course){
						echo "
							<tr>
								<th>".$course[0]."</th>
								<th>".$course[2]."</th>
								<th>".$course[3]."</th>
								<th><input type='submit' class='white_transparent' name='upload' value='Download'></input></th>
							</tr>
						";
				}
			}
			
			function download_file( $fullPath )
			{
			  if( headers_sent() )
				die('Headers Sent');


			  if(ini_get('zlib.output_compression'))
				ini_set('zlib.output_compression', 'Off');


			  if( file_exists($fullPath) )
			  {

				$fsize = filesize($fullPath);
				$path_parts = pathinfo($fullPath);
				$ext = strtolower($path_parts["extension"]);

				switch ($ext) 
				{
				  case "zip": $ctype="application/zip"; break;
				  default: $ctype="application/force-download";
				}

				header("Pragma: public"); 
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false); 
				header("Content-Type: $ctype");
				header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".$fsize);
				ob_clean();
				flush();
				readfile( $fullPath );
			  } 
			  else
				die('File Not Found');

			}
		?>
	  
	</table> 
			
		  
    
        
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