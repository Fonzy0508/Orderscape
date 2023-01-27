<?php
session_start();
$_SESSION;
include("Connections.php");
$InvalidMSG = "";
//Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Username = $_POST['User_Email'];
    $Password = $_POST['User_Password'];
    //Login logicl
    if (!empty($Username) && !empty($Password) && !is_numeric($Username)) {
        $query = "select * from accounts where Email = '$Username'";
        $result = mysqli_query($con, $query);
        if ($result) {
            if ($result && mysqli_num_rows($result) > 0) {
                $UserData = mysqli_fetch_assoc($result);

                if ($UserData['Password'] === $Password) {
                    $ID = $UserData['AccountID'];
                    $queryOnline = "UPDATE accounts SET ActiveStatus = 'true' WHERE AccountID = $ID";
                    $resultOnline = mysqli_query($con, $queryOnline);
                    if ($UserData['Account_Type'] === "Admin") {
                        header("Location: Admin Pages/Dashboard_Page.php");
                        $_SESSION['UserID'] = $UserData['AccountID'];
                        $_SESSION['A_Type'] = $UserData['Account_Type'];
                        die;
                    } elseif ($UserData['Account_Type'] === "Customer" || $UserData['Account_Type'] === "Teacher") {
                        header("Location: Customer Pages/Menu_Customer_Page.php");
                        $_SESSION['UserID'] = $UserData['AccountID'];
                        die;
                    } elseif ($UserData['Account_Type'] === "Staff") {
                        header("Location: Admin Pages/Order_Page.php");
                        $_SESSION['UserID'] = $UserData['AccountID'];
                        $_SESSION['A_Type'] = $UserData['Account_Type'];
                    } else {
                        echo "Something went wrong";
                    }
                }
            }
        }
        $InvalidMSG = "Wrong Username or password";
    } else {
        $InvalidMSG = "Invalid entered Email or Password";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Log in</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script>
        function readmore() {
            document.getElementById("Pbtn").style = "display:none; background-color: #d1a561;";
            document.getElementById("Pbtn1").style = "display:block; background-color: #d1a561;";
        }

        function readLess() {
            document.getElementById("Pbtn").style = "display:block; background-color: #d1a561;";
            document.getElementById("Pbtn1").style = "display:none; background-color: #d1a561;";
        }
    </script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-md h-auto" style="background-color: #7f6a6c;">
            <div class="col-12 pt-0 d-block position-absolute top-0" style="height: 10px; background-color: #efd581;"></div>
            <div class="container-fluid mt-2">
                <!--Navbar leftside-->
                <a class="navbar-brand" href="#">
                    <img src="Images/LogoXT.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                    <img src="Images/ORDERSCAPE-removebg-preview.png" alt="Logo" width="230" height="30" class="d-sm-inline-block d-none align-text-top">
                </a>
                <!--Navbar Rightside-->
                <div>
                    <button type="button" class="btn p-0">
                        <img src="Images/b1.png" alt="">
                    </button>
                    <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class=" text-dark" style="position: relative; top: -2px;"><img src="Images/image-removebg-preview.png" width="30px" height="30px"></span>
                        <div class="align-items-center d-inline-block ">
                            <h6 class="d-lg-inline-block m-0 p-2 text-decoration-underline">LOGIN</h6>
                        </div>
                    </button>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <!--Welcome text-->
        <div class="container p-2 h-auto mt-5">
            <div class="col align-self-center text-center m-4 mb-5">
                <h4 class="d-inline-block m-0">Welcome to</h4>
                <img src="Images/ORDERSCAPE-removebg-preview.png" alt="Logo" width="230" height="30" class=" align-text-top position-relative" style="top: -8px;">
            </div>
            <div class="col">
                <h4 style="color: #7f6a6c;">Order the food you like!</h4>
            </div>
        </div>
        <!--the big Food image-->
        <div class="container-fluid p-0">
            <img src="Images/foods.png" alt="" class="d-block col-12 ">
        </div>

        <!--FAQ Card-->
        <div class="container m-auto">
            <div class="card mt-5 mb-5 col-10 m-auto h-auto">
                <div class="card-body h-auto mb-3">
                    <h4 class="card-title">Frequently Asked Questions</h4>
                    <h5 class="card-subtitle mt-3">What is Orderscape?</h5>
                    <p class="card-text">Orderscape is STI's ordering system service designed for students, faculty, and staff. We are the partner of the STI
                        Canteen, where you may check the daily menu, make a reservation, and then place an order. No more waiting in long lines;
                        it is designed to make our schooldays more convenient.</p>
                    <div class="collapse" id="MoreInfo">
                        <p class="card-text">
                            As we gathered problems, we saw that the STI Canteen had a significant problem serving meals to a large number of
                            students and teachers. We observed that the canteen's slow service creates lengthy queues. Each teacher and student has
                            an allotted break period, therefore it must be utilized effectively. Students and teachers should not skip meals since
                            doing so will hinder their ability to be prepared and energetic for upcoming lessons. We all have different food
                            preferences and likes and dislikes, so knowing the menu saves everyone time and effort. As consumers no longer need to
                            check the menu manually for themselves, they now have more time to select their meals. Since the epidemic is not yet
                            gone, it is also safer and healthier. Long lineups at the canteen may compromise the school's safety precautions,
                            putting the health of students and teachers in danger.<br><br>

                            Therefore, we created Orderscape to assist STI students, teachers, and staff. With the support of Orderscape, the
                            professors, employees, and students at STI may make online reservations for meals, beverages, and desserts, enabling the
                            canteen staff to serve clients more efficiently.<br>
                            As a result, the likelihood of long lineups will decrease, ensuring consumers can eat on time while adhering to health
                            regulations.<br>
                            We believe that faculty, students, and staff will find our application useful in their everyday school.<br><br>

                            Our program's main goal is to shorten the wait in line for meals at the school cafeteria and make ordering quicker for
                            students who are rushing to eat but don't want to stand in line. It can also be used to check the cafeteria's menu for
                            today so that you won't have to wait in line and check it yourself. Additionally, it features a separate system for
                            staff, teachers, and students, allowing them to line up in a line that is prioritized for them. The program can assist
                            the canteen staff in streamlining their workload and providing orders to the kids as quickly as feasible.
                        </p>
                    </div>
                    <button id="Pbtn" onclick="readmore()" class="mt-3 btn fw-bold col-12" style="background-color: #d1a561;" type="button" data-bs-toggle="collapse" data-bs-target="#MoreInfo" aria-expanded="false" aria-controls="MoreInfo">
                        Read More
                    </button>
                    <button id="Pbtn1" onclick="readLess()" class="mt-3 btn fw-bold col-12" style="display: none; background-color: #d1a561;" type="button" data-bs-toggle="collapse" data-bs-target="#MoreInfo" aria-expanded="false" aria-controls="MoreInfo">
                        Read Less
                    </button>
                </div>
            </div>
        </div>

        <!--Login modal-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header d-block h-auto position-relative">
                        <button type="button" class="btn-close d-flex justify-content-end position-absolute" style="top: 20px; right:20px;" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="container-fluid col-12">
                            <img src="Images/LogoXT.png" class="d-flex m-auto" width="80px" height="80px" alt="">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form method="POST">
                                <div class="d-flex flex-row align-items-center justify-content-center justify-content-lg-start">

                                    <a href="" class="m-auto">
                                        <button type="button" class="btn col-12 h-auto m-auto w-auto" style="background-color: #F25022;">
                                            <img src="Images/windows-logo.png" width="20px" height="20px" class="m-2 d-inline-block">
                                            <p class="d-inline-block text-white m-2">SIGN IN WITH YOUR 0365 ACCOUNT</p>
                                        </button>
                                    </a>
                                </div>

                                <div class="divider d-flex align-items-center my-4">
                                    <p class="text-center fw-bold mb-0 m-auto col-12">Or</p>
                                </div>
                                <div class="invalid-feedback d-flex col-12 justify-content-center">
                                    <span><?php echo $InvalidMSG ?></span>
                                </div>
                                <!-- Email input -->
                                <div class="form-outline mb-4 was-validated">
                                    <label class="form-label" for="form3Example3">Email address</label>
                                    <input type="email" id="form3Example3" name="User_Email" class="form-control form-control-lg is-valid" placeholder="Enter Email" required />                                    
                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-3 was-validated">
                                    <label class="form-label" for="form3Example4">Password</label>
                                    <input type="password" id="form3Example4" name="User_Password" class="form-control form-control-lg is-valid" placeholder="Enter password" required />
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Checkbox -->
                                    <div class="form-check mb-0">
                                        <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                                        <label class="form-check-label" for="form2Example3">
                                            Remember me
                                        </label>
                                    </div>
                                    <a href="#!" class="text-body">Forgot password?</a>
                                </div>

                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <!--href="../Customer Pages/Menu Page/Menu_Customer_Page.html"-->
                                    <a>
                                        <input type="submit" value="Login" class="btn m-auto col-12 btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem; background-color: #d1a561;"></button>
                                    </a>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="container-fluid pt-3 pb-3 p-5" style="background-color: #ecdb9c;">
            <div class="col-12">
                <h5 style="color: #737373;">O r d e r s c a p e</h5>
                <hr class="border-3">
            </div>
            <div class="col-12 d-flex justify-content-evenly ">
                <button class="btn fw-bolder" style="color: #737373;">About Orderscape</button>
                <button class="btn fw-bolder" style="color: #737373;">Help Center</button>
                <button class="btn fw-bolder" style="color: #737373;">FAQs</button>
            </div>
        </div>
        <div class="col-12" style="height: 80px; background-color: #7f6a6c;"></div>
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
</body>

</html>