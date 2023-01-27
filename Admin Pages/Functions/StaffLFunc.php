<?php
include('../../Connections.php');
$query = "SELECT * FROM accounts WHERE Account_Type = 'Staff' OR Account_Type = 'Admin' ORDER BY Account_Type";
$result = mysqli_query($con, $query);
while ($Acc = mysqli_fetch_assoc($result)) {
?>
    <div class="col-12" data-bs-toggle="modal" data-bs-target="#AddAccmodal" onclick="GetData(this)">
        <span class="d-none">
            <span><?php echo $Acc['AccountID'] ?></span>
            <span><?php echo $Acc['Account_Type'] ?></span>
            <span><?php echo $Acc['FirstName'] ?></span>
            <span><?php echo $Acc['MiddleName'] ?></span>
            <span><?php echo $Acc['LastName'] ?></span>
            <span><?php echo $Acc['PhoneNumber'] ?></span>
            <span><?php echo $Acc['CourseOrPosition'] ?></span>
            <span><?php echo $Acc['Email'] ?></span>
            <span><?php echo $Acc['Password'] ?></span>
        </span>
        <div class="col-12 d-flex flex-wrap text-center mb-1 rounded-4 text-white p-2" style="background: rgba(127, 106, 108,.5);">
            <div class="col-6 position-relative">
                <input class="form-check-input position-absolute border-3 rounded-4" style="top: -2px; left: 10px; background-color: inherit;" type="checkbox" value="ACCIDHERE" id="flexCheckDefault">
                <!--Name of the staff-->
                <h6 class="m-0"><?php echo $Acc['FirstName'] . " " . $Acc['MiddleName'] . " " . $Acc['LastName'] ?></h6>
            </div>
            <div class="col-6">
                <!--Position of the staff-->
                <h6 class="m-0"><?php echo $Acc['CourseOrPosition'] ?></h6>
            </div>
        </div>
        <hr class="m-auto mb-2 text-center " style="width: 98%;">
    </div>
<?php } ?>