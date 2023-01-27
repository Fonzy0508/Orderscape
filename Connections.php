<?php
    $db_Host = "localhost";
    $db_User = "root";
    $db_Pass = "";
    $db_Name ="orderscapedb";
    if(!$con = mysqli_connect($db_Host,$db_User,$db_Pass,$db_Name)){
        die("Failed");
    }    
?>