<?php
include("../includes/db.php");

$ids     = $_POST['id'];
$iqamas  = $_POST['iqama'];
$names   = $_POST['name'];
$licenses= $_POST['license'];
$types   = $_POST['type'];
$salaries= $_POST['salary'];

for($i=0; $i<count($ids); $i++){

    $id      = $ids[$i];
    $iqama   = mysqli_real_escape_string($conn,$iqamas[$i]);
    $name    = mysqli_real_escape_string($conn,$names[$i]);
    $license = mysqli_real_escape_string($conn,$licenses[$i]);
    $type    = $types[$i];
    $salary  = !empty($salaries[$i]) ? $salaries[$i] : NULL;

    mysqli_query($conn,"
        UPDATE drivers SET
            iqama_number='$iqama',
            driver_name='$name',
            license_number='$license',
            driver_type='$type',
            salary='$salary'
        WHERE id='$id'
    ");
}

echo "success";