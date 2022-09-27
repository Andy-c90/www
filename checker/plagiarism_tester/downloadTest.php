<?php
// connect to the database
$conn = mysqli_connect('localhost', 'root', '', 'checker');

$sql = "SELECT file FROM submission where submission_id=40";
$result = mysqli_query($conn, $sql);

$file = mysqli_fetch_assoc($result);

$final_file = 'download/'.$file['filename'];
    
?>