<?php
include("../includes/db.php");

$iqamas   = $_POST['iqama'];
$names    = $_POST['driver_name'];
$licenses = $_POST['license_number'];
$types    = $_POST['driver_type'];
$salaries = $_POST['salary'];

for($i = 0; $i < count($iqamas); $i++){

    $iqama   = mysqli_real_escape_string($conn, $iqamas[$i]);
    $name    = mysqli_real_escape_string($conn, $names[$i]);
    $license = mysqli_real_escape_string($conn, $licenses[$i]);
    $type    = $types[$i];
    $salary  = !empty($salaries[$i]) ? $salaries[$i] : NULL;

    if(empty($iqama) || empty($name) || empty($license)) continue;

    mysqli_query($conn, "
        INSERT INTO drivers 
        (iqama_number, driver_name, license_number, driver_type, salary)
        VALUES 
        ('$iqama','$name','$license','$type','$salary')
    ");
}

echo "success";