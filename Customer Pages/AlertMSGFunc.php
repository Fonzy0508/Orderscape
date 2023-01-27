<?php
session_start();
$AID = $_SESSION['UserID'];
include("../Connections.php");
$queryAlert = "SELECT AlertMSG FROM accounts WHERE AccountID = $AID";
$resultAlert = mysqli_query($con, $queryAlert);
$row = mysqli_fetch_assoc($resultAlert);
if (isset($row['AlertMSG'])) {
    $C = $row['AlertMSG'];
    if (str_contains($row['AlertMSG'], 'Cancelled')) {
        list($C, $Reason, $MSG) = explode(" : ", $row['AlertMSG']);
    }
?>
    <span class="d-none">5</span>
    <div class="col-12 bg-dark d-flex justify-content-center align-content-center align-items-center h-100 position-absolute" style="z-index: 5 !important; background: rgba(0,0,0, .5) !important;">
        <div class="col-12 m-1 col-md-6 d-flex p-3 rounded-2" style="background-color: #d1a561;">
            <form method="POST" class="col-12 d-flex flex-wrap">
                <div class="col-12">
                    <h5><?php echo  $C ?></h5>
                    <?php if(str_contains($row['AlertMSG'], 'Cancelled')){?>
                        <div class="container">
                            <div class="col-12 d-flex justify-content-between ">
                                <span class=" fw-bold">Cancellation reason: </span>
                                <span><?php echo  $Reason ?></span>
                            </div>
                            <hr class="">
                            <span><?php echo  $MSG ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <input type="submit" value="Okay" class="col-12 btn rounded-4 col-md-6" style="background-color: #ecdb9c;" name="Alertdel">
                </div>
            </form>
        </div>
    </div>
<?php } else {
}
?>