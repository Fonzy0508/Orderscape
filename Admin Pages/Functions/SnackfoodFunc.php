<div class="card text-start m-2 col-xl-2 col-md-3 col-sm-4 col-12 ms-sm-5 ms-md-4 float-start" data-bs-toggle="modal" data-bs-target="#AddFoodmodal" id="addfoodm" onclick="GetFoodInfos(this)" style="background: rgba(209,162,97,.15);">
    <div class="col-12 d-flex justify-content-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="#d1a561" class="bi bi-plus-lg" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
        </svg>
    </div>
    <div class="card-body">
        <h6 class="card-title float-start">Add Food</h4>
    </div>
</div>

<?php
include("../../Connections.php");
$queryS = "select * from food where Category = 'Snack'";
$resultS = mysqli_query($con, $queryS);
while ($row = mysqli_fetch_assoc($resultS)) {
?>
    <div class="card text-start m-2 col-xl-2 col-md-3 col-sm-4 col-12 ms-sm-5 ms-md-4 float-start" onclick="GetFoodInfos(this)" data-bs-toggle="modal" data-bs-target="#AddFoodmodal">
        <img class="card-img-top" src="<?php echo $row['Image'] ?>" alt="FoodIMG">
        <div class="card-body">
            <h6 class="card-title float-start"><?php echo $row['FoodName'] ?></h6>
            <h6 class="float-end">P<?php echo $row['FoodPrice'] ?></h6>
            <span class="d-none" id="<?php echo $row['FoodID'] ?>"><?php echo $row['FoodID'] ?></span>
            <span class="d-none" class="<?php echo $row['Category'] ?>"><?php echo $row['Category'] ?></span>
        </div>
    </div>
<?php
}
?>