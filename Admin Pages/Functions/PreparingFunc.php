<?php
include("../../Connections.php");
$query = "SELECT * FROM orders WHERE orders.Status = 'Preparing'";
$result = mysqli_query($con, $query);
while ($Orows = mysqli_fetch_assoc($result)) {
?>
    <a href="Order_Page.php" style="text-decoration: none; color:white;">
        <div class="col-12 p-2 d-flex flex-wrap align-content-start mb-2 ps-4 NOH  rounded-5" style="background: rgba(127,106,108,.5); height: 90px;">
            <span class="col-12 fs-3 fw-bold">Order <?php echo $Orows['OrderID'] ?></span>
            <div class="col-12 d-flex justify-content-between align-items-end pe-3">
                <span class="fs-6"><?php echo $Orows['AccountID'] ?></span>
                <span class="fs-5">P<?php echo $Orows['OrderPrice'] ?></span>
            </div>
        </div>
    </a>

<?php } ?>