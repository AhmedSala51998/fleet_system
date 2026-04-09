<?php
include("../includes/db.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $driver_ids = $_POST['driver_id'];
    $service_types = $_POST['service_type'];
    $amounts = $_POST['amount'];
    $problems = $_POST['problem_description'];
    $created_ats = $_POST['created_at'];

    // معالجة الملفات
    $files = $_FILES['invoice_image'];

    for($i=0; $i<count($driver_ids); $i++){

        $driver_id = $driver_ids[$i];
        $service_type = $service_types[$i];
        $amount = $amounts[$i];
        $problem = !empty($problems[$i]) ? $problems[$i] : NULL;
        $created_at = $created_ats[$i];

        // رفع الصورة إذا موجودة
        $invoice_image = NULL;
        if(!empty($files['name'][$i])){
            $tmp_name = $files['tmp_name'][$i];
            $filename = time()."_".$files['name'][$i];
            move_uploaded_file($tmp_name, "../uploads/".$filename);
            $invoice_image = $filename;
        }

        // إدخال في قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO expenses (driver_id, service_type, amount, problem_description, invoice_image,created_at) VALUES (?, ?, ?, ?, ?,?)");
        $stmt->bind_param("isdsss", $driver_id, $service_type, $amount, $problem, $invoice_image, $created_at);
        $stmt->execute();
    }

    echo "success";
}
?>