<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$mid=$name=$email=$iam='';
$content=$errormsg='';
if(isset($_SESSION['mid'])){
$mid=$_SESSION['mid'];
$name=$_SESSION['name'];
$email=$_SESSION['email'];
$iam=$_SESSION['iam'];
$adminrights=$_SESSION['adminrights'];
$cid=$_SESSION['cid'];
$admin=$_SESSION['admin'];
$rcountry=$_SESSION['country'];


require_once '../config.php';
if(isset($_GET['id'])){
$requestid=$_GET['id'];

mysqli_query($link,"delete from submitted_candidates where id='$requestid'");
header('location: all');
exit();
}
else
$content="select a candidate to delete submission";
}	
else{
$errormsg="Access denied";	
}
?>
