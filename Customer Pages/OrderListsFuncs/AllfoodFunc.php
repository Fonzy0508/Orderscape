<?php
include("../../Connections.php");
$query = "select * from food";
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_assoc($result)) {
?>
    <div class="card text-start m-2 col-xl-2 col-md-3 col-sm-4 col-12 ms-sm-5 ms-md-4 float-start" onclick="GetFoodInfos(this)" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <img class="card-img-top" src="" alt="Title">
        
        <div class="card-body">
            <h6 class="card-title float-start"><?php echo $row['FoodName'] ?></h6>
            <h6 class="float-end">P<?php echo $row['FoodPrice'] ?></h6>
            <h6 class="d-none"><?php echo $row['FoodID'] ?></h6>
        </div>
    </div>
<?php
}
?>