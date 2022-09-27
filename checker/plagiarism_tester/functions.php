<?php
 
function openZip($file_to_open) {
    #getting the file
  $conn = mysqli_connect("localhost:3306","root","") or die("Erreur: Connection Issue");
  $bd = mysqli_select_db($conn,"checker") or die("Errreur: Database Issue");
  
  $file = rand(1000,100000)."-".$_FILES[$file_to_open]['name'];
  $file_loc = $_FILES[$file_to_open]['tmp_name'];
  $file_size = $_FILES[$file_to_open]['size'];
  $file_type = $_FILES[$file_to_open]['type'];
  $folder="upload/";
  echo "test";

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
        $request_insert_file = "insert into submission(student,assignment,file) values(".$default_student_id.",".$default_assignment_id.",'".$final_file."')";
        $result = mysqli_query($conn,$request_insert_file);
        $last_submission_id = mysqli_insert_id($conn);
        $_SESSION["submission_id"] = $last_submission_id;
        echo "<script type=\"text/javascript\">alert('Success : File Submited Successfully')</script>";
      }
      else {
        echo "<script type=\"text/javascript\">alert('Error : Upload a zip File')</script>";
        //header('Location: file_upload.php'); 
      }
	  
	  echo $final_file;

 }

}

function openGit($username,$repository,$token){
	echo $repository;
	
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

	$result=curl_exec ($ch);
	curl_close ($ch);
	
	
}
?>