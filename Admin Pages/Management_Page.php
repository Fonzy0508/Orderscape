<?php
session_start();
include("../Connections.php");
include("../Functions.php");
$AT = $_SESSION['A_Type'];
$AID = $_SESSION['UserID'];
$Current_UserData = CheckLogin($con);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CAccID = $_POST['Acc_ID'];
    $AFN = $_POST['First_Name'];
    $AMN = $_POST['Middle_Name'];
    $ALN = $_POST['Last_Name'];
    $APN = $_POST['PhoneNum'];
    $AT = $_POST['A_Type'];
    $AEM = $_POST['Email'];
    $COP = $_POST['COP'];
    $AP = $_POST['Password'];
    $query = "SELECT AccountID FROM accounts WHERE Account_Type = '$AT' ORDER BY AccountID DESC LIMIT 1";
    $res = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($res);
    $NAccID = $row['AccountID'];
    if (isset($_POST['Addbtn'])) {
        $NAccID += 1;
        $query = "INSERT INTO accounts (AccountID, Account_Type, Email, accounts.Password, FirstName, MiddleName, LastName, PhoneNumber, CourseOrPosition) 
        VALUES ($NAccID, '$AT', '$AEM', '$AP', '$AFN', '$AMN', '$ALN', $APN, '$COP')";
        mysqli_query($con, $query);
    } elseif (isset($_POST['Deletebtn'])) {
        $query = "DELETE FROM accounts WHERE AccountID = $CAccID";
        mysqli_query($con, $query);
    } elseif (isset($_POST['Updatebtn'])) {
        $query = "UPDATE accounts SET Account_Type = '$AT', Email = '$AEM', FirstName = '$AFN', MiddleName = '$AMN', LastName = '$ALN', PhoneNumber = '$APN', CourseOrPosition = '$COP' 
        WHERE AccountID = $CAccID";
        mysqli_query($con, $query);
    } elseif (isset($_POST['ResetPWD'])) {
        $query = "UPDATE accounts SET accounts.Password = '$ALN' WHERE AccountID = $CAccID";
        mysqli_query($con, $query);
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <style>
        .Sidezy {
            border-right: 5px solid transparent !important;
        }

        .NBH:hover {
            background-color: #7f6a6c;
        }

        .zy {
            height: 90% !important;
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

        .NOH:hover {
            background-color: #7f6a6c !important;
        }

        .FCh:hover {
            background-color: #d1a561 !important;
        }

        .SBactive:active {
            background-color: #d1a561;
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

        @media only screen and (max-width: 763px) {
            .ratingdiv {
                height: 100% !important;
            }
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

<body class="h-100 overflow-hidden" onload="StaffL()">
    <header>
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
                            <h6 id="Username" style="color: #ecdb9c;" class="m-0">Username here</h6>
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
        <div class="position-absolute fade h-100 w-50 collapse navbar-collapse" id="navbarNavAltMarkup" style="background-color: #7f6a6c; z-index: 2;">
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
                                <div class="FCh d-flex justify-content-end rounded-2">
                                    <span class="text-ends m-2" style="color: #ecdb9c;">Management</span>
                                </div>                                
                            </div>
                        </div>
                    </li>
                    <li class="nav-item mt-3 w-auto h-auto">
                        <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                            <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Menu_Page.html">
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
                            <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Order_Page.html">
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
        <div class="d-block w-100 h-100 d-flex justify-content-evenly">
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
            <!--Main contents-->
            <div class="h-100 float-none float-md-start p-0 m-auto ms-md-0 container-fluid d-flex flex-wrap scrollautohide" style="overflow-x: hidden !important;">
                <!--Add Account modal-->
                <div class="modal modal-fullscreen-md-down modal-lg fade" id="AddAccmodal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Account Informations</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class=" container-fluid d-flex">
                                    <form class="d-flex flex-wrap" id="Foodform" method="POST">
                                        <div class="container row g-3 was-validated">
                                            <input class="d-none" id="AccID" name="Acc_ID">
                                            <div class="col-12 d-flex flex-wrap">
                                                <div class="col-12 col-md-3 p-1">
                                                    <label for="FirstName" class="form-label">First Name</label>
                                                    <input type="text" class="form-control is-valid" id="FirstName" name="First_Name" required>
                                                    <div class="invalid-feedback">
                                                        Input First Name
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3 p-1">
                                                    <label for="MiddleName" class="form-label">Middle Name</label>
                                                    <input type="text" class="form-control is-valid" id="MiddleName" name="Middle_Name" required>
                                                    <div class="invalid-feedback">
                                                        Input Middle Name
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3 p-1">
                                                    <label for="LastName" class="form-label">Last Name</label>
                                                    <input type="text" class="form-control is-valid" id="LastName" name="Last_Name" required>
                                                    <div class="invalid-feedback">
                                                        Input Last Name
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-3 p-1">
                                                    <label for="SelectC" class="form-label">Account Type</label>
                                                    <select id="SelectC" class="form-select" name="A_Type" required aria-label="select example">
                                                        <option value="">Select Account Type</option>
                                                        <option value="Admin">Admin</option>
                                                        <option value="Staff">Staff</option>
                                                    </select>
                                                    <div class="invalid-feedback">Select Account Type</div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex flex-wrap">
                                                <div class="col-12 col-md-8 p-1 ">
                                                    <label for="" class="form-label">Email</label>
                                                    <div class="input-group col-12 d-flex flex-row">
                                                        <span class="input-group-text" id="inputGroupPrepend3">@</span>
                                                        <span class="input-group-text col" id="Email" aria-describedby="inputGroupPrepend3">srthrsrvtwrtsastcbtrwet</span>
                                                        <input type="text" name="Email" id="Emaili" class="col form-control is-valid" aria-describedby="inputGroupPrepend3" required>
                                                        <div id="emailid" class="invalid-feedback">
                                                            Input Email
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 p-1">
                                                    <label for="" class="form-label">Password</label>
                                                    <input id="pwi" type="password" class="form-control is-valid" name="Password" required>
                                                    <input type="submit" id="pwb" name="ResetPWD" class="col-12 btn bg-danger" value="Reset">
                                                    <div id="pwd" class="invalid-feedback">
                                                        Input Password
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-wrap">
                                                <div class="col-12 col-md-6 p-1">
                                                    <label for="PhoneNum" class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control is-valid" id="PhoneNum" name="PhoneNum" required>
                                                    <div class="invalid-feedback">
                                                        Input Phone Number
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 p-1">
                                                    <label for="COP" class="form-label">User Job Position</label>
                                                    <input type="text" class="form-control is-valid" id="COP" name="COP">
                                                    <div class="invalid-feedback">
                                                        Input Position
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 w-100 d-flex" style="height: 50px;">
                                                <div class="col p-1">
                                                    <input id="AddAccform" class="btn btn-primary w-100" type="submit" name="Addbtn" value="Add Food">
                                                    <input id="UpdateAccform" class="btn btn-primary w-100" type="submit" name="Updatebtn" value="Update Food">
                                                </div>
                                                <div class="col p-1">
                                                    <input id="DeleteAccForm" class="btn btn-danger w-100" type="submit" name="Deletebtn" value="Delete">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!--<div class="modal-footer">
                                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
                            </div>-->
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="col-12 h-100 p-2 pt-4">
                        <h4 class="col-12 pb-4" style="color: #7f6a6c;">Staff Management</h4>
                        <div class="col-12 d-flex justify-content-center">
                            <div data-bs-toggle="modal" data-bs-target="#AddAccmodal" onclick="GetData(this)" id="AddNAccbtn" class="col-12 col-sm-6 btn text-center p-1 rounded-4 fw-bold" style="background-color:#7f6a6c; color: white;">Add</div>
                        </div>
                        <!--Staff db-->
                        <div class="col-12 pt-2 mt-2 overflow-hidden d-flex flex-wrap align-content-start" style="height: 440px; color: #7f6a6c;">
                            <div class="col-6 text-center">
                                <h5>Name</h5>
                            </div>
                            <div class="col-6 text-center">
                                <h5>Position</h5>
                            </div>
                            <script type="text/javascript">
                                function StaffL() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('StaffLtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/StaffLFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    StaffL();
                                }, 1000);
                            </script>
                            <div id="StaffLtb" class="overflow-auto d-flex flex-wrap align-content-start h-100 col-12">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </main>
    <script type="text/javascript">
        function GetData(div) {
            if (div.id == document.getElementById('AddNAccbtn').id) {
                document.getElementById('FirstName').value = "";
                document.getElementById('MiddleName').value = "";
                document.getElementById('LastName').value = "";
                document.getElementById('SelectC').value = "";
                document.getElementById('PhoneNum').value = "";
                document.getElementById('AccID').value = "";
                document.getElementById('Emaili').value = "";
                document.getElementById('pwi').value = "";
                document.getElementById('COP').value = "";
                document.getElementById('Email').style.display = "none";
                document.getElementById('Emaili').style.display = "block";
                document.getElementById('emailid').setAttribute('class', 'invalid-feedback');
                document.getElementById('pwb').style.display = "none";
                document.getElementById('pwi').style.display = "block";
                document.getElementById('pwd').setAttribute('class', 'invalid-feedback');
                document.getElementById('AddAccform').style.display = "block";
                document.getElementById('UpdateAccform').style.display = "none";
                document.getElementById('DeleteAccForm').style.display = "none";
            } else {
                document.getElementById('UpdateAccform').style.display = "block";
                document.getElementById('AddAccform').style.display = "none";
                document.getElementById('DeleteAccForm').style.display = "block";
                document.getElementById('Email').style.display = "block";
                document.getElementById('emailid').setAttribute('class', 'invalid-feedback d-none');
                document.getElementById('pwb').style.display = "block";
                document.getElementById('pwi').style.display = "none";
                document.getElementById('pwd').setAttribute('class', 'invalid-feedback d-none');
                document.getElementById('emailid').style.display = "none";
                document.getElementById('FirstName').value = div.children[0].children[2].innerHTML;
                document.getElementById('COP').value = div.children[0].children[6].innerHTML;
                document.getElementById('pwi').value = " ";
                document.getElementById('MiddleName').value = div.children[0].children[3].innerHTML;
                document.getElementById('LastName').value = div.children[0].children[4].innerHTML;
                document.getElementById('SelectC').value = div.children[0].children[1].innerHTML;
                document.getElementById('PhoneNum').value = div.children[0].children[5].innerHTML;
                document.getElementById('AccID').value = div.children[0].children[0].innerHTML;
                document.getElementById('Email').innerHTML = div.children[0].children[7].innerHTML;
                document.getElementById('Emaili').value = div.children[0].children[7].innerHTML;
            }
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