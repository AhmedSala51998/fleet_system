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
while($d = mysqli_fetch_assoc($drivers_q)){
    $drivers[] = $d;
}

// ===== بناء الـ HTML =====
$html = "<style>
body{font-family:Arial, sans-serif;font-size:10px;}
table{border-collapse:collapse;width:100%;margin-top:10px;table-layout:auto;}
th, td{border:1px solid #000;padding:4px;text-align:center;word-wrap:break-word; white-space: nowrap;}
th{background:#f2f2f2;}
h3{text-align:center;}
small{font-size:8px;}

/* للطباعة */
@media print {
    table { page-break-inside: auto; }
    tr    { page-break-inside: avoid; page-break-after: auto; }
    td, th { white-space: normal; } /* السماح للكلمات بالانكسار */
}
</style>";

$html .= "<h3>Drivers Report - $month</h3>";
$html .= "<table>";

// Header بيانات السائقين
$html .= "<tr><td>Drivers Data</td>";
foreach($drivers as $d){
    $html .= "<td colspan='4' style='background:#d9edf7'>{$d['driver_name']}</td>";
}
$html .= "</tr>";

$html .= "<tr><td></td>";
foreach($drivers as $d){
    $html .= "<td>Iqama</td><td>Code</td><td>Mobile</td><td></td>";
}
$html .= "</tr>";

$html .= "<tr><td></td>";
foreach($drivers as $d){
    $html .= "<td>{$d['iqama_number']}</td><td>{$d['id']}</td><td>-</td><td></td>";
}
$html .= "</tr>";

// Header الأيام والخدمات
$html .= "<tr><th>Days</th>";
foreach($drivers as $d){
    $html .= "<th>Gasoline</th><th>Maintenance</th><th>Internet</th><th>Other</th>";
}
$html .= "</tr>";

// بيانات كل يوم
$totals = [];
for($day=1; $day<=31; $day++){
    $html .= "<tr><td>$day</td>";
    
    foreach($drivers as $d){
        $date = $month . '-' . str_pad($day,2,'0',STR_PAD_LEFT);
        
        // جلب المصروفات لكل خدمة مع التفاصيل
        $res = mysqli_query($conn,"
            SELECT service_type, SUM(amount) as total, GROUP_CONCAT(problem_description SEPARATOR ', ') as details
            FROM expenses
            WHERE driver_id = {$d['id']}
            AND DATE(created_at)='$date'
            GROUP BY service_type
        ");

        $data = [
            'fuel'=>0,'maintenance'=>0,'internet'=>0,'other'=>0,
            'fuel_desc'=>'','maintenance_desc'=>'','internet_desc'=>'','other_desc'=>''
        ];
        while($r = mysqli_fetch_assoc($res)){
            $data[$r['service_type']] = $r['total'];
            if($r['service_type'] == 'fuel') $data['fuel_desc'] .= $r['details'] . "; ";
            if($r['service_type'] == 'maintenance') $data['maintenance_desc'] .= $r['details'] . "; ";
            if($r['service_type'] == 'internet') $data['internet_desc'] .= $r['details'] . "; ";
            if($r['service_type'] == 'other') $data['other_desc'] .= $r['details'] . "; ";
        }

        // عرض الرقم + التفاصيل لكل خدمة
        $html .= "<td>{$data['fuel']}<br><small>{$data['fuel_desc']}</small></td>
                  <td>{$data['maintenance']}<br><small>{$data['maintenance_desc']}</small></td>
                  <td>{$data['internet']}<br><small>{$data['internet_desc']}</small></td>
                  <td>{$data['other']}<br><small>{$data['other_desc']}</small></td>";

        // تجميع الإجماليات
        if(!isset($totals[$d['id']])) $totals[$d['id']] = ['fuel'=>0,'maintenance'=>0,'internet'=>0,'other'=>0];
        $totals[$d['id']]['fuel'] += $data['fuel'];
        $totals[$d['id']]['maintenance'] += $data['maintenance'];
        $totals[$d['id']]['internet'] += $data['internet'];
        $totals[$d['id']]['other'] += $data['other'];
    }
    $html .= "</tr>";
}

// Total لكل سائق لكل خدمة
$html .= "<tr style='background:#f2dede'><td>Total</td>";
foreach($drivers as $d){
    $html .= "<td>{$totals[$d['id']]['fuel']}</td>
              <td>{$totals[$d['id']]['maintenance']}</td>
              <td>{$totals[$d['id']]['internet']}</td>
              <td>{$totals[$d['id']]['other']}</td>";
}
$html .= "</tr>";

// Total شامل لكل سائق
$html .= "<tr style='background:#dff0d8'><td>Total All Services</td>";
foreach($drivers as $d){
    $sum = $totals[$d['id']]['fuel'] 
         + $totals[$d['id']]['maintenance'] 
         + $totals[$d['id']]['internet'] 
         + $totals[$d['id']]['other'];
    $html .= "<td colspan='4'>$sum</td>";
}
$html .= "</tr>";

$html .= "</table>";

// طباعة التقرير
echo $html;
?>