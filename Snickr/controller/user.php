<?php 
include 'authorization.php';
include 'dbqueries.php';

if (isset($_POST['uinfo'])) {
    
    $uname=htmlspecialchars($_POST['uname']);         
    $unickname=htmlspecialchars($_POST['unick']);    
    $uphone=htmlspecialchars($_POST['ucontact']); 
    $ujob=htmlspecialchars($_POST['ujob']);  
    $id=htmlspecialchars($_SESSION['id']);       
    updateUser($id,$uname,$unickname,$uphone,$ujob);
    
}

