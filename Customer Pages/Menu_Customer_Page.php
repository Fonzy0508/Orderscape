<?php

use LDAP\Result;

session_start();
include("../Connections.php");
include("../Functions.php");
//Checks if an account is logged in
$Current_UserData = CheckLogin($con);
//gets current UserID
$AID = $_SESSION['UserID'];

//Get all food by categorys
$query = "select * from food";
$queryD = "select * from food where Category = 'Dish'";
$queryDr = "select * from food where Category = 'Drink'";
$queryC = "select * from food where Category = 'Dessert'";
$queryS = "select * from food where Category = 'Snack'";
$queryCFC = "select * from foodcart where AccountID = $AID";
$result = mysqli_query($con, $query);
$resultD = mysqli_query($con, $queryD);
$resultDr = mysqli_query($con, $queryDr);
$resultC = mysqli_query($con, $queryC);
$resultS = mysqli_query($con, $queryS);
$resultCFC = mysqli_query($con, $queryCFC);
$queryAlert = "SELECT AlertMSG FROM accounts WHERE AccountID = $AID";
$resultAlert = mysqli_query($con, $queryAlert);
//handles multiple form posts
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //Add selected food to foodcart
  if (isset($_POST['FC_Add'])) {
    $FID = $_POST['Food_ID'];
    $FQ = $_POST['Food_Quantity'];
    $queryFC = "INSERT INTO foodcart(AccountID, FoodID, Quantity) VALUES((SELECT AccountID FROM accounts WHERE AccountID = $AID),$FID,$FQ)";
    $resultFC = mysqli_query($con, $queryFC);
    header("Location: Menu_Customer_Page.php");
  } elseif (isset($_POST['FC_Delete'])) {
    $FCFID = $_POST['FFood_ID'];
    $query = "DELETE FROM foodcart WHERE FoodID = $FCFID AND AccountID = $AID";
    $result = mysqli_query($con, $query);
    header("Location: Menu_Customer_Page.php");
  } elseif (isset($_POST['OrderBtn'])) {
    $FQC = floatval($_POST["FQuantity"]);
    $queryO = "INSERT INTO orders(AccountID, OrderPrice) VALUES((SELECT AccountID FROM accounts WHERE AccountID = $AID),$FQC)";
    $queryFCO = "UPDATE foodcart SET OrderID = (SELECT OrderID FROM orders WHERE AccountID = $AID) WHERE AccountID = $AID";
    $result = mysqli_query($con, $queryO);
    $resultfd = mysqli_query($con, $queryFCO);
    header("Location: Menu_Customer_Page.php");
  } elseif (isset($_POST['Alertdel'])) {
    $queryA = "UPDATE accounts SET AlertMSG = NULL WHERE AccountID = $AID";
    $resultA = mysqli_query($con, $queryA);
    header("Location: Menu_Customer_Page.php");
  }
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
  <title>Menu Page</title>
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

    .activezy {
      border-right: 5px solid #d1a561 !important;
    }

    .activezy:active {
      border-right: 5px solid #ecdb9c !important;
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
<script type="text/javascript">
  function AlertMSG() {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
      document.getElementById('bodya').innerHTML = this.responseText;
      if (document.getElementById('bodya').hasChildNodes()) {
        document.getElementById('bodya').style.zIndex = 5;
      } else {
        document.getElementById('bodya').style.zIndex = -5;
      }
    }
    xhttp.open("GET", "AlertMSGFunc.php");
    xhttp.send();
  }
  setInterval(function() {
    AlertMSG();
  }, 500);
</script>

<body class="h-100 overflow-hidden" onload="xhttpload();">
  <div id="bodya" class="col-12 d-flex justify-content-center align-content-center align-items-center h-100 position-absolute" style="z-index: -5;">
  </div>
  <header>
    <nav class="navbar navbar-expand-md h-auto" style="background-color: #7f6a6c;">
      <div class="col-12 pt-0 d-block position-absolute top-0" style="height: 10px; background-color: #efd581;"></div>
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


    <!--SidebarToggle-->
    <div class="position-absolute fade h-25 w-50 collapse navbar-collapse" id="navbarNavAltMarkup" style="background-color: #ecdb9c; z-index: 2;">
      <div class="navbar-nav">
        <a class="nav-link active NBH m-2 p-2 rounded-2" aria-current="page" href="Menu_Customer_Page.html">
          <img src="Images/home.png" alt="">
          Home</a>
        <a class="nav-link NBH m-2 p-2 rounded-2" href="#" onclick="ToggleRemove()" data-bs-toggle="collapse" data-bs-target="#FCNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <img src="Images/hot-food.png" alt="">
          Foodcart</a>
      </div>
    </div>
    <div class="d-block w-100 h-100 d-flex justify-content-evenly">
      <!--Side bar-->
      <div class="h-100 bg-black float-start d-none d-md-inline-block w-auto" style="background: rgba(236,219,156,.8) !important;">
        <div class="d-flex flex-column flex-shrink-0 bg-light mt-3" style="width: auto; height: 90vh; background: rgba(236,219,156,.3) !important;">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <li class="nav-item mt-2">
              <a href="Menu_Customer_Page.php" class="btn nav-link rounded-0 Sidezy activezy" onmouseover="this.style.borderRight='5px'" title="Home" aria-current="page">
                <img src="Images/home.png" alt="">
              </a>
            </li>
            <li class="nav-item mt-2">
              <a href="#" class="btn nav-link rounded-0 Sidezy" onclick="FCtab()" id="FC" title="Foodcart" data-bs-toggle="collapse" data-bs-target="#FCNav" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <img src="Images/hot-food.png" alt="">
              </a>
            </li>
          </ul>
        </div>
      </div>
      <!--Main contents-->
      <div class="h-100 float-none float-md-start p-0 m-auto ms-md-0 container-fluid scrollautohide" style="overflow-x: hidden !important;">
        <!--Foodcart sidebar-->
        <div id="FCNav" class="fcside collapse-horizontal collapse-horizontal collapse col-sm-10 col-md-6 col-lg-4 col-12 position-fixed h-100" style="z-index: 3!important; background-color: #ecdb9c; right: 0px; z-index: 2;">
          <div class="h-100 w-100">
            <!--Title-->
            <div class="w-100 ">
              <h4 class="m-3 col-8 d-inline-block">My Orders</h3>
                <button type="button" class="col-4 position-absolute m-3 btn-close d-inline-block" onclick="FCtab()" data-bs-toggle="collapse" data-bs-target="#FCNav" style="right: 0;" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation"></button>
            </div>
            <!--TimeSelect-->
            <div class="container" style="height:75px">
              
              <div class="d-flex container align-content-center justify-content-center align-items-center">
                <span class="d-flex align-content-center rounded-4 justify-content-center align-items-center" style="width:200px; height: 35px;background-color: #d1a561;">
                  <h6 class="m-2">Foodcart</h6>
                </span>
              </div>
              
            </div>
            <!--Foodcart foods-->
            <div class="container scrollautohide" style="height: 350px;">
              <!--Foods selected-->
              <form method="POST" class="d-none">
                <input type="number" name="FFood_ID" id="FC_Food_ID">
                <input type="submit" name="FC_Delete" id="FC_del">
              </form>
              <?php
              //Foodcart selected foods
              $FoodOPrice = 0;
              while ($row = mysqli_fetch_assoc($resultCFC)) {
                $f = $row['FoodID'];
                //Food datas
                $query = "SELECT * FROM food WHERE FoodID = $f";
                $resultCF = mysqli_query($con, $query);
                $foodData = mysqli_fetch_assoc($resultCF);

                $FoodOPrice += ($foodData["FoodPrice"] * $row['Quantity']);
                //ifstatements prevents user to delete if it is ordered
                if (!isset($row['OrderID'])) { ?>
                  <div class="p-2 ">
                    <div class="card FCh text-start d-inline-block h-100 w-100 m-auto" style="border: none; border-radius: 10px; background-color: transparent;">
                      <img class="col-4 float-start" height="100px" src="" alt="FoodIMG" style="border-radius: 10px 0px 0px 10px;">
                      <div class="card-body col-8 h-100 d-block float-start" style="border-radius: 0px 10px 10px 0px;">
                        <div class="w-100 d-block position-relative" style="height: 25px;">
                          <span class="btn btn-close position-absolute" onclick="DeleteData(this)" style="top: 20px; right:5px;"></span>
                          <h6 class="d-none"><?php echo $foodData['FoodID'] ?></h6>
                          <h6 class="card-title float-start m-0"><?php echo $foodData['FoodName'] ?></h6>
                          <p class="card-text mb-0 float-end">P<?php echo $foodData['FoodPrice'] ?></p>
                        </div>
                        <!--Quantity-->
                        <div class="col-6 d-flex justify-content-evenly rounded-2 h-auto" style=" background: rgba(209,164,97,.8) !important;">
                          <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="add(this)">+</button>
                          <input class="col-6 d-flex text-center rounded-2 p-1" type="number" value="<?php echo $row['Quantity'] ?>" style="background-color: transparent; border: 0px solid !important;">
                          <button class="btn col-3 d-flex justify-content-center fw-bolder" onclick="minus(this)">-</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php } elseif (isset($row['OrderID'])) { ?>
                  <div class="p-2 ">
                    <div class="card FCh text-start d-inline-block h-100 w-100 m-auto" style="border: none; border-radius: 10px; background-color: transparent;">
                      <img class="col-4 float-start" height="100px" src="" alt="FoodIMG" style="border-radius: 10px 0px 0px 10px;">
                      <div class="card-body col-8 h-100 d-block float-start" style="border-radius: 0px 10px 10px 0px;">
                        <div class="w-100 d-block position-relative" style="height: 25px;">
                          <h6 class="d-none"><?php echo $foodData['FoodID'] ?></h6>
                          <h6 class="card-title float-start m-0"><?php echo $foodData['FoodName'] ?></h6>
                          <p class="card-text mb-0 float-end">P<?php echo $foodData['FoodPrice'] ?></p>
                        </div>
                        <!--Quantity-->
                        <div class="col-6 d-flex justify-content-evenly rounded-2 h-auto" style=" background: rgba(209,164,97,.8) !important;">
                          <input class="col-12 d-flex text-center rounded-2 p-1" type="number" value="<?php echo $row['Quantity'] ?>" style="background-color: transparent; border: 0px solid !important;">
                        </div>
                      </div>
                    </div>
                  </div>
              <?php
                }
              }
              ?>
            </div>

            <div class="flex-row container flex-wrap">
              <hr class="w-75 m-auto mt-1">
              <span class="align-content-start d-inline-flex">
                <h6>Total: </h6>
              </span>
              <span class="justify-content-end float-end d-inline-flex">
                <h6 class="">P</h6>
                <!--Total price placed here-->
                <h6 id="TotalPrice"><?php echo $FoodOPrice ?></h6>
              </span>
            </div>
            <div class="col-12 d-flex justify-content-end p-2">
              <form method="POST" class="col-12 d-flex justify-content-end">
                <input type="number" class="d-none" step="0.1" name="FQuantity" value="<?php echo $FoodOPrice ?>">
                <?php
                $resultif = mysqli_query($con, "SELECT * FROM foodcart WHERE AccountID = $AID LIMIT 1");
                $row = mysqli_fetch_assoc($resultif);
                if (!isset($row['OrderID']) && isset($row)) { ?>
                  <input class="btn col-sm-6 col-12 fw-bold rounded-4" style="background-color: #d1a561;" type="submit" name="OrderBtn" value="Order Now">
                <?php } elseif (isset($row['OrderID'])) { ?>
                  <span class="col-12">
                    <h5 class="col-12 text-center">Pay P<?php echo $FoodOPrice ?> and Recieve order at the canteen</h5>
                    <h6 class="col12 text-center">Order will be discarded 30 minutes after ordered.</h6>
                  </span>
                <?php } ?>
              </form>
            </div>
          </div>
        </div>
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
        <!-- Food Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog-centered modal-dialog">
            <div class="modal-content" style=" background-color: #d1a561; ">
              <div class="modal-body">
                <!--Form Foodcart add-->
                <form method="post">
                  <button type="button" class="m-4 btn-close position-absolute" style="top: 0; right:0;" data-bs-dismiss="modal" aria-label="Close"></button>
                  <div class="col-12 col-sm-6 d-flex justify-content-center p-2 float-start">
                    <img id="FDIMG" src="" class="w-100 h-auto" alt="">
                  </div>
                  <div class="col-12 col-sm-6 float-start d-flex flex-wrap h-auto" style=" padding: 50px 20px;">
                    <div class="d-block col-12">
                      <h6 id="FDN" class="fw-bolder float-start"></h6>
                      <h6 id="FDP" class="fw-bolder float-end"></h6>
                      <input id="FDID" class="d-none" type="number" name="Food_ID">
                      <input id="FDNV" class="d-none" type="text" name="Food_Name">
                      <input id="FDPV" class="d-none" type="number" step="0.1" name="Food_Price">
                    </div>
                    <div class="col-6 d-flex justify-content-evenly rounded-2 bg-white h-auto">
                      <input class="btn col-3 d-flex justify-content-center" type="button" onclick="add(this)" value="+">
                      <input class="col-6 d-flex text-center rounded-2 p-1" type="number" name="Food_Quantity" value="1" style="background-color: transparent; border: 0px solid !important;">
                      <input class="btn col-3 d-flex justify-content-center" type="button" onclick="minus(this)" value="-">
                    </div>
                  </div>
                  <?php
                  $resultif = mysqli_query($con, "SELECT * FROM foodcart WHERE AccountID = $AID LIMIT 1");
                  $row = mysqli_fetch_assoc($resultif);
                  if (!isset($row['OrderID'])) { ?>
                    <input class="btn w-100 text-center border-2 bg-white" value="Add to cart" type="submit" name="FC_Add">
                  <?php } elseif (isset($row['OrderID'])) { ?>
                    <span class="col-12">
                      <h5 class="col-12 text-center">Pay P<?php echo $FoodOPrice ?> and Recieve your order first.</h5>
                    </span>
                  <?php } ?>

                </form>
              </div>

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
            <button class="nav-link text-dark foodtabs" id="Dstab" onclick="DsTab()" data-bs-toggle="tab" data-bs-target="#DessertTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Dessert</button>
          </li>
          <li class="nav-item text-dark fw-bold" role="presentation">
            <button class="nav-link text-dark foodtabs" id="Drtab" onclick="DrTab()" data-bs-toggle="tab" data-bs-target="#DrinksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Drinks</button>
          </li>
          <li class="nav-item fw-bold" role="presentation">
            <button class="nav-link text-dark foodtabs" id="Stab" onclick="STab()" data-bs-toggle="tab" data-bs-target="#SnacksTab" type="button" role="tab" aria-controls="messages" aria-selected="false">Snacks</button>
          </li>
        </ul>
        <!-- Tab panes :: Food cards -->
        <div class="tab-content">
          <!--All food List-->
          <div class="tab-pane active" id="AllFoodTab" role="tabpanel" aria-labelledby="home-tab">
            <script type="text/javascript">
              function Allfood() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                  document.getElementById('AllFoodtb').innerHTML = this.responseText;
                }
                xhttp.open("GET", "OrderListsFuncs/AllfoodFunc.php");
                xhttp.send();
              }
              setInterval(function() {
                Allfood();
              }, 1000);
            </script>
            <div id="AllFoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100">

            </div>
          </div>
          <!--Snacks List-->
          <div class="tab-pane" id="SnacksTab" role="tabpanel" aria-labelledby="messages-tab">
            <script type="text/javascript">
              function Snackfood() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                  document.getElementById('SnackFoodtb').innerHTML = this.responseText;
                }
                xhttp.open("GET", "OrderListsFuncs/SnackfoodFunc.php");
                xhttp.send();
              }
              setInterval(function() {
                Snackfood();
              }, 1000);
            </script>
            <div id="SnackFoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100">

            </div>
          </div>
          <!--Dish List-->
          <div class="tab-pane" id="DishTab" role="tabpanel" aria-labelledby="profile-tab">
            <script type="text/javascript">
              function Dishfood() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                  document.getElementById('DishFoodtb').innerHTML = this.responseText;
                }
                xhttp.open("GET", "OrderListsFuncs/DishfoodFunc.php");
                xhttp.send();
              }
              setInterval(function() {
                Dishfood();
              }, 1000);
            </script>
            <div id="DishFoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100">

            </div>
          </div>
          <!--Drinks List-->
          <div class="tab-pane" id="DrinksTab" role="tabpanel" aria-labelledby="messages-tab">
            <script type="text/javascript">
              function Drinkfood() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                  document.getElementById('DrinkFoodtb').innerHTML = this.responseText;
                }
                xhttp.open("GET", "OrderListsFuncs/DrinkfoodFunc.php");
                xhttp.send();
              }
              setInterval(function() {
                Drinkfood();
              }, 1000);
            </script>
            <div id="DrinkFoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100">
            </div>
          </div>
          <!--Dessert List-->
          <div class="tab-pane" id="DessertTab" role="tabpanel" aria-labelledby="messages-tab">
            <script type="text/javascript">
              function Dessertfood() {
                const xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                  document.getElementById('DessertFoodtb').innerHTML = this.responseText;
                }
                xhttp.open("GET", "OrderListsFuncs/DessertfoodFunc.php");
                xhttp.send();
              }
              setInterval(function() {
                Dessertfood();
              }, 1000);
            </script>
            <div id="DessertFoodtb" class="container-fluid d-flex flex-wrap align-content-start p-md-3 w-100 h-100">
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
      AlertMSG();
      Allfood();
      Snackfood();
      Dishfood();
      Dessertfood();
      Drinkfood();
    }
    //get food infos and transfer to food modals
    function DeleteData(data) {
      document.getElementById('FC_Food_ID').value = data.nextElementSibling.innerHTML;
      document.getElementById('FC_del').click();
    }

    function GetFoodInfos(div) {
      document.getElementById('FDN').innerHTML = div.children[1].children[0].innerHTML;
      document.getElementById('FDP').innerHTML = div.children[1].children[1].innerHTML;
      document.getElementById('FDNV').value = div.children[1].children[0].innerHTML;
      document.getElementById('FDPV').value = div.children[1].children[1].innerHTML;
      document.getElementById('FDID').value = div.children[1].children[2].innerHTML;
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
    var Dessert = document.getElementById("Dstab");
    var Drink = document.getElementById("Drtab");
    var All = document.getElementById("Atab");
    //Tabs constant Selected indicator
    function ATab() {
      All.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
      Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Dessert.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
    }

    function DrTab() {
      All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Dessert.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Drink.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
    }

    function DTab() {
      All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      dish.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
      Dessert.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
    }

    function STab() {
      All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Snacks.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
      dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Dessert.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
    }

    function DsTab() {
      All.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Snacks.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      dish.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
      Dessert.setAttribute('style', 'border-bottom: 5px solid #d1a561 !important;');
      Drink.setAttribute('style', 'border-bottom: 5px solid #ecdb9c !important;');
    }
    var count = 0;

    function FCtab() {
      if (count == 0) {
        document.getElementById('FC').setAttribute('style', 'border-right: 5px solid #d1a561 !important;');
        count++;
      } else {
        document.getElementById('FC').setAttribute('style', 'border-right: 0px solid #ecdb9c !important;');
        count--
      }

    }
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>