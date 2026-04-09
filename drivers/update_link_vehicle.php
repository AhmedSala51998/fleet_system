<?php
include("../includes/db.php");

$driver_id  = $_POST['driver_id'];
$vehicle_id = !empty($_POST['vehicle_id']) ? $_POST['vehicle_id'] : NULL;

mysqli_query($conn,"
    UPDATE drivers 
    SET vehicle_id = ".($vehicle_id ? "'$vehicle_id'" : "NULL")."
    WHERE id = '$driver_id'
");

echo "success";