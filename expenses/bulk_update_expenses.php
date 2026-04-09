<?php
include("../includes/db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $ids = $_POST['id'];
    $driver_ids = $_POST['driver_id'];
    $service_types = $_POST['service_type'];
    $amounts = $_POST['amount'];
    $problems = $_POST['problem_description'];
    $created_ats = $_POST['created_at'];

    $files = $_FILES['invoice_image'];

    for($i=0; $i<count($ids); $i++){

        $id = $ids[$i];
        $driver_id = $driver_ids[$i];
        $service_type = $service_types[$i];
        $amount = $amounts[$i];
        $problem = !empty($problems[$i]) ? $problems[$i] : NULL;
        $created_at = $created_ats[$i];

        // رفع الصورة إذا تم اختيار ملف جديد
        $invoice_image_sql = "";
        if(!empty($files['name'][$i])){
            $tmp_name = $files['tmp_name'][$i];
            $filename = time()."_".$files['name'][$i];
            move_uploaded_file($tmp_name, "../uploads/".$filename);
            $invoice_image_sql = ", invoice_image='$filename'";
        }

        $sql = "UPDATE expenses SET driver_id='$driver_id', service_type='$service_type', amount='$amount', problem_description='$problem' $invoice_image_sql, created_at='$created_at' WHERE id='$id'";
        $conn->query($sql);
    }

    echo "success";
}
?>