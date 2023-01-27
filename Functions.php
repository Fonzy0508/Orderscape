<?php    
    function CheckLogin($con){
        date_default_timezone_set("singapore");
        if(isset($_SESSION['UserID'])){
            $ID = $_SESSION['UserID'];
            $query = "select * from accounts where AccountID = '$ID' limit 1";
            $result =mysqli_query($con,$query);
            if($result && mysqli_num_rows($result)>0){
                $UserData = mysqli_fetch_assoc($result);
                return $UserData;
            }
        }        
        header("Location: ../Login_Page/LoginPage.php");
        die;
        
    }
?>