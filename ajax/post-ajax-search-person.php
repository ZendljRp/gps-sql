<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: text/html;charset=utf-8");
include '../conecction/conecction.php';
$conn = conn();
if(!empty($_POST["btnGrabar"])){
    $fchini   = $_POST['fchini'];
    $dni      = $_POST['strInput'];
    $telefono = $_POST['strTelf'];
    $gestion  = $_POST['txtcomentario'];
    
    
    $sql = "";
    
    
    
    
}

