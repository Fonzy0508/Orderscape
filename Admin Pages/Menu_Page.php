<?php
session_start();
include("../Connections.php");
include("../Functions.php");
//Checks if logged in
$Current_UserData = CheckLogin($con);
//Gets all food data by category
$AT = $_SESSION['A_Type'];
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
// Add/delete/update food that is currently posted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $FID = $_POST['Food_ID'];
    $FN = $_POST['Food_Name'];
    $FP = $_POST['Food_Price'];
    $C = $_POST['Food_Category'];
    $IM = $_POST['Food_Img'];
    if (isset($_POST['Addbtn'])) {
        $queryf = "insert into food (FoodName, FoodPrice, Category, Image) values ('$FN',$FP,'$C','$IM')";
        $result = mysqli_query($con, $queryf);
        header("Location: Menu_Page.php");
    } elseif (isset($_POST['Deletebtn'])) {
        $queryf = "delete from food where FoodID = $FID";
        $result = mysqli_query($con, $queryf);
        header("Location: Menu_Page.php");
    } elseif (isset($_POST['Updatebtn'])) {
        $queryf = "update food set FoodName = '$FN', FoodPrice = $FP, Category = '$C', Image = '$IM' where FoodID = $FID ";
        $result = mysqli_query($con, $queryf);
        header("Location: Menu_Page.php");
    } else {
        echo "bobo ka lods";
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <title>Menu</title>
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
            <div class="h-100 float-none float-md-start p-0 m-auto ms-md-0 container-fluid scrollautohide" style="overflow-x: hidden !important;">
                <div class="col-12 h-auto d-inline-block">
                    <!--Searchbar-->
                    <div class="container mt-2 mb-3 m-auto">
                        <form class="d-flex" role="search">
                            <div style="width:38px; height: 38px; background-color: #ecdb9c; border-top-left-radius: .375em; border-bottom-left-radius: .375em;
                                border: 1px solid #ced4da; border-right: 0px;">
                                <img src="Images/magnifying-glass.png" width="20px" height="20px" style="margin: 9px; border: 1px !important;" alt="">
                            </div>

                            <input class="form-control me-2 Searchbar ps-0 pe-2" style="background-color: #ecdb9c;" type="search" placeholder="What would you like to eat?" aria-label="Search">
                            <!-- search button... remove comment if needed

                            <button class="btn " style="background-color: #ecdb9c;" type="submit">Search</button>-->
                        </form>
                    </div>
                    <!--advertisment-->
                    <div class="content mb-3 w-auto">
                        <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner m-auto" style="height: 200px; width: 700px;">
                                <div class="carousel-item active" data-bs-interval="10000">
                                    <img src="Images/FoodAdvertisment.jpg" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item" data-bs-interval="2000">
                                    <img src="Images/ORDERSCAPE-removebg-preview.png" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img src="Images/ORDERSCAPE-removebg-preview.png" class="d-block w-100" alt="...">
                                    <!-- captions for slides.. Copy if needed

                                    <div class="carousel-caption d-none d-md-block">
                                    <h5>Third slide label</h5>
                                    <p>Some representative placeholder content for the third slide.</p>
                                    </div>-->
                                </div>
                            </div>
                            <!--Left right button for the advertisment.. remove comment if needed

                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                            </button>-->
                        </div>
                    </div>
                </div>
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
                                    <form class="d-flex flex-wrap" id="Foodform" method="POST">
                                        <div class="col-4">
                                            <label class="btn btn-default btn-file w-100 h-100 bg-black">
                                                <img src="" alt=""> <input type="file" name="Food_Img" style="display: none;">
                                            </label>
                                        </div>
                                        <div class="col-8">
                                            <div class="container row g-3 was-validated">
                                                <input class="d-none" id="FoodID" name="Food_ID">
                                                <div class="col-12">
                                                    <label for="FoodName" class="form-label">Food Name</label>
                                                    <input type="text" class="form-control is-valid" id="FoodName" name="Food_Name" required>
                                                    <div class="invalid-feedback">
                                                        Input Food Name
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label for="FoodPrice" class="form-label">Food Price</label>
                                                    <div class="input-group has-validation">
                                                        <span class="input-group-text" id="inputGroupPrepend3">₱</span>
                                                        <input type="number" step="0.1" class="form-control" id="FoodPrice" name="Food_Price" aria-describedby="validationServerPriceFeedback" required>
                                                        <div id="validationServerPriceFeedback" class="invalid-feedback">
                                                            Please input Food price.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-12 col-md-6">
                                                    <label for="validationServerPrice" class="form-label">Food Category</label>
                                                    <select id="SelectC" class="form-select" name="Food_Category" required aria-label="select example">
                                                        <option value="">Open this select menu</option>
                                                        <option value="Dish">Dish</option>                                                        
                                                        <option value="Drink">Drinks</option>
                                                        <option value="Snack">Snacks</option>
                                                        <option value="Dessert">Desserts</option>
                                                    </select>
                                                    <div class="invalid-feedback">Select Food Category.</div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="col-12 w-100 d-flex" style="height: 50px;">
                                                        <div class="col-6 p-1">
                                                            <input id="AddFoodform" class="btn btn-primary w-100" type="submit" name="Addbtn" value="Add Food">
                                                            <input id="UpdateFoodform" class="btn btn-primary w-100" type="submit" name="Updatebtn" value="Update Food">
                                                        </div>
                                                        <div class="col-6 p-1">
                                                            <input id="DeletefoodForm" class="btn btn-danger w-100" type="submit" name="Deletebtn" value="Delete">

                                                        </div>
                                                    </div>
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

                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-fill text-dark m-2" id="myTab" role="tablist">
                    <li class="nav-item text-dark fw-bold" role="presentation">
                        <button class="nav-link text-dark active foodtabs" id="Atab" onclick="ATab()" data-bs-toggle="tab" data-bs-target="#AllFoodTab" type="button" role="tab" aria-controls="home" aria-selected="true">All</button>
                    </li>
                    <li class="nav-item text-dark fw-bold " role="presentation">
                        <button class="nav-link text-dark  foodtabs" id="Dtab" onclick="DTab()" data-bs-toggle="tab" data-bs-target="#DishTab" type="button" role="tab" aria-controls="profile" aria-selected="false">Dish</button>
                    </li>
                    <li class="nav-item text-dark fw-bold" role="presentation">
                        <button class="nav-link text-dark foodtabs" id="Dstab" onclick="CTab()" data-bs-toggle="tab" data-bs-target="#DessertTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Dessert</button>
                    </li>
                    <li class="nav-item text-dark fw-bold" role="presentation">
                        <button class="nav-link text-dark foodtabs" id="Drtab" onclick="DrTab()" data-bs-toggle="tab" data-bs-target="#DrinksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Drinks</button>
                    </li>
                    <li class="nav-item fw-bold" role="presentation">
                        <button class="nav-link text-dark foodtabs" id="Stab" onclick="STab()" data-bs-toggle="tab" data-bs-target="#SnacksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Snacks</button>
                    </li>
                </ul>
                <!-- Tab panes :: Food cards -->
                <div class="tab-content justify-content-center">
                    <!--All food List-->
                    <div class="tab-pane active" id="AllFoodTab" role="tabpanel" aria-labelledby="home-tab">
                        <script type="text/javascript">
                            function Allfood() {
                                const xhttp = new XMLHttpRequest();
                                xhttp.onload = function() {
                                    document.getElementById('Allfoodtb').innerHTML = this.responseText;
                                }
                                xhttp.open("GET", "Functions/AllfoodFunc.php");
                                xhttp.send();
                            }
                            setInterval(function() {
                                Allfood();
                            }, 1500);
                        </script>
                        <div id="Allfoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100 ">

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
                                xhttp.open("GET", "Functions/SnackfoodFunc.php");
                                xhttp.send();
                            }
                            setInterval(function() {
                                Snackfood();
                            }, 1500);
                        </script>
                        <div id="Snackfoodtb" class="container-fluid d-flex align-content-start flex-wrap p-md-3 w-100 h-100">

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
                                xhttp.open("GET", "Functions/DishfoodtbFunc.php");
                                xhttp.send();
                            }
                            setInterval(function() {
                                Dishfood();
                            }, 1500);
                        </script>
                        <div id="Dishfoodtb" class="container-fluid d-flex align-content-start flex-wrap p-md-3 w-100 h-100">

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
                                xhttp.open("GET", "Functions/DessertfoodFunc.php");
                                xhttp.send();
                            }
                            setInterval(function() {
                                Dessertfood();
                            }, 1500);
                        </script>
                        <div id="Dessertfoodtb" class="container-fluid d-flex align-content-start flex-wrap p-md-3 w-100 h-100">

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
                                xhttp.open("GET", "Functions/DrinkfoodFunc.php");
                                xhttp.send();
                            }
                            setInterval(function() {
                                Drinkfood();
                            }, 1500);
                        </script>
                        <div id="Drinkfoodtb" class="container-fluid d-flex align-content-start flex-wrap p-md-3 w-100 h-100">

                        </div>
                    </div>
                </div>
            </div>
    </main>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <!--Customized functions for tabs-->
    <script>
        function xhttpload() {
            Allfood();
            Snackfood();
            Dishfood();
            Dessertfood();
            Drinkfood();
        }
        //get food infos and transfer to food modals
        function GetFoodInfos(div) {
            if (div.id == document.getElementById('addfoodm').id) {
                document.getElementById('FoodName').value = "";
                document.getElementById('FoodPrice').value = "";
                document.getElementById('SelectC').value = "";
                document.getElementById('FoodID').value = "";
                document.getElementById('AddFoodform').style.display = "block";
                document.getElementById('UpdateFoodform').style.display = "none";
                document.getElementById('DeletefoodForm').style.display = "none";
            } else {
                document.getElementById('UpdateFoodform').style.display = "block";
                document.getElementById('AddFoodform').style.display = "none";
                document.getElementById('DeletefoodForm').style.display = "block";
                document.getElementById('FoodName').value = div.children[1].children[0].innerHTML;
                document.getElementById('FoodPrice').value = parseInt(div.children[1].children[1].innerHTML.replace("P", ""));
                document.getElementById('FoodID').value = div.children[1].children[2].innerHTML;
                console.log(div.children[1].children[2].innerHTML);
                if (div.children[1].children[3].innerHTML.toLowerCase() == "dish") {
                    document.getElementById('SelectC').value = "Dish";
                } else if (div.children[1].children[3].innerHTML.toLowerCase() == "drink") {
                    document.getElementById('SelectC').value = "Drink";
                } else if (div.children[1].children[3].innerHTML.toLowerCase() == "combo") {
                    document.getElementById('SelectC').value = "Combo";
                } else if (div.children[1].children[3].innerHTML.toLowerCase() == "dessert") {
                    document.getElementById('SelectC').value = "Dessert";
                } else if (div.children[1].children[3].innerHTML.toLowerCase() == "snacks") {
                    document.getElementById('SelectC').value = "Snack";
                } else {
                    document.getElementById('SelectC').value = "";
                }
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

        var Snacks = document.getElementById("Stab");
        var dish = document.getElementById("Dtab");
        var combo = document.getElementById("Dstab");
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