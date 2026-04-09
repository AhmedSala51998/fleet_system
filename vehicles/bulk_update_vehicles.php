<?php
include("../includes/db.php");

$ids = $_POST['id'];
$types = $_POST['type'];
$models = $_POST['model'];
$ownerships = $_POST['ownership'];
$plates = $_POST['plate'];

for($i=0; $i<count($ids); $i++){

    $imageName = "";

    if(isset($_FILES['image']['name'][$i]) && $_FILES['image']['name'][$i] != ""){

        $tmp = $_FILES['image']['tmp_name'][$i];
        $imageName = time() . "_" . $_FILES['image']['name'][$i];

        move_uploaded_file($tmp, "../uploads/".$imageName);

        mysqli_query($conn,"
            UPDATE vehicles 
            SET 
                vehicle_type='{$types[$i]}',
                vehicle_model='{$models[$i]}',
                ownership='{$ownerships[$i]}',
                plate_number='{$plates[$i]}',
                registration_image='{$imageName}'
            WHERE id='{$ids[$i]}'
        ");

    } else {

        // بدون تغيير الصورة
        mysqli_query($conn,"
            UPDATE vehicles 
            SET 
                vehicle_type='{$types[$i]}',
                vehicle_model='{$models[$i]}',
                ownership='{$ownerships[$i]}',
                plate_number='{$plates[$i]}'
            WHERE id='{$ids[$i]}'
        ");
    }
}

echo "success";