<?php
include("../../Connections.php");
$queryNumC = "SELECT * FROM (SELECT ohistory.OH_ID, ohistory.OrderID, ohistory.Order_Price, ohistory.Date, ohistory.Time, ohistory.State, ohistory.AccountID, ohistory.Msg, accounts.Account_Type
FROM ohistory INNER JOIN accounts ON ohistory.AccountID = accounts.AccountID)tb WHERE tb.State = 'Cancelled' AND Account_Type = 'Customer'";
$resultCancell = mysqli_query($con, $queryNumC);
while ($row = mysqli_fetch_assoc($resultCancell)) {
    $ts = strtotime($row['Date']);
    $FD = date('m/j/Y', $ts);
    $tsT = strtotime($row['Time']);
    $FT = date('h:i A', $tsT);
?>
    <div class="col-12 p-2 mb-2 rounded-4 d-flex flex-wrap" style="background-color:#d1a561;">
        <span class="col-6 p-1 fw-bold"><?php echo $row['OrderID']; ?></span>
        <span class="col-6 p-1 d-flex justify-content-end">P<?php echo $row['Order_Price']; ?></span>
        <span class="col-4 p-1 fw-bold"><?php echo $row['AccountID']; ?></span>
        <span class="col-8 p-1 d-flex flex-wrap justify-content-end">
            <span class="fw-bold col-12 text-end"><?php echo $row['State'] . " : " .  $FT . " " . $FD ?></span>
        </span>
        <span class="col-12"><?php echo str_replace('Your', 'The', $row['Msg']); ?></span>
    </div>
<?php } ?>