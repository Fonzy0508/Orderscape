<?php
include("Connections.php");
session_start();
if(isset($_SESSION['UserID'])){
    $ID = $_SESSION['UserID'];
    $queryOffline = "UPDATE accounts SET ActiveStatus = 'false' WHERE AccountID = $ID";
    $resultOffline = mysqli_query($con, $queryOffline);
    unset($_SESSION['UserID']);
}
header("Location: index.php");
die;
?>