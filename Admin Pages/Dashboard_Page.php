<?php

use LDAP\Result;

session_start();
include("../Connections.php");
include("../Functions.php");
$AID = $_SESSION['UserID'];
$Current_UserData = CheckLogin($con);
$query = "select * from food";
$queryD = "select * from food where Category = 'Dish'";
$queryDr = "select * from food where Category = 'Drink'";
$queryC = "select * from food where Category = 'Dessert'";
$queryS = "select * from food where Category = 'Snack'";
$result = mysqli_query($con, $query);
$resultD = mysqli_query($con, $queryD);
$resultDr = mysqli_query($con, $queryDr);
$resultC = mysqli_query($con, $queryC);
$resultS = mysqli_query($con, $queryS);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["GetFoodData"])) {
        $FoodID = $_POST["FIDs"];
        $Q = $_POST["Quantity"];
        $query = "INSERT INTO foodcart(AccountID,FoodID,Quantity) VALUES ($AID,$FoodID,$Q)";
        mysqli_query($con, $query);
    } elseif (isset($_POST['FC_Delete'])) {
        $FCFID = $_POST['FFood_ID'];
        $fquery = "DELETE FROM foodcart WHERE FoodCartID = $FCFID AND AccountID = $AID";
        mysqli_query($con, $fquery);
    } elseif (isset($_POST['OrderBtn'])) {
        $FQC = floatval($_POST["FQuantity"]);
        $queryO = "INSERT INTO orders(AccountID, OrderPrice) VALUES((SELECT AccountID FROM accounts WHERE AccountID = $AID),$FQC)";
        $queryFCO = "UPDATE foodcart SET OrderID = (SELECT OrderID FROM orders WHERE AccountID = $AID ORDER BY OrderID DESC LIMIT 1) WHERE AccountID = $AID AND OrderID IS NULL";
        mysqli_query($con, $queryO);
        mysqli_query($con, $queryFCO);        
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <title></title>
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

<body class="h-100 overflow-hidden" onload="Online(); xhttpload();">
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
        <div class="position-absolute fade h-100 w-50 collapse navbar-collapse" id="navbarNavAltMarkup" style="background-color: #7f6a6c; z-index: 2;">
            <div class="navbar-nav">
                <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
                    <li class="nav-item mt-2 w-auto h-auto">
                        <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                            <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu" href="Dashboard_Page.html">
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
                        <!--Dropdown menus for dashboard-->
                        <div id="Dashboarddrop" class="collapse col-8 float-end">
                            <div class="row gy-2 me-0 pe-1">
                                <a href="StaffManagement_Page.html">
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
        <div class="d-block w-100 h-100 d-flex justify-content-evenly">
            <!--Side bar-->
            <div class="h-100  float-start d-none d-md-inline-block w-auto" style="background-color: #7f6a6c !important;">
                <div class="d-flex flex-column flex-shrink-0 bg-light mt-3" style="width: 220px; height: 90vh; background-color: #7f6a6c !important;">
                    <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
                        <li class="nav-item mt-2 w-auto h-auto">
                            <div class="col-12 d-flex flex-nowrap justify-content-start align-items-center SBactive">
                                <a class=" col-8 pe-0 btn nav-link rounded-0 Sidezy d-flex flex-column" id="FC" title="Menu">
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
            <div class="h-100 float-none float-md-start p-0 m-auto ms-md-0 container-fluid d-flex flex-wrap overflow-hidden" style="overflow-x: hidden !important;">
                <!--add/update/delete Food modals-->
                <div class="modal modal-fullscreen-md-down modal-lg fade" id="AddFoodmodal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalToggleLabel">Food Informations</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!--Images add-->
                                <div class=" container-fluid d-flex ">
                                    <div class="col-4">
                                        <label class="btn btn-default btn-file w-100 h-100 bg-black">
                                            <img src="" alt=""> <input type="file" style="display: none;" required>
                                        </label>
                                    </div>
                                    <div class="col-8">
                                        <div class="container">
                                            <form class="row g-3 was-validated" id="Foodform">
                                                <div class="col-12">
                                                    <label for="FoodName" class="form-label">Food Name</label>
                                                    <input type="text" class="form-control is-valid" id="FoodName" required>
                                                    <div class="invalid-feedback">
                                                        Input Food Name
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label for="FoodPrice" class="form-label">Food Price</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend3">₱</span>
                                                        <input type="number" step="0.1" class="form-control" id="FoodPrice" aria-describedby="validationServerPriceFeedback" required>
                                                        <div id="validationServerPriceFeedback" class="invalid-feedback">
                                                            Please input Food price.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-12 col-md-6">
                                                    <label for="validationServerPrice" class="form-label">Food
                                                        Category</label>
                                                    <select class="form-select" required aria-label="select example">
                                                        <option value="">Open this select menu</option>
                                                        <option value="1">Dish</option>
                                                        <option value="2">Combos</option>
                                                        <option value="3">Drinks</option>
                                                        <option value="4">Snacks</option>
                                                        <option value="5">Desserts</option>
                                                    </select>
                                                    <div class="invalid-feedback">Select Food Category.</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="col-12 w-100 d-flex" style="height: 50px;">
                                                        <div class="col-6 p-1">
                                                            <button id="AddFoodform" class="btn btn-primary w-100" type="submit">Add Food</button>
                                                            <button id="UpdateFoodform" class="btn btn-primary w-100" type="submit">Update Food</button>
                                                        </div>
                                                        <div class="col-6 p-1">
                                                            <button id="DeletefoodForm" class="btn btn-danger w-100" type="submit">Delete</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!--<div class="modal-footer">
                                <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
                            </div>-->
                        </div>
                    </div>
                </div>
                <!--Contents-->
                <div class="col h-100">
                    <form method="POST" id="FForm" class="d-none">
                        <input id="addfood" type=" text" name="FIDs" value="">
                        <input id="QF" type=" text" name="Quantity" value="">
                        <button type="submit" name="GetFoodData" class="btn" id="Addfa">Add</button>
                    </form>
                    <button class="btn d-flex align-items-center justify-content-center p-0 rounded-5 d-lg-none position-absolute" style="z-index: 2; top: 280px; right: 10px; width: 30px; height: 30px; background-color: #efd581;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#7f6a6c" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z" />
                        </svg>
                    </button>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-fill text-dark m-2" id="myTab" role="tablist">
                        <li class="nav-item text-dark fw-bold" role="presentation">
                            <button class="nav-link text-dark active foodtabs" id="Atab" onclick="ATab()" data-bs-toggle="tab" data-bs-target="#AllFoodTab" type="button" role="tab" aria-controls="home" aria-selected="true">All</button>
                        </li>
                        <li class="nav-item text-dark fw-bold " role="presentation">
                            <button class="nav-link text-dark  foodtabs" id="Dtab" onclick="DTab()" data-bs-toggle="tab" data-bs-target="#DishTab" type="button" role="tab" aria-controls="profile" aria-selected="false">Dish</button>
                        </li>
                        <li class="nav-item text-dark fw-bold" role="presentation">
                            <button class="nav-link text-dark foodtabs" id="Ctab" onclick="CTab()" data-bs-toggle="tab" data-bs-target="#DessertTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Dessert</button>
                        </li>
                        <li class="nav-item text-dark fw-bold" role="presentation">
                            <button class="nav-link text-dark foodtabs" id="Drtab" onclick="DrTab()" data-bs-toggle="tab" data-bs-target="#DrinksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Drinks</button>
                        </li>
                        <li class="nav-item fw-bold" role="presentation">
                            <button class="nav-link text-dark foodtabs" id="Stab" onclick="STab()" data-bs-toggle="tab" data-bs-target="#SnacksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Snacks</button>
                        </li>
                    </ul>
                    <!-- Tab panes :: Food cards -->
                    <div class="tab-content justify-content-center overflow-auto ratingdiv" style="height: 65%;">
                        <!--All food List-->
                        <div class="tab-pane active" id="AllFoodTab" role="tabpanel" aria-labelledby="home-tab">
                            <script type="text/javascript">
                                function Allfood() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Allfoodtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/DAllfoodFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Allfood();
                                }, 5000);
                            </script>
                            <div id="Allfoodtb" class="container-fluid d-flex flex-wrap p-md-3 w-100 h-100 ">

                            </div>
                        </div>
                        <!--Snacks List-->
                        <div class="tab-pane" id="SnacksTab" role="tabpanel" aria-labelledby="messages-tab">
                            <script type="text/javascript">
                                function Snackfood() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Snackfoodtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/DSnackfoodFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Snackfood();
                                }, 1000);
                            </script>
                            <div id="Snackfoodtb" class="container-fluid d-flex flex-wrap p-md-3 w-100 h-100">

                            </div>
                        </div>
                        <!--Dish List-->
                        <div class="tab-pane" id="DishTab" role="tabpanel" aria-labelledby="profile-tab">
                            <script type="text/javascript">
                                function Dishfood() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Dishfoodtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/DDishfoodFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Dishfood();
                                }, 1000);
                            </script>
                            <div id="Dishfoodtb" class="container-fluid d-flex flex-wrap p-md-3 w-100 h-100">

                            </div>
                        </div>
                        <!--Dessert List-->
                        <div class="tab-pane" id="DessertTab" role="tabpanel" aria-labelledby="messages-tab">
                            <script type="text/javascript">
                                function Dessertfood() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Dessertfoodtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/DDessertfoodFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Dessertfood();
                                }, 1000);
                            </script>
                            <div id="Dessertfoodtb" class="container-fluid d-flex flex-wrap p-md-3 w-100 h-100">

                            </div>
                        </div>
                        <!--Drinks List-->
                        <div class="tab-pane" id="DrinksTab" role="tabpanel" aria-labelledby="messages-tab">
                            <script type="text/javascript">
                                function Drinkfood() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Drinkfoodtb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "Functions/DDrinkfoodFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Drinkfood();
                                }, 1000);
                            </script>
                            <div id="Drinkfoodtb" class="container-fluid d-flex flex-wrap p-md-3 w-100 h-100">

                            </div>

                        </div>
                    </div>
                    <div class="col-12 flex-wrap d-none d-md-flex" style="height: 24%;">
                        <!--RAtings-->
                        <div class="col-6 h-100 p-2">
                            <span class="fs-6 fw-bold ms-1" style="color: #7f6a6c;">Ratings</span>
                            <div class="col-12 rounded-5 mb-1" style="background: rgba(127,106,108,.5);">
                                <div class="p-1 d-flex align-items-center float-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="ms-2 me-1 bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                </div>

                                <div class="w-75 rounded-5" style="height: 24px; background: rgba(127,106,108,.5);"></div>
                            </div>
                            <div class="col-12 rounded-5 mb-1" style="background: rgba(127,106,108,.5);">
                                <div class="p-1 d-flex align-items-center float-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="ms-2 me-1 bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                </div>
                                <div class="w-50 rounded-5" style="height: 24px; background: rgba(127,106,108,.5);"></div>
                            </div>
                            <div class="col-12 rounded-5 mb-1" style="background: rgba(127,106,108,.5);">
                                <div class="p-1 d-flex align-items-center float-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="ms-2 me-1 bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                </div>

                                <div class="w-75 rounded-5" style="height: 24px; background: rgba(127,106,108,.5);"></div>
                            </div>
                            <div class="col-12 rounded-5 mb-1" style="background: rgba(127,106,108,.5);">
                                <div class="p-1 d-flex align-items-center float-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="ms-2 me-1 bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#efd581" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi me-1 bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#e6e6e6" class="bi bi-star-fill" viewBox="0 0 16 16">
                                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                    </svg>
                                </div>
                                <div class="w-25 rounded-5" style="height: 24px; background: rgba(127,106,108,.5);"></div>
                            </div>
                        </div>
                        <div class="col-6 h-100 p-2">
                            <span class="fs-6 fw-bold ms-1" style="color: #7f6a6c;">Online</span>
                            <script type="text/javascript">
                                function Online() {
                                    const xhttp = new XMLHttpRequest();
                                    xhttp.onload = function() {
                                        document.getElementById('Onlinetb').innerHTML = this.responseText;
                                    }
                                    xhttp.open("GET", "OnlineStaffFunc.php");
                                    xhttp.send();
                                }
                                setInterval(function() {
                                    Online();
                                }, 100);
                            </script>
                            <div id="Onlinetb" class="col-12">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 h-100 d-none d-lg-block p-1">
                    <div class="col-12 h-100 rounded-5 p-2 float-start d-flex flex-wrap align-content-start" style="z-index: 3;background: rgba(127,106,108,.48);">
                        <div class="col-12 d-flex justify-content-between mt-3 p-2">
                            <span class="fs-2 fw-bold" style="color: #7f6a6c;">Order List</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#7f6a6c" class="bi bi-list-ul" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm-3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm0 4a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                            </svg>
                        </div>

                        <!--Tab and contents of new orders, preparing orders and the current selected walkin orders-->
                        <div class="col-12 p-1" style="height: 85%;">
                            <!--Tabs for new orders, preparing orders and the current selected walkin orders-->
                            <div class=" col-12 d-flex justify-content-center">
                                <ul class="nav nav-pills mb-3 rounded-5" style="background: rgba(127,106,108,.5);" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-5" style="color: white !important ; " id="PT-new" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true" onclick="FTActive(this)">New</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-5" style="color: white !important ;" id="PT-Preparing" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="FTActive(this)">Prepared</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active rounded-5" style="color: white !important ;background: rgba(127,106,108) !important;" id="PT-Current" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" onclick="FTActive(this)">Current</button>
                                    </li>
                                </ul>
                            </div>
                            <!--Tab contents for new orders, preparing orders and the current selected walkin orders-->
                            <div class="tab-content text-white" id="pills-tabContent" style="height: 85%;">
                                <form method="POST" class="d-none">
                                    <input type="number" name="FFood_ID" id="FC_Food_ID">
                                    <input type="submit" name="FC_Delete" id="FC_del">
                                </form>
                                <script type="text/javascript">
                                    function Preparingfood() {
                                        const xhttp = new XMLHttpRequest();
                                        xhttp.onload = function() {
                                            document.getElementById('pills-home').innerHTML = this.responseText;
                                            document.getElementById('pills-home1').innerHTML = this.responseText;
                                        }
                                        xhttp.open("GET", "Functions/PreparingFunc.php");
                                        xhttp.send();
                                    }
                                    setInterval(function() {
                                        Preparingfood();
                                    }, 1000);
                                </script>
                                <div class="tab-pane overflow-auto fade p-1 h-100 overflow-auto" id="pills-home" role="tabpanel" aria-labelledby="PT-new" tabindex="0">
                                    <!--Datas for new orders are placed here-->

                                </div>
                                <script type="text/javascript">
                                    function Preparedfood() {
                                        const xhttp = new XMLHttpRequest();
                                        xhttp.onload = function() {
                                            document.getElementById('pills-profile').innerHTML = this.responseText;
                                            document.getElementById('pills-profile1').innerHTML = this.responseText;
                                        }
                                        xhttp.open("GET", "Functions/PreparedFunc.php");
                                        xhttp.send();
                                    }
                                    setInterval(function() {
                                        Preparedfood();
                                    }, 1000);
                                </script>
                                <div class="tab-pane overflow-auto h-100 fade p-1" id="pills-profile" role="tabpanel" aria-labelledby="PT-Preparing" tabindex="0">
                                    <!--Datas for preparing orders are placed here-->

                                </div>

                                <div class="tab-pane  show active fade p-1 h-100 d-flex flex-wrap" id="pills-contact" role="tabpanel" aria-labelledby="PT-Current" tabindex="0">
                                    <!--Datas for Current orders are placed here-->
                                    <div class="overflow-auto d-flex align-items-start flex-wrap" style="height: 80%;">
                                        <?php
                                        $AFquery = "SELECT * FROM (SELECT foodcart.FoodCartID, foodcart.OrderID, foodcart.FoodID, foodcart.Quantity, food.FoodName, food.FoodPrice, foodcart.AccountID FROM foodcart INNER JOIN food ON foodcart.FoodID = food.FoodID )tb
                                        WHERE AccountID = $AID  AND OrderID IS NULL";
                                        $AFresult = mysqli_query($con, $AFquery);
                                        $OAPrice = 0;
                                        while ($WIFoodData = mysqli_fetch_assoc($AFresult)) {
                                            $OAPrice += ($WIFoodData["FoodPrice"] * $WIFoodData['Quantity']);
                                        ?>
                                            <div class="card FCh text-start d-inline-block mb-1" style="border: none; border-radius: 10px; background-color: transparent;">
                                                <img class="col-4 float-start" height="100px" src="" alt="FoodIMG" style="border-radius: 10px 0px 0px 10px;">
                                                <div class="card-body col-8 h-100 d-block float-start" style="border-radius: 0px 10px 10px 0px;">
                                                    <div class="w-100 d-block position-relative" style="height: 25px;">
                                                        <span class="btn btn-close position-absolute" onclick="DeleteData(this)" style="top: 20px; right:5px;"></span>
                                                        <h6 class="d-none"><?php echo $WIFoodData['FoodCartID'] ?></h6>
                                                        <h6 class="card-title float-start m-0"><?php echo $WIFoodData['FoodName'] ?></h6>
                                                        <p class="card-text mb-0 float-end">P<?php echo $WIFoodData['FoodPrice'] ?></p>
                                                    </div>
                                                    <!--Quantity-->
                                                    <div class="col-6 d-flex justify-content-evenly rounded-2 h-auto" style=" background: rgba(209,164,97,.8) !important;">
                                                        <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="add(this)">+</button>
                                                        <input class="col-6 d-flex text-center rounded-2 p-1" type="number" value="<?php echo $WIFoodData['Quantity'] ?>" style="background-color: transparent; border: 0px solid !important;">
                                                        <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="minus(this)">-</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="d-flex flex-row align-content-end container flex-wrap">
                                        <hr class="col-12 w-75 m-auto mt-1">
                                        <span class="col-6 justify-content-start d-inline-flex">
                                            <h6>Total: </h6>
                                        </span>
                                        <span class="col-6 justify-content-end float-end d-inline-flex">
                                            <h6 class="">P</h6>
                                            <!--Total price placed here-->
                                            <h6 id="TotalPrice"><?php echo $OAPrice ?></h6>
                                        </span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end p-2">
                                        <form method="POST" class="col-12 d-flex justify-content-end">
                                            <input type="number" class="d-none" step="0.1" name="FQuantity" value="<?php echo $OAPrice ?>">
                                            <?php
                                            $query = "SELECT * FROM foodcart WHERE OrderID IS NULL AND AccountID = $AID LIMIT 1";
                                            $result = mysqli_query($con, $query);
                                            $order = mysqli_fetch_assoc($result);
                                            if (isset($order)) {
                                            ?>
                                                <input class="btn col-sm-6 col-12 fw-bold rounded-4" style="background-color: #d1a561;" type="submit" name="OrderBtn" value="Order Now">
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 h-100 bg-white rounded-5" style="z-index: 2;"></div>
                </div>
                <div class="col-sm-6 col-10 p-2 d-lg-none position-absolute collapse collapse-horizontal zy" style=" z-index: 3; right: 0px;" id="collapseWidthExample">
                    <div class="col-12 h-100 rounded-5 p-2 float-start d-flex flex-wrap align-content-start" style="z-index: 3;background: rgba(127,106,108,.48);">
                        <div class="col-12 d-flex justify-content-between mt-3 p-2">
                            <span class="fs-2 fw-bold" style="color: #7f6a6c;">Order List</span>
                            <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-label="Close"></button>
                        </div>

                        <!--Tab and contents of new orders, preparing orders and the current selected walkin orders-->
                        <div class="col-12 p-1 h-100">
                            <!--Tabs for new orders, preparing orders and the current selected walkin orders-->
                            <div class=" col-12 d-flex justify-content-center">
                                <ul class="nav nav-pills mb-3 rounded-5" style="background: rgba(127,106,108,.5);" id="pills-tab1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-5" style="color: white !important ; " id="PT-new1" data-bs-toggle="pill" data-bs-target="#pills-home1" type="button" role="tab" aria-controls="pills-home" aria-selected="true" onclick="FTActive(this)">New</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-5" style="color: white !important ;" id="PT-Preparing1" data-bs-toggle="pill" data-bs-target="#pills-profile1" type="button" role="tab" aria-controls="pills-profile" aria-selected="false" onclick="FTActive(this)">Prepared</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active rounded-5" style="color: white !important ; background: rgba(127,106,108) !important;" id="PT-Current1" data-bs-toggle="pill" data-bs-target="#pills-contact1" type="button" role="tab" aria-controls="pills-contact" aria-selected="false" onclick="FTActive(this)">Current</button>
                                    </li>
                                </ul>
                            </div>
                            <!--Tab contents for new orders, preparing orders and the current selected walkin orders-->
                            <div class="tab-content h-100 text-white" id="pills-tabContent">
                                <div class="tab-pane overflow-auto fade p-1 overflow-auto" id="pills-home1" role="tabpanel" aria-labelledby="PT-new1" tabindex="0" style="height: 78%;">
                                    <!--Datas for new orders are placed here-->

                                </div>
                                <div class="tab-pane overflow-auto fade p-1" id="pills-profile1" role="tabpanel" aria-labelledby="PT-Preparing1" tabindex="0" style="height: 78%;">
                                    <!--Datas for preparing orders are placed here-->

                                </div>
                                <div class="tab-pane show active overflow-auto fade p-1" id="pills-contact1" role="tabpanel" aria-labelledby="PT-Current" tabindex="0" style="height: 78%;">
                                    <!--Datas for Current orders are placed here-->
                                    <div class="overflow-auto d-flex align-items-start flex-wrap" style="height: 80%;">
                                        <?php
                                        $AFquery = "SELECT * FROM (SELECT foodcart.FoodCartID, foodcart.OrderID, foodcart.FoodID, foodcart.Quantity, food.FoodName, food.FoodPrice, foodcart.AccountID FROM foodcart INNER JOIN food ON foodcart.FoodID = food.FoodID )tb
                                        WHERE AccountID = $AID  AND OrderID IS NULL";
                                        $AFresult = mysqli_query($con, $AFquery);
                                        $OAPrice = 0;
                                        while ($WIFoodData = mysqli_fetch_assoc($AFresult)) {
                                            $OAPrice += ($WIFoodData["FoodPrice"] * $WIFoodData['Quantity']);
                                        ?>
                                            <div class="card FCh text-start d-inline-block mb-1" style="border: none; border-radius: 10px; background-color: transparent;">
                                                <img class="col-4 float-start" height="100px" src="" alt="FoodIMG" style="border-radius: 10px 0px 0px 10px;">
                                                <div class="card-body col-8 h-100 d-block float-start" style="border-radius: 0px 10px 10px 0px;">
                                                    <div class="w-100 d-block position-relative" style="height: 25px;">
                                                        <span class="btn btn-close position-absolute" onclick="DeleteData(this)" style="top: 20px; right:5px;"></span>
                                                        <h6 class="d-none"><?php echo $WIFoodData['FoodCartID'] ?></h6>
                                                        <h6 class="card-title float-start m-0"><?php echo $WIFoodData['FoodName'] ?></h6>
                                                        <p class="card-text mb-0 float-end">P<?php echo $WIFoodData['FoodPrice'] ?></p>
                                                    </div>
                                                    <!--Quantity-->
                                                    <div class="col-6 d-flex justify-content-evenly rounded-2 h-auto" style=" background: rgba(209,164,97,.8) !important;">
                                                        <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="add(this)">+</button>
                                                        <input class="col-6 d-flex text-center rounded-2 p-1" type="number" value="<?php echo $WIFoodData['Quantity'] ?>" style="background-color: transparent; border: 0px solid !important;">
                                                        <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="minus(this)">-</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="d-flex flex-row align-content-end container flex-wrap">
                                        <hr class="col-12 w-75 m-auto mt-1">
                                        <span class="col-6 justify-content-start d-inline-flex">
                                            <h6>Total: </h6>
                                        </span>
                                        <span class="col-6 justify-content-end float-end d-inline-flex">
                                            <h6 class="">P</h6>
                                            <!--Total price placed here-->
                                            <h6 id="TotalPrice"><?php echo $OAPrice ?></h6>
                                        </span>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end p-2">
                                        <form method="POST" class="col-12 d-flex justify-content-end">
                                            <input type="number" class="d-none" step="0.1" name="FQuantity" value="<?php echo $OAPrice ?>">
                                            <?php
                                            $query = "SELECT * FROM foodcart WHERE OrderID IS NULL AND AccountID = $AID LIMIT 1";
                                            $result = mysqli_query($con, $query);
                                            $order = mysqli_fetch_assoc($result);
                                            if (isset($order)) {
                                            ?>
                                                <input class="btn col-sm-6 col-12 fw-bold rounded-4" style="background-color: #d1a561;" type="submit" name="OrderBtn" value="Order Now">
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 h-100 bg-white rounded-5" style="z-index: 2;"></div>
                </div>

            </div>
    </main>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <!--Customized functions for tabs-->
    <script type="text/javascript">
        function xhttpload() {
            Allfood();
            Snackfood();
            Dishfood();
            Dessertfood();
            Drinkfood();
            Preparingfood();
            Preparedfood();
        }

        function delf(data) {
            document.getElementById('addfood').value = data.nextElementSibling.innerHTML;
            document.getElementById('QF').value = data.parentElement.previousElementSibling.childNodes[3].value;
            document.getElementById('Addfa').click();

        }

        function DeleteData(data) {
            document.getElementById('FC_Food_ID').value = data.nextElementSibling.innerHTML;
            document.getElementById('FC_del').click();
        }
        //multiselectfood
        function selectfood(div) {
            if (div.checked) {
                document.getElementById('PT-Current').click();
                var food = div.nextElementSibling.children[1].innerHTML;
                var price = div.nextElementSibling.children[2].innerHTML;
                document.getElementById("pills-contact").insertAdjacentHTML("beforeend",
                    "<div class= 'col-12 mb-1 p-2 " + div.value + " d-flex flex-wrap align-content-start ps-4 NOH  rounded-5 overflow-auto' id='" + div.value + "' style = 'background: rgba(127,106,108,.5); height: 90px;' >" +
                    "<span class='col-12 fs-3 fw-bold'>" + food + " #" + "</span>" +
                    "<div class='col-12 d-flex justify-content-between align-items-end pe-3'><span class='fs-6'>Time</span><span class='fs-5'>P" + price + "</span></div></div >");
                document.getElementById("pills-contact1").insertAdjacentHTML("beforeend",
                    "<div class= 'col-12 mb-1 p-2 " + div.value + " d-flex flex-wrap align-content-start ps-4 NOH  rounded-5 overflow-auto' id='" + div.value + "' style = 'background: rgba(127,106,108,.5); height: 90px;' >" +
                    "<span class='col-12 fs-3 fw-bold'>" + food + " #" + "</span>" +
                    "<div class='col-12 d-flex justify-content-between align-items-end pe-3'><span class='fs-6'>Time</span><span class='fs-5'>P" + price + "</span></div></div >");
            } else {
                const boxes = Array.from(document.getElementsByClassName(div.value));
                boxes.forEach(box => {
                    box.remove();
                });
            }
        }
        //OrderTabs 
        function FTActive(div) {
            if (div.id == "PT-new" || div.id == "PT-new1") {
                div.setAttribute('style', 'background: rgba(127,106,108) !important; color: white !important ;');
                document.getElementById('PT-Preparing').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Current').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Preparing1').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Current1').setAttribute('style', 'background: transparent !important; color: white !important ;');
            } else if (div.id == "PT-Preparing" || div.id == "PT-Preparing1") {
                div.setAttribute('style', 'background: rgba(127,106,108) !important; color: white !important ;');
                document.getElementById('PT-new').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Current').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-new1').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Current1').setAttribute('style', 'background: transparent !important; color: white !important ;');
            } else {
                div.setAttribute('style', 'background: rgba(127,106,108) !important; color: white !important ;');
                document.getElementById('PT-Preparing').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-new').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-Preparing1').setAttribute('style', 'background: transparent !important; color: white !important ;');
                document.getElementById('PT-new1').setAttribute('style', 'background: transparent !important; color: white !important ;');
            }

        }
        //get food infos and transfer to food modals
        function GetFoodInfos(div) {
            if (div.id == document.getElementById('addfoodm').id) {
                document.getElementById('FoodName').value = "";
                document.getElementById('FoodPrice').value = "";
                document.getElementById('AddFoodform').style.display = "block";
                document.getElementById('UpdateFoodform').style.display = "none";
                document.getElementById('DeletefoodForm').style.display = "none";
            } else {
                document.getElementById('UpdateFoodform').style.display = "block";
                document.getElementById('AddFoodform').style.display = "none";
                document.getElementById('DeletefoodForm').style.display = "block";
                document.getElementById('FoodName').value = div.children[1].children[0].innerHTML;
                document.getElementById('FoodPrice').value = parseInt(div.children[1].children[1].innerHTML.replace("P", ""));
            }
        }
        //toggle remover in navbar
        function ToggleRemove() {
            document.getElementById('navbarNavAltMarkup').classList.remove("show")
        }

        //add minus 
        function add(btn) {
            btn.nextElementSibling.value = parseInt(btn.nextElementSibling.value) + 1;
        }

        function minus(btn) {
            if (btn.previousElementSibling.value > 1) {
                btn.previousElementSibling.value = parseInt(btn.previousElementSibling.value) - 1;
            }

        }

        function AddFoodbtn() {
            document.getElementById('AddFoodbtn').click();
        }

        var Snacks = document.getElementById("Stab");
        var dish = document.getElementById("Dtab");
        var combo = document.getElementById("Ctab");
        var Drink = document.getElementById("Drtab");
        var All = document.getElementById("Atab");
        //Tabs constant Selected indicator
        function ATab() {
            All.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
            Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            combo.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
        }

        function DrTab() {
            All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            combo.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Drink.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
        }

        function DTab() {
            All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            dish.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
            combo.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
        }

        function STab() {
            All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Snacks.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
            dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            combo.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
        }

        function CTab() {
            All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
            combo.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
            Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
        }
        var count = 0;

        function FCtab(Tab) {
            if (count == 0) {
                Tab.setAttribute('style', 'border-right: 5px solid #d1a561 !important;');
                count++;
            } else {
                Tab.setAttribute('style', 'border-right: 0px solid #ecdb9c !important;');
                count--
            }

        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>