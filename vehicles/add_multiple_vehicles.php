<?php
include("../includes/db.php");

$types = $_POST['type'];
$models = $_POST['model'];
$ownerships = $_POST['ownership'];
$plates = $_POST['plate'];

for($i=0; $i<count($types); $i++){

    // ======================
    // معالجة الصورة
    // ======================
    $imageName = "";

    if(isset($_FILES['image']['name'][$i]) && $_FILES['image']['name'][$i] != ""){

        $tmp  = $_FILES['image']['tmp_name'][$i];
        $name = time() . "_" . rand(1000,9999) . "_" . $_FILES['image']['name'][$i];

        move_uploaded_file($tmp, "../uploads/".$name);

        $imageName = $name;
    }

    // ======================
    // إدخال البيانات
    // ======================
    mysqli_query($conn,"
        INSERT INTO vehicles (
            vehicle_type,
            vehicle_model,
            ownership,
            plate_number,
            registration_image
        )
        VALUES (
            '{$types[$i]}',
            '{$models[$i]}',
            '{$ownerships[$i]}',
            '{$plates[$i]}',
            '$imageName'
        )
    ");
}

echo "success";