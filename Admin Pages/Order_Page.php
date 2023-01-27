<?php

use LDAP\Result;

session_start();
include("../Connections.php");
include("../Functions.php");
$AT = $_SESSION['A_Type'];
//Checks if an account is logged in
$Current_UserData = CheckLogin($con);
//gets current UserID
$AID = $_SESSION['UserID'];
$queryOrders = "SELECT * FROM (SELECT orders.OrderID, orders.AccountID, orders.OrderPrice, orders.Status, accounts.FirstName, accounts.LastName, accounts.Account_Type, accounts.CourseOrPosition
FROM orders INNER JOIN accounts ON orders.AccountID = accounts.AccountID)tb WHERE Account_Type = 'Teacher' OR Account_Type = 'Admin' OR Account_Type = 'Staff'";
$resultOrders = mysqli_query($con, $queryOrders);
//Total numbers of done and cancelled orders
$queryNumD = "SELECT * FROM (SELECT ohistory.OH_ID, ohistory.OrderID, ohistory.Order_Price, ohistory.State, ohistory.AccountID, ohistory.Msg, accounts.Account_Type
FROM ohistory INNER JOIN accounts ON ohistory.AccountID = accounts.AccountID)tb WHERE tb.State = 'Recieved' AND Account_Type = 'Customer'";
$resultDone = mysqli_query($con, $queryNumD);
$NumberDone = mysqli_num_rows($resultDone);
$queryNumC = "SELECT * FROM (SELECT ohistory.OH_ID, ohistory.OrderID, ohistory.Order_Price, ohistory.State, ohistory.AccountID, ohistory.Msg, accounts.Account_Type
FROM ohistory INNER JOIN accounts ON ohistory.AccountID = accounts.AccountID)tb WHERE tb.State = 'Cancelled' AND Account_Type = 'Customer'";
$resultCancell = mysqli_query($con, $queryNumC);
$NumberCancel = mysqli_num_rows($resultCancell);
$queryPNumD = "SELECT * FROM (SELECT ohistory.OH_ID, ohistory.OrderID, ohistory.Order_Price, ohistory.State, ohistory.AccountID, ohistory.Msg, accounts.Account_Type
FROM ohistory INNER JOIN accounts ON ohistory.AccountID = accounts.AccountID)tb WHERE tb.State = 'Recieved' AND Account_Type != 'Customer'";
$resultPDone = mysqli_query($con, $queryPNumD);
$NumberPDone = mysqli_num_rows($resultPDone);
$queryPNumC = "SELECT * FROM (SELECT ohistory.OH_ID, ohistory.OrderID, ohistory.Order_Price, ohistory.State, ohistory.AccountID, ohistory.Msg, accounts.Account_Type
FROM ohistory INNER JOIN accounts ON ohistory.AccountID = accounts.AccountID)tb WHERE tb.State = 'Cancelled' AND Account_Type != 'Customer'";
$resultPCancell = mysqli_query($con, $queryPNumC);
$NumberPCancel = mysqli_num_rows($resultPCancell);
//Form post handler
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['SDone'])) {
        $Time = date("H:i:s");
        $Date = date("Y-m-j");
        $OID = $_POST['OID'];
        $AID = $_POST['AID'];
        $PID = $_POST['PID'];
        $queryOH = "INSERT INTO ohistory(OrderID, Order_Price, ohistory.State, AccountID, Msg, ohistory.Date, ohistory.Time) VALUES($OID,$PID,'Recieved',$AID, 'Your Order has been succesfully received','$Date','$Time')";
        $queryDelO = "DELETE FROM orders WHERE OrderID = $OID AND AccountID = $AID";
        $queryAM = "UPDATE accounts SET AlertMSG = 'Your Order has been succesfully received' WHERE AccountID = $AID";
        $resultOH = mysqli_query($con, $queryOH);
        $resultDO = mysqli_query($con, $queryDelO);
        $resultAM = mysqli_query($con, $queryAM);
        header("Location: Order_Page.php");
    } elseif (isset($_POST['SCancel'])) {
        $Time = date("H:i:s");
        $Date = date("Y-m-j");
        $OID = $_POST['OID'];
        $AID = $_POST['AID'];
        $PID = $_POST['PID'];
        $AMSG = "Order Cancelled";
        if(isset($_POST['AMSG'])){
            $AMSG = $AMSG." : ".$_POST['AMSG'];
        }                
        $queryOH = "INSERT INTO ohistory(OrderID, Order_Price, ohistory.State, AccountID, Msg, ohistory.Date, ohistory.Time) VALUES($OID,$PID,'Cancelled',$AID, '$AMSG','$Date','$Time')";
        $queryDelO = "DELETE FROM orders WHERE OrderID = $OID AND AccountID = $AID";
        $queryAM = "UPDATE accounts SET AlertMSG = '$AMSG' WHERE AccountID = $AID";
        $resultOH = mysqli_query($con, $queryOH);
        $resultDO = mysqli_query($con, $queryDelO);
        $resultAM = mysqli_query($con, $queryAM);        
    } elseif (isset($_POST['Prepare'])) {
        $OID = $_POST['OID'];
        $AID = $_POST['AID'];
        $queryPrepared = "UPDATE orders SET orders.Status = 'Prepared' WHERE AccountID = $AID AND OrderID = $OID";
        mysqli_query($con, $queryPrepared);
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <title>Orders</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <style>
        tr {
            border-bottom: 10px #7f6a6c;
        }

        .Sidezy {
            border-right: 5px solid transparent !important;
        }

        .NBH:hover {
            background-color: #7f6a6c;
        }

        .SBactive:active {
            background-color: #d1a561;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            appearance: none;
            margin: 0;
        }

        .foodtabs {
            border: 0px solid #ecdb9c !important;
            border-bottom: 5px solid #ecdb9c !important;
        }

        .FCh:hover {
            background-color: #d1a561 !important;
        }

        .activezy {
            border-right: 5px solid #d1a561 !important;
        }

        .activezy:active {
            border-right: 5px solid #ecdb9c !important;
        }

        .xH:hover {
            background-color: red !important;
            opacity: 80% !important;
        }

        /*.foodtabs:focus{
       border-bottom: 5px solid #d1a561 !important;
        }*/
        .Searchbar {
            border-top-left-radius: 0px !important;
            border-bottom-left-radius: 0px !important;
            border-left: 0px !important;
        }

        /*Scroll bar CSS*/
        /* width */
        ::-webkit-scrollbar {
            width: 10px;
            position: relative;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #7f6a6c;
            border-radius: 20px;
            z-index: 2;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .scrollautohide {
            overflow: hidden;
        }

        .scrollautohide:hover {
            overflow: auto;
            overflow-y: overlay;

        }
    </style>
</head>

<body class="h-100 overflow-hidden" onload="xhttpload()">
    <header>
        <!--Top navbar-->
        <nav class="navbar navbar-expand-md h-auto" style="background: rgba(127,106,108,.95);">
            <div class="col-12 pt-0 d-block position-absolute top-0" style="height: 10px; background-color: #efd581;">
            </div>
            <div class="container-fluid mt-2">
                <!--Navbar leftside-->
                <a class="navbar-brand" href="#">
                    <button class="d-md-none btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <img src="Images/LogoXT.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                    <img src="Images/ORDERSCAPE-removebg-preview.png" alt="Logo" width="230" height="30" class="d-sm-inline-block d-none align-text-top">
                </a>
                <!--Navbar Rightside-->
                <div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center justify-content-center p-1 link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <!--Active User profile-->
                            <img src="Images/image-removebg-preview.png" alt="mdo" width="30" height="30" class="rounded-circle">
                            <!--Active Account User name-->
                            <h6 id="Username" style="color: #ecdb9c;" class="m-0">
                                <?php
                                echo $Current_UserData['FirstName'] . " " . $Current_UserData['MiddleName'] . " " . $Current_UserData['LastName']
                                ?>
                            </h6>
                        </a>
                        <ul class="dropdown-menu text-small shadow" style="background-color: #ecdb9c;">
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="../Logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main style="height: 90%;">
        <!--TODO: Dashboard should not be visible if the logged in account type is staff-->
        <!--SidebarToggle-->
        <div class="position-absolute fade  col-8 col-sm-4 h-100 collapse navbar-collapse" id="navbarNavAltMarkup" style="background-color: #7f6a6c; z-index: 2;">
            <div class="navbar-nav">
                <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
                    <li class="nav-item mt-2 w-auto h-auto">
                        <?php
                        if ($AT == "Admin") {
                        ?>
                            <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                                <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Dashboard_Page.php">
                                    <div class="col-12 d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-speedometer" viewBox="0 0 16 16">
                                            <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z" />
                                            <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z" />
                                        </svg>
                                        <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Dashboard</h6>
                                    </div>
                                </a>
                                <a class="ps-0 col-2 p-0 btn bi bi-caret-down-fill" data-bs-toggle="collapse" href="#Dashboarddrop" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ecdb9c" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                    </svg>
                                </a>
                            </div>
                        <?php } ?>
                        <!--Dropdown menus for dashboard-->
                        <div id="Dashboarddrop" class="collapse col-8 float-end">
                            <div class="row gy-2 me-0 pe-1">
                                <a href="Management_Page.php" style="text-decoration: none;">
                                    <div class="FCh d-flex justify-content-end rounded-2">
                                        <span class="text-ends m-2" style="color: #ecdb9c;">Management</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mt-3 w-auto h-auto">
                        <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                            <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Menu_Page.php">
                                <div class="col-12 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-house-door-fill" viewBox="0 0 16 16">
                                        <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z" />
                                    </svg>
                                    <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Menu</h6>
                                </div>
                            </a>
                        </div>
                    </li>
                    <li class="nav-item mt-3 w-auto h-auto">
                        <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                            <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Order_Page.php">
                                <div class="col-12 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-layout-text-sidebar-reverse" viewBox="0 0 16 16">
                                        <path d="M12.5 3a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5zm0 3a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5zm.5 3.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5zm-.5 2.5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5z" />
                                        <path d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2zM4 1v14H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h2zm1 0h9a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5V1z" />
                                    </svg>
                                    <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Orders</h6>
                                </div>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="w-100 h-100 d-flex">
            <!--Side bar-->
            <div class="h-100  float-start d-none d-md-inline-block w-auto" style="background-color: #7f6a6c !important;">
                <div class="d-flex flex-column flex-shrink-0 bg-light mt-3" style="width: 220px; height: 90vh; background-color: #7f6a6c !important;">
                    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
                        <li class="nav-item mt-2 w-auto h-auto">
                            <?php
                            if ($AT == "Admin") {
                            ?>
                                <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                                    <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Dashboard_Page.php">
                                        <div class="col-12 d-flex align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-speedometer" viewBox="0 0 16 16">
                                                <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z" />
                                                <path fill-rule="evenodd" d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z" />
                                            </svg>
                                            <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Dashboard</h6>
                                        </div>
                                    </a>
                                    <a class="ps-0 col-2 p-0 btn bi bi-caret-down-fill" data-bs-toggle="collapse" href="#Dashboarddrop" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#ecdb9c" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z" />
                                        </svg>
                                    </a>
                                </div>
                            <?php } ?>
                            <!--Dropdown menus for dashboard-->
                            <div id="Dashboarddrop" class="collapse col-8 float-end">
                                <div class="row gy-2 me-0 pe-1">
                                    <a href="Management_Page.php" style="text-decoration: none;">
                                        <div class="FCh d-flex justify-content-end rounded-2">
                                            <span class="text-ends m-2" style="color: #ecdb9c;">Management</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item mt-3 w-auto h-auto">
                            <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                                <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Menu_Page.php">
                                    <div class="col-12 d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-house-door-fill" viewBox="0 0 16 16">
                                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z" />
                                        </svg>
                                        <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Menu</h6>
                                    </div>
                                </a>
                            </div>
                        </li>
                        <li class="nav-item mt-3 w-auto h-auto">
                            <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                                <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Order_Page.php">
                                    <div class="col-12 d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#ecdb9c" class="col-4 p-2 bi bi-layout-text-sidebar-reverse" viewBox="0 0 16 16">
                                            <path d="M12.5 3a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5zm0 3a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5zm.5 3.5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 .5-.5zm-.5 2.5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1h5z" />
                                            <path d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2zM4 1v14H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h2zm1 0h9a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5V1z" />
                                        </svg>
                                        <h6 class="text-center m-0 col-8" style="color: #ecdb9c;">Orders</h6>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!--modal-->
            <?php
            //Gets all orders and create own callable modals
            $queryAOrder = "SELECT * FROM (SELECT orders.OrderID, orders.AccountID, orders.OrderPrice, accounts.FirstName, accounts.LastName, accounts.Account_Type, accounts.CourseOrPosition FROM orders INNER JOIN accounts ON orders.AccountID = accounts.AccountID)tb ";
            $ResultAO = mysqli_query($con, $queryAOrder);
            while ($AOrow = mysqli_fetch_assoc($ResultAO)) {
            ?>
                <div class="modal fade" id="ID<?php echo $AOrow['AccountID'] . $AOrow['OrderID'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog-centered modal-dialog">
                        <div class="modal-content" style="background-color:#ecdb9c">
                            <div class="modal-body" style="height: 450px;">
                                <div class="col-12 d-flex" style="height: 35%;">
                                    <div class="col-4 h-1090">
                                        <img src="" alt="ProfilePicute " class="w-100 h-100">
                                    </div>
                                    <div class="col-8 p-2">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="button" class=" btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="col-12">
                                            <!--Datas from Orderdb here-->
                                            <h6 id="UID"><?php echo $AOrow['FirstName'] . " " . $AOrow['LastName'] ?></h6>
                                            <h6 id="USAY"><?php echo $AOrow['CourseOrPosition'] ?></h6>
                                            <h6 id="OID">Order #: <?php echo $AOrow['OrderID'] ?></h6>
                                        </div>

                                    </div>
                                </div>
                                <div class="overflow-auto d-flex  flex-wrap col-12 p-2" style="height: 55%;">
                                    <?php
                                    //Gets Specific Account food orders
                                    $CID = $AOrow['AccountID'];
                                    $COID = $AOrow['OrderID'];
                                    $queryFoodOrders = "SELECT * FROM (SELECT foodcart.FoodCartID, foodcart.FoodID, foodcart.OrderID, foodcart.Quantity, food.FoodName, food.FoodPrice, foodcart.AccountID FROM foodcart INNER JOIN food ON foodcart.FoodID = food.FoodID )tb
                                    WHERE AccountID = $CID AND OrderID = $COID";
                                    $query = "SELECT orders.Status FROM orders WHERE AccountID = $CID";
                                    $resultS = mysqli_query($con, $query);
                                    $row = mysqli_fetch_assoc($resultS);
                                    $resultFO = mysqli_query($con, $queryFoodOrders);
                                    while ($Foodrow = mysqli_fetch_assoc($resultFO)) {
                                    ?>
                                        <div class="col-12 col-sm-6 p-3 d-flex border-3" style="height: 100px; ">
                                            <div class="col-4 bg-danger" style="border-radius: 10px 0px 0px 10px;">
                                                <img src="Images/Samples/food-ga5066c530_1920.jpg" style="border-radius: 10px 0px 0px 10px;" class="h-100 w-100" alt="">
                                            </div>
                                            <div class="col-8 p-2" style=" border-radius: 0px 10px 10px 0px; background-color:#efd581;">
                                                <h6><?php echo $Foodrow['FoodName'] ?></h6>
                                                <div class="col-12 d-flex justify-content-evenly">
                                                    <h6>P<?php echo $Foodrow['FoodPrice'] ?></h6>
                                                    <h6>Q: <?php echo $Foodrow['Quantity'] ?></h6>
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>

                                </div>
                                <div class="col-12 d-flex flex-wrap align-items-center">
                                    <div class="col-6">
                                        <span class="fs-6 fw-bold">Total price: P<?php echo $AOrow['OrderPrice'] ?></span>
                                    </div>
                                    <div class="col-6 d-flex flex-wrap justify-content-evenly">
                                        <?php
                                        if ($row['Status'] == "Preparing") {
                                        ?>
                                            <button class="btn m-1 rounded-5" style="background-color: #d1a561;" onclick="Cprep(this)">
                                                <p class="d-none">P<?php echo $AOrow['OrderID'] . $AOrow['AccountID'] ?></p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" class=" d-block d-sm-none bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                                                </svg>
                                                <span class="d-none d-sm-block">Prepared</span>
                                            </button>
                                        <?php
                                        } elseif ($row['Status'] == "Prepared") {
                                        ?>
                                            <button class="btn m-1 rounded-5" style="background-color: #d1a561;" onclick="Cdone(this)">
                                                <p class="d-none">S<?php echo $AOrow['OrderID'] . $AOrow['AccountID'] ?></p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" class=" d-block d-sm-none bi bi-check-circle" viewBox="0 0 16 16">
                                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                                                </svg>
                                                <span class="d-none d-sm-block">Recieved</span>
                                            </button>
                                        <?php
                                        }
                                        ?>
                                        <button class="btn m-1 rounded-5" style="background-color: #d1a561;" onclick="CCancel(this)">
                                            <p class="d-none">Sc<?php echo $AOrow['OrderID'] . $AOrow['AccountID'] ?></p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="d-block d-sm-none bi bi-x-circle" viewBox="0 0 16 16">
                                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                                            </svg>
                                            <span class="d-none d-sm-block">Cancel</span>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="d-flex flex-wrap w-100 h-100" style="overflow-y: auto;">
                <div class="col-lg-6 col-12 p-2 d-flex flex-wrap h-100">
                    <div class="col-sm-4 col-4 d-flex align-items-end">
                        <h6 class="col-12 text-start" style="color: #7f6a6c;">Priority List</h6>
                    </div>
                    <!--Alternative for table order-->
                    <div class="col-12 d-flex flex-wrap overflow-auto rounded-4 p-3 pt-4 " style=" height: 85%;  background: rgba(236, 219, 156, 0.5);">
                        <!--Table for Priority List-->
                        <div class="col-12 h-75 d-flex flex-wrap align-content-start">
                            <span class="col-4 fs-5 fw-bold">Order #</span>
                            <span class="col-4 fs-5 fw-bold">Name</span>
                            <!--Datas for order list-->
                            <script type="text/javascript">
                                function PrioList() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('PrioListtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/PrioListFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    PrioList();
                                }, 1000);
                            </script>
                            <div id="PrioListtb" class="col-12 overflow-auto" style="height: 92%; overflow-x: hidden !important;">

                            </div>
                        </div>
                        <div class="col-12 h-25 d-flex flex-wrap justify-content-evenly align-content-center">
                            <a class="btn p-0 col-4 d-flex flex-wrap justify-content-center align-content-center" data-bs-toggle="modal" href="#PCOrder_History">
                                <span class="fs-6 text-end col-8" style="color: #7f6a6c;">Total of Cancelled order</span>
                                <span class="col-8 rounded-3 text-center" style="background-color: #d1a561;"><?php echo $NumberPCancel ?></span>
                            </a>
                            <a class="btn p-0 col-4 d-flex flex-wrap justify-content-center align-content-center" data-bs-toggle="modal" href="#PDOrder_History">
                                <span class="fs-6 text-start col-8" style="color: #7f6a6c;">Total of <br>Done order</span>
                                <span class="col-8 rounded-3 text-center" style="background-color: #d1a561;"><?php echo $NumberPDone ?></span>
                            </a>
                            <div class="col-4 d-flex flex-wrap justify-content-center align-content-center">
                                <span class="fs-6 text-center col-12" style="color: #7f6a6c;">Total Orders: <?php echo $NumberPCancel + $NumberPDone ?></span>
                            </div>
                        </div>
                    </div>

                </div>
                <!--Customer orderlist-->
                <div class="col-lg-6 col-12 p-2 d-flex flex-wrap h-100 ">
                    <div class="col-sm-4 col-6 d-flex align-items-end">
                        <h6 class="col-12 text-start" style="color: #7f6a6c;">Customer List</h6>
                    </div>
                    <div class="col-12 d-flex flex-wrap overflow-auto rounded-4 p-3 pt-4" style="background: rgba(236, 219, 156, 0.5); height: 85%;">
                        <!--Table for Priority List-->
                        <div class="col-12 h-75 d-flex flex-wrap align-content-start ">
                            <span class="col-4 fs-5 fw-bold">Order #</span>
                            <span class="col-4 fs-5 fw-bold">Name</span>
                            <!--Datas for order list-->
                            <script type="text/javascript">
                                function CustList() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('CustListtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/CustListFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    CustList();
                                }, 2000);
                            </script>
                            <div id="CustListtb" class="col-12 overflow-auto" style="height: 92%; overflow-x: hidden !important;">

                            </div>
                            <div class="col-12 h-25 d-flex flex-wrap justify-content-evenly align-content-center">
                                <a class="btn p-0 col-4 d-flex flex-wrap justify-content-center align-content-center" data-bs-toggle="modal" href="#COrder_History">
                                    <span class="fs-6 text-end col-8" style="color: #7f6a6c;">Total of Cancelled order</span>
                                    <span class="col-8 rounded-3 text-center" style="background-color: #d1a561;"><?php echo $NumberCancel ?></span>
                                </a>
                                <a class="btn p-0 col-4 d-flex flex-wrap justify-content-center align-content-center" data-bs-toggle="modal" href="#DOrder_History">
                                    <span class="fs-6 text-start col-8" style="color: #7f6a6c;">Total of <br>Done order</span>
                                    <span class="col-8 rounded-3 text-center" style="background-color: #d1a561;"><?php echo $NumberDone ?></span>
                                </a>
                                <div class="col-4 d-flex flex-wrap justify-content-center align-content-center">
                                    <span class="fs-6 text-center col-12" style="color: #7f6a6c;">Total Orders: <?php echo $NumberCancel + $NumberDone ?></span>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        </a>
        <!--Total done and cancelled orders-->
        <div class="modal fade" id="DOrder_History" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #ecdb9c;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Recieved Orders</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #d1a561;"></button>
                    </div>
                    <script type="text/javascript">
                        function DoneList() {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
                                document.getElementById('DoneListtb').innerHTML = this.responseText;
                            }
                            xhttp.open("GET", "Functions/DoneListFunc.php");
                            xhttp.send();
                        }
                        setInterval(function() {
                            DoneList();
                        }, 1000);
                    </script>
                    <div id="DoneListtb" class="modal-body overflow-auto p-2 d-flex flex-wrap align-content-start mb-3" style="height: 300px;">

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="COrder_History" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #ecdb9c;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Cancelled Orders</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #d1a561;"></button>
                    </div>
                    <script type="text/javascript">
                        function CancelList() {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
                                document.getElementById('CancelListtb').innerHTML = this.responseText;
                            }
                            xhttp.open("GET", "Functions/CancelListFunc.php");
                            xhttp.send();
                        }
                        setInterval(function() {
                            CancelList();
                        }, 1000);
                    </script>
                    <div id="CancelListtb" class="modal-body overflow-auto p-2 d-flex flex-wrap align-content-start mb-3" style="height: 300px;">

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="PDOrder_History" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #ecdb9c;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Recieved Orders</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #d1a561;"></button>
                    </div>
                    <script type="text/javascript">
                        function PDoneList() {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
                                document.getElementById('PDoneListtb').innerHTML = this.responseText;
                            }
                            xhttp.open("GET", "Functions/PDoneListFunc.php");
                            xhttp.send();
                        }
                        setInterval(function() {
                            PDoneList();
                        }, 1000);
                    </script>
                    <div id="PDoneListtb" class="modal-body overflow-auto p-2 d-flex flex-wrap align-content-start mb-3" style="height: 300px;">

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="PCOrder_History" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #ecdb9c;">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Cancelled Orders</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #d1a561;"></button>
                    </div>
                    <script type="text/javascript">
                        function PCancelList() {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
                                document.getElementById('PCancelListtb').innerHTML = this.responseText;
                            }
                            xhttp.open("GET", "Functions/PCancelListFunc.php");
                            xhttp.send();
                        }
                        setInterval(function() {
                            PCancelList();
                        }, 1000);
                    </script>
                    <div id="PCancelListtb" class="modal-body overflow-auto p-2 d-flex flex-wrap align-content-start mb-3" style="height: 300px;">

                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="Alertmsgmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body d-flex flex-wrap">
                        <div class="col-12 d-flex justify-content-between">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Cancellation</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST">
                            <div class="col-12">
                                <div class="col-12 d-flex flex-wrap">
                                    <h6 class="col-12">Reason: </h6>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" onclick="radio(this)" name="flexRadioDefault" type="radio" id="flexRadioDefault1" value="Out of Stock : ">
                                        <label class="form-check-label" for="flexRadioDefault1">
                                            Out of Stock
                                        </label>
                                    </div>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" onclick="radio(this)" name="flexRadioDefault" type="radio" id="flexRadioDefault2" value="Safety Procedure : ">
                                        <label class="form-check-label" for="flexRadioDefault2">
                                            Safety Procedure
                                        </label>
                                    </div>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" onclick="radio(this)" name="flexRadioDefault" type="radio" id="flexRadioDefault3" value="Canteen Closed : ">
                                        <label class="form-check-label" for="flexRadioDefault3">
                                            Canteen Closed
                                        </label>
                                    </div>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" onclick="radio(this)" name="flexRadioDefault" type="radio" id="flexRadioDefault4" value="Account Restrictions : ">
                                        <label class="form-check-label" for="flexRadioDefault4">
                                            Account Restrictions
                                        </label>
                                    </div>
                                    <div class="form-check col-6">
                                        <input class="form-check-input" onclick="radio(this)" name="flexRadioDefault" type="radio" id="flexRadioDefault5" value="Others : " checked>
                                        <label class="form-check-label" for="flexRadioDefault5">
                                            Others
                                        </label>
                                    </div>
                                </div>
                                <label for="AiMsg">Cancellation Message</label>
                                <div class="input-group col-12 d-flex flex-row was-validated">
                                    <span class="input-group-text col-4" id="Armsg" aria-describedby="inputGroupPrepend3">Others : </span>
                                    <input class="form-control" type="text" id="AiMsg" required>
                                    <div class="invalid-feedback">
                                        Input Cancellation reason
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 p-2 d-flex justify-content-end">
                                <button type="button" name="Cancelmsg" class="btn btn-danger" onclick="Cancbtn(this)">Cancel Order</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        function xhttpload() {
            PrioList();
            CustList();
            DoneList();
            CancelList();
            PDoneList();
            PCancelList();
        }
        var CurrentID = null;

        function getid(data) {
            CurrentID = data.previousElementSibling.id;
            console.log(CurrentID);
        }

        function radio(data) {
            document.getElementById('Armsg').innerHTML = data.value;
        }

        function Cancbtn(data) {
            //var msg = do
            document.getElementById(CurrentID).value = document.getElementById('Armsg').innerHTML + document.getElementById('AiMsg').value;
            console.log(document.getElementById('Armsg').innerHTML + document.getElementById('AiMsg').value);
            document.getElementById(CurrentID.replace("In", "Sc")).click();            
        }

        function Cprep(data) {
            document.getElementById(data.children[0].innerHTML).click();
        }

        function Cdone(data) {
            document.getElementById(data.children[0].innerHTML).click();
        }

        function CCancel(data) {
            document.getElementById(data.children[0].innerHTML).click();
        }
    </script>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <!--Customized functions for tabs-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>