<?php
require __DIR__.'/../includes/db.php';

// ===== فلترة الشهر والسائق =====
$month = $_GET['month'] ?? date('Y-m'); // افتراضي الشهر الحالي
$driver_id = $_GET['driver_id'] ?? '';  // افتراضي كل السائقين

// ===== استدعاء السائقين =====
$where = '';
if($driver_id){
    $where = "WHERE id = ".intval($driver_id);
}

$drivers_q = mysqli_query($conn,"SELECT * FROM drivers $where");
$drivers = [];
while($d = mysqli_fetch_assoc($drivers_q)){ // استخدم $drivers_q هنا
    $drivers[] = $d;
}

// ===== بناء الـ HTML =====
$html = "<style>
body{font-family:Arial, sans-serif;font-size:10px;}
table{border-collapse:collapse;width:100%;margin-top:10px;table-layout:auto;}
th, td{border:1px solid #000;padding:4px;text-align:center;word-wrap:break-word; white-space: nowrap;}
th{background:#f2f2f2;}
h3{text-align:center;}

/* للطباعة */
@media print {
    table { page-break-inside: auto; }
    tr    { page-break-inside: avoid; page-break-after: auto; }
    td, th { white-space: normal; } /* السماح للكلمات بالانكسار */
}
</style>";

$html .= "<h3>Drivers Report - $month</h3>";
$html .= "<table>";

// الصف الأول: Drivers Data
$html .= "<tr><td>Drivers Data</td>";
foreach($drivers as $d){
    $html .= "<td>Name</td><td>{$d['driver_name']}</td><td></td><td></td>";
}
$html .= "</tr>";

// الصف الثاني: Iqama
$html .= "<tr><td></td>";
foreach($drivers as $d){
    $html .= "<td>Iqama</td><td>{$d['iqama_number']}</td><td></td><td></td>";
}
$html .= "</tr>";

// الصف الثالث: Code
$html .= "<tr><td></td>";
foreach($drivers as $d){
    $html .= "<td>Code</td><td>{$d['id']}</td><td></td><td></td>";
}
$html .= "</tr>";

// الصف الرابع: Mobile
$html .= "<tr><td></td>";
foreach($drivers as $d){
    $html .= "<td>Mobile</td><td>-</td><td></td><td></td>";
}
$html .= "</tr>";

// Header الأيام والمصروفات
$html .= "<tr><th>Days</th><th>Detail</th>";
foreach($drivers as $d){
    $html .= "<th>Gasoline</th><th>Maintenance</th><th>Internet</th><th>Other</th>";
}
$html .= "</tr>";

// بيانات كل يوم
$totals = [];
for($day=1; $day<=31; $day++){
    $html .= "<tr><td>$day</td><td></td>";
    foreach($drivers as $d){
        $date = $month . '-' . str_pad($day,2,'0',STR_PAD_LEFT);
        $res = mysqli_query($conn,"
            SELECT service_type, SUM(amount) total
            FROM expenses
            WHERE driver_id = {$d['id']}
            AND DATE(created_at)='$date'
            GROUP BY service_type
        ");
        $data = ['fuel'=>0,'maintenance'=>0,'internet'=>0,'other'=>0];
        while($r = mysqli_fetch_assoc($res)){
            $data[$r['service_type']] = $r['total'];
        }

        $html .= "<td>{$data['fuel']}</td>
                  <td>{$data['maintenance']}</td>
                  <td>{$data['internet']}</td>
                  <td>{$data['other']}</td>";

        // تجميع الإجماليات
        if(!isset($totals[$d['id']])){
            $totals[$d['id']] = ['fuel'=>0,'maintenance'=>0,'internet'=>0,'other'=>0];
        }
        $totals[$d['id']]['fuel'] += $data['fuel'];
        $totals[$d['id']]['maintenance'] += $data['maintenance'];
        $totals[$d['id']]['internet'] += $data['internet'];
        $totals[$d['id']]['other'] += $data['other'];
    }
    $html .= "</tr>";
}

// Total لكل سائق
// Total لكل سائق لكل خدمة (موجود عندك)
$html .= "<tr><td>Total</td><td></td>";
foreach($drivers as $d){
    $html .= "<td>{$totals[$d['id']]['fuel']}</td>
              <td>{$totals[$d['id']]['maintenance']}</td>
              <td>{$totals[$d['id']]['internet']}</td>
              <td>{$totals[$d['id']]['other']}</td>";
}
$html .= "</tr>";

// ===== Total شامل لكل الخدمات لكل سائق =====
$html .= "<tr><td>Total All Services</td><td></td>";
foreach($drivers as $d){
    $sum = $totals[$d['id']]['fuel'] 
         + $totals[$d['id']]['maintenance'] 
         + $totals[$d['id']]['internet'] 
         + $totals[$d['id']]['other'];
    $html .= "<td colspan='4'>$sum</td>"; // نجمع الأربع أعمدة في خانة واحدة
}
$html .= "</tr>";

$html .= "</table>";

// ===== طباعة التقرير مباشرة =====
echo $html;
?>
<script>
window.onload = function(){
    window.print();
}
</script>