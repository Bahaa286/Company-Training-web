<?php 

session_start();

unset($_SESSION['user_id']);
unset($_SESSION['desplay_name']);

if(isset($_SESSION['student_id'])){
    unset($_SESSION['student_id']);
}
if(isset($_SESSION['company_id'])){
    unset($_SESSION['company_id']);
}

unset($_SESSION['username']);

session_destroy();

header("location: index.php");

?>