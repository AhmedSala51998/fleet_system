<?php
include("../includes/db.php");
include("../includes/layout.php");
require_once __DIR__.'/../libs/SimpleXLSXGen.php';

$month = $_GET['month'] ?? date('Y-m');
$driver_id = $_GET['driver_id'] ?? '';

// ====== السواقين ======
$where = "";
if($driver_id){
    $where = "WHERE id = $driver_id";
}

$drivers = mysqli_query($conn,"SELECT * FROM drivers $where");


$driverList = [];
while($d = mysqli_fetch_assoc($drivers)){
    $driverList[] = $d;
}

// ====== تجهيز البيانات ======
$data = [];

/* الصف الأول */
$row1 = [$trans[$lang]['drivers_data']];
foreach($driverList as $i=>$d){
    $row1[] = $i+1;
    $row1[] = '';
    $row1[] = '';
    $row1[] = '';
}
$data[] = $row1;

/* Name */
$row2 = [''];
foreach($driverList as $d){
    $row2[] = $trans[$lang]['name'];
    $row2[] = $d['driver_name'];
    $row2[] = '';
    $row2[] = '';
}
$data[] = $row2;

/* Iqama */
$row3 = [''];
foreach($driverList as $d){
    $row3[] = $trans[$lang]['iqama'];
    $row3[] = $d['iqama_number'];
    $row3[] = '';
    $row3[] = '';
}
$data[] = $row3;

/* Code */
$row4 = [''];
foreach($driverList as $d){
    $row4[] = $trans[$lang]['code'];
    $row4[] = $d['id'];
    $row4[] = '';
    $row4[] = '';
}
$data[] = $row4;

/* Mobile */
$row5 = [''];
foreach($driverList as $d){
    $row5[] = $trans[$lang]['mobile'];
    $row5[] = '-';
    $row5[] = '';
    $row5[] = '';
}
$data[] = $row5;

/* Header */
$header = [$trans[$lang]['days'],$trans[$lang]['detail']];
foreach($driverList as $d){
    $header[] = $trans[$lang]['gasoline'];
    $header[] = $trans[$lang]['maintenance'];
    $header[] = $trans[$lang]['internet'];
    $header[] = $trans[$lang]['other'];
}
$data[] = $header;

// ====== الأيام ======
$totals = [];

for($day=1;$day<=31;$day++){

    $row = [$day,''];

    foreach($driverList as $d){

        $date = $month.'-'.str_pad($day,2,'0',STR_PAD_LEFT);

        $q = mysqli_query($conn,"
            SELECT service_type, SUM(amount) total
            FROM expenses
            WHERE driver_id = {$d['id']}
            AND DATE(created_at)='$date'
            GROUP BY service_type
        ");

        $vals = ['fuel'=>0,'maintenance'=>0,'internet'=>0,'other'=>0];

        while($r = mysqli_fetch_assoc($q)){
            $vals[$r['service_type']] = $r['total'];
        }

        $row[] = $vals['fuel'];
        $row[] = $vals['maintenance'];
        $row[] = $vals['internet'];
        $row[] = $vals['other'];

        // totals
        $totals[$d['id']]['fuel'] += $vals['fuel'];
        $totals[$d['id']]['maintenance'] += $vals['maintenance'];
        $totals[$d['id']]['internet'] += $vals['internet'];
        $totals[$d['id']]['other'] += $vals['other'];
    }

    $data[] = $row;
}

// ====== TOTAL ======
$totalRow = [$trans[$lang]['total'],''];

foreach($driverList as $d){

    $fuel = $totals[$d['id']]['fuel'] ?? 0;
    $main = $totals[$d['id']]['maintenance'] ?? 0;
    $net  = $totals[$d['id']]['internet'] ?? 0;
    $oth  = $totals[$d['id']]['other'] ?? 0;

    $totalRow[] = $fuel;
    $totalRow[] = $main;
    $totalRow[] = $net;
    $totalRow[] = $oth;
}

$data[] = $totalRow;

// ====== Grand Total ======
$grand = [$trans[$lang]['grand_total'],''];

foreach($driverList as $d){

    $sum = 
        ($totals[$d['id']]['fuel'] ?? 0) +
        ($totals[$d['id']]['maintenance'] ?? 0) +
        ($totals[$d['id']]['internet'] ?? 0) +
        ($totals[$d['id']]['other'] ?? 0);

    $grand[] = $sum;
    $grand[] = '';
    $grand[] = '';
    $grand[] = '';
}

$data[] = $grand;

// ====== تحميل ======
$xlsx = Shuchkin\SimpleXLSXGen::fromArray($data);
$xlsx->downloadAs($trans[$lang]['drivers_report_file'] . ".xlsx");
exit;