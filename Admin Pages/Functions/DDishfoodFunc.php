 <?php
    include("../../Connections.php");
    $queryD = "select * from food where Category = 'Dish'";
    $resultD = mysqli_query($con, $queryD);
    while ($row = mysqli_fetch_assoc($resultD)) {
    ?>
     <div class="card text-start m-2 col-xl-2 col-md-3 col-sm-4 col-12 ms-sm-5 ms-md-4 float-start">
         <img class="card-img-top" src="" alt="FoodIMG">
         <div class="card-body p-1 d-flex flex-wrap">
             <h6 class="d-none" id="<?php echo "Food" . $row['FoodID'] ?>"><?php echo "Food" . $row['FoodID'] ?></h6>
             <h6 class=" col-8"><?php echo $row['FoodName'] ?></h6>
             <h6 class="col-4">P<?php echo $row['FoodPrice'] ?></h6>
             <div class="input-group has-validation p-2">
                 <span class="input-group-text" id="inputGroupPrepend3">Q</span>
                 <input type="number" value="1" class="form-control" id="FoodPricea" name="Food_Price" aria-describedby="validationServerPriceFeedback" required>
                 <div id="validationServerPriceFeedback" class="invalid-feedback">
                     Please input Food price.
                 </div>
             </div>
             <div class="col-12 p-2">
                 <button class="btn col-12" style="background-color: #ecdb9c !important; " onclick="delf(this)">Add</button>
                 <span class="d-none"><?php echo $row['FoodID'] ?></span>
             </div>

         </div>
     </div>
 <?php
    }
    ?>