<?php
include("../../Connections.php");
$queryOrders = "SELECT * FROM (SELECT orders.OrderID, orders.AccountID, orders.OrderPrice, orders.Status, accounts.FirstName, accounts.LastName, accounts.Account_Type, accounts.CourseOrPosition
FROM orders INNER JOIN accounts ON orders.AccountID = accounts.AccountID)tb WHERE Account_Type = 'Customer'";
$resultOrders = mysqli_query($con, $queryOrders);
while ($row = mysqli_fetch_assoc($resultOrders)) {
?>
    <div class="col-12 d-flex">
        <div class=" h-auto d-flex flex-wrap justify-content-center" style="width: 60%;" data-bs-toggle="modal" data-bs-target="#ID<?php echo $row['AccountID'] . $row['OrderID'] ?>">
            <span class="col-6 h-auto"><?php echo $row['OrderID'] ?></span>
            <span class="col-6 h-auto"><?php echo $row['FirstName'] . " " . $row['LastName'] . "  |" . $row['CourseOrPosition'] ?></span>
            <span class="d-none"><?php echo $row['AccountID'] ?></span>
            <hr class="col-12 m-0">
        </div>
        <div class="d-flex justify-content-evenly" style="width: 40%;">
            <form method="POST">
                <input type="number" class="d-none" name="OID" value="<?php echo $row['OrderID'] ?>">
                <input type="number" class="d-none" name="AID" value="<?php echo $row['AccountID'] ?>">
                <input type="number" name="PID" class="d-none" value="<?php echo $row['OrderPrice'] ?>">

                <?php
                if ($row['Status'] == "Preparing") {
                ?>
                    <button type="submit" name="Prepare" class="btn m-1 rounded-5" style="background-color: #d1a561;" id="P<?php echo $row['OrderID'] . $row['AccountID'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" class=" d-block d-md-none bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                        </svg>
                        <span class="d-none d-md-block">Prepared</span>
                    </button>
                <?php
                } elseif ($row['Status'] == "Prepared") {
                ?>
                    <button type="submit" name="SDone" class="btn m-1 rounded-5" style="background-color: #d1a561;" id="S<?php echo $row['OrderID'] . $row['AccountID'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" class=" d-block d-md-none bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                        </svg>
                        <span class="d-none d-md-block">Recieved</span>
                    </button>
                <?php
                }
                ?>
                <!--TODO: add comment why cancelled-->
                <input type="text" class="d-none" name="AMSG" id="In<?php echo $row['OrderID'] . $row['AccountID'] ?>">                
                <button type="button" onclick="getid(this)" data-bs-toggle="modal" data-bs-target="#Alertmsgmodal" class="btn m-1 rounded-5" style="background-color: #d1a561;">
                    <span class="d-none">Sc<?php echo $row['OrderID'] . $row['AccountID'] ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="d-block d-md-none bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                    <span class="d-none d-md-block">Cancel</span>
                </button>
                <button type="submit" name="SCancel" class="d-none btn m-1 rounded-5" style="background-color: #d1a561;" id="Sc<?php echo $row['OrderID'] . $row['AccountID'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="d-block d-md-none bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                    <span class="d-none d-md-block">Cancel</span>
                </button>
            </form>
        </div>
    </div>
<?php } ?>