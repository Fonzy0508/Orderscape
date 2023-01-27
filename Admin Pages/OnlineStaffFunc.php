<?php
include('../Connections.php');
$query = "SELECT FirstName, LastName FROM accounts WHERE ActiveStatus = 'true'";
$resultOl = mysqli_query($con, $query);
while ($OLrow = mysqli_fetch_assoc($resultOl)) {
?>
    <div class="col-12 mb-1 d-flex rounded-5 align-items-center" style="background: rgba(127,106,108,.5);">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" fill="#4caf50" class="bi bi-dot" viewBox="0 0 16 16">
            <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
        </svg>
        <span class="fs-6" style="color: #7f6a6c;"><?php echo $OLrow['FirstName'] . " " . $OLrow['LastName'] ?></span>
    </div>
<?php
}
?>