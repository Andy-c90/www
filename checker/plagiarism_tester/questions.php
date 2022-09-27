<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="">
<head>
<title>Questions</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>
<body id="top">
<?php
require "functions.php";
#connection to the database
$conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
$bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");

session_start();
if (!isset($_SESSION['profile'])) {
    header("Location: index.php");
} else {
    $profile = unserialize($_SESSION['profile']);
	$id = $profile[0];
	$name = $profile[1];
	$email = $profile[2];
	$submittion_detail = unserialize($_SESSION['submittion_detail']);
	$course = explode("--",$submittion_detail[0]);
	$assignment = explode("--",$submittion_detail[1]);
	
	$default_student_id = $id;
	$default_assignment_id = $assignment[0];
	
	$course_id = $course[0];
	
}


if (isset($_POST['upload'])){
  #getting the file
  $file = rand(1000,100000)."-".$_FILES['file_up']['name'];
  $file_loc = $_FILES['file_up']['tmp_name'];
  $file_size = $_FILES['file_up']['size'];
  $file_type = $_FILES['file_up']['type'];
  $folder="upload/";


  $new_size = $file_size/1024;  
  $new_file_name = strtolower($file); 
  $final_file=str_replace(' ','-',$new_file_name);
  
  if(move_uploaded_file($file_loc,$folder.$final_file))
  {
	
	$zip = new ZipArchive();
    $x = $zip->open($folder.$file);
    if($x === true) {
		for($i = 0; $i < $zip->numFiles; $i++) 
		{   
			$fileInZip = $zip->statIndex($i);
			$filename = $fileInZip['name'];
			$zip->renameIndex($i, $filename.'.txt');
			$zip->close();
			$x = $zip->open($folder.$file);
        }
		
        $zip->extractTo($folder);
        $zip->close();
    } else {
        die("There was a problem. Please try again!");
    }
    #checking if the file has the appropriate extension, if it does we store it in the database, if it doesn't we display an error and we redirect the student to the uplaod_file page
    $array = explode('.', $final_file);
    $extension = end($array);
      if($extension == "zip"){
        $request_insert_file = "insert into submission(student,assignment,file,course_id) values(".$default_student_id.",".$default_assignment_id.",'".$final_file."',".$course_id.")";
        $result = mysqli_query($conn,$request_insert_file);
        $last_submission_id = mysqli_insert_id($conn);
        $_SESSION["submission_id"] = $last_submission_id;
        echo "<script type=\"text/javascript\">alert('Success : File Submited Successfully')</script>";
      }
      else {
        echo "<script type=\"text/javascript\">alert('Error : Upload a zip File')</script>";
        //header('Location: file_upload.php'); 
      }
	  
 }
}

if(isset($_POST['username'])){
	$username = $_POST['username'];
	$token = $_POST['accessToken'];
	$repository = $_POST['repository'];
	$folder="upload/";
	$URL="https://github.com/".$username."/".$repository."/archive/master.zip";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,'https://github.com');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "$username:$token");

	curl_setopt($ch, CURLOPT_URL,$URL);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	$zipfile=curl_exec ($ch);
	curl_close ($ch);
	$filename = rand(1000,100000)."-".$repository;
	file_put_contents($folder.$filename.'.zip', $zipfile);
	$zip = new ZipArchive(); 

	$x = $zip->open('upload/'.$filename.'.zip'); 
	if($x === true) {
		for($i = 0; $i < $zip->numFiles; $i++) 
		{   
			$fileInZip = $zip->statIndex($i);
			$fname = $fileInZip['name'];
			$zip->renameIndex($i, $fname.'.txt');
			
        }
		
		$zip->extractTo("upload/");
        $zip->close();
        
    } else {
        die("There was a problem. Please try again!");
    }
	
	$final_file = basename('upload/'.$filename.'.zip');
	$request_insert_file = "insert into submission(student,assignment,file,course_id) values(".$default_student_id.",".$default_assignment_id.",'".LOAD_FILE('$final_file')."',".$course_id.")";
    $result = mysqli_query($conn,$request_insert_file);
    $last_submission_id = mysqli_insert_id($conn);
    $_SESSION["submission_id"] = $last_submission_id;
    echo "<script type=\"text/javascript\">alert('Success : File Submited Successfully')</script>";
}
?>
<div class="bgded" style="background-image:url('images/demo/backgrounds/01.webp');"> 
  <div class="wrapper overlay row0">
    <div id="topbar" class="hoc clear">
      <div class="fl_left"> 

      </div>
    </div>
  </div>
</div>
<?php 
#getting the ids of all the questions in database
$req_get_question_ids = "SELECT question_id from question";
$res_ids = mysqli_query($conn,$req_get_question_ids);
$ids = array();
while($l = mysqli_fetch_row($res_ids)){
  array_push($ids,$l[0]);
}

$radom_5_questions = array();
for ($i=1;$i<=5;$i++){
    shuffle($ids);
    $selected = array_pop($ids);
    array_push($radom_5_questions,$selected);
}
?>
<div class="wrapper overlay">
    <div id="pageintro2" class="hoc"> 
      <article>
        <h3 class="heading">Welcome To Plagiarism Checker</h3>
        <br></br>
        <span class="center_title">Answer the questions to complete the test</span>
        <br></br><br>
      </article>
    </div>
  </div>
<div class="wrapper row3">
  <main class="hoc container clear"> 
    <section id="introblocks">
      <ul class="nospace group elements elements-three">
        <?php 
        //getting the question description for each of the 5 random questions
        $index = 0;
        foreach ($radom_5_questions as $question_id) {
          $index++;
          $request_get_question = "select description from question where question_id=".$question_id;
          $request_get_question_result = mysqli_query($conn,$request_get_question);

        //displaying the questions 
          while($l = mysqli_fetch_row($request_get_question_result)){
            $description = $l[0];
            echo "<li class='one_third'>
                    <article>
                      <footer><a href='answer_form.php?question_id=".$question_id."&question_number=".$index."'><i class='fas fa-question'></i></a></footer>
                      <h6 class='heading'>Question ".$index."</h6>
                      <p>".$description."</p>
                    </article>
                  </li>";
          }
        }
        ?>
      </ul>
    </section>
    <div class="clear"></div>
  </main>
</div>


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