<?php 
include("includes/db.php");
include("includes/layout.php");

// ===== فلترة الشهر =====
$month = $_GET['month'] ?? date('Y-m'); // افتراضي الشهر الحالي

// ===== العدادات حسب الشهر =====
$drivers = mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT d.id FROM drivers d
    JOIN expenses e ON d.id = e.driver_id
    WHERE DATE_FORMAT(e.created_at,'%Y-%m')='$month'"));

$vehicles = mysqli_num_rows(mysqli_query($conn,"SELECT DISTINCT v.id FROM vehicles v
    JOIN expenses e ON v.id = e.vehicle_id
    WHERE DATE_FORMAT(e.created_at,'%Y-%m')='$month'"));

// المصروفات حسب النوع للشهر المختار
$fuel = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as total FROM expenses WHERE service_type='fuel' AND DATE_FORMAT(created_at,'%Y-%m')='$month'"))['total'] ?? 0;
$maintenance = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as total FROM expenses WHERE service_type='maintenance' AND DATE_FORMAT(created_at,'%Y-%m')='$month'"))['total'] ?? 0;
$internet = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as total FROM expenses WHERE service_type='internet' AND DATE_FORMAT(created_at,'%Y-%m')='$month'"))['total'] ?? 0;
$other = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(amount) as total FROM expenses WHERE service_type='other' AND DATE_FORMAT(created_at,'%Y-%m')='$month'"))['total'] ?? 0;
?>

<!-- فلتر الشهر -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>الرئيسية</h3>
    <form method="get">
        <label>اختر الشهر: </label>
        <input type="month" name="month" value="<?php echo $month; ?>" onchange="this.form.submit()" style="padding:3px; margin-left:5px;">
    </form>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="stat-card bg-blue">
            <h3><?php echo $drivers; ?></h3>
            <p>عدد السائقين</p>
            <i class="fa fa-users"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-green">
            <h3><?php echo $vehicles; ?></h3>
            <p>عدد المركبات</p>
            <i class="fa fa-car"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-orange">
            <h3><?php echo $fuel; ?></h3>
            <p>مصروف البنزين</p>
            <i class="fa fa-gas-pump"></i>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card bg-red">
            <h3><?php echo $maintenance; ?></h3>
            <p>مصروف الصيانة</p>
            <i class="fa fa-tools"></i>
        </div>
    </div>

    <div class="col-md-3 mt-3">
        <div class="stat-card" style="background-color:#6f42c1; color:#fff;">
            <h3><?php echo $internet; ?></h3>
            <p>مصروف الإنترنت</p>
            <i class="fa fa-wifi"></i>
        </div>
    </div>

    <div class="col-md-3 mt-3">
        <div class="stat-card" style="background-color:#20c997; color:#fff;">
            <h3><?php echo $other; ?></h3>
            <p>مصروفات أخرى</p>
            <i class="fa fa-ellipsis-h"></i>
        </div>
    </div>

</div>

<div class="card-box mt-4">
    <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
var ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['بنزين', 'صيانة', 'إنترنت', 'أخرى'],
        datasets: [{
            label: 'المصروفات',
            data: [<?php echo $fuel; ?>, <?php echo $maintenance; ?>, <?php echo $internet; ?>, <?php echo $other; ?>],
            backgroundColor: ['#ffa500','#ff4d4d','#9b59b6','#1abc9c'],
            borderWidth: 1
        }]
    },
    options: {
        responsive:true,
        plugins: { legend:{ display:false } },
        scales: {
            y: { beginAtZero:true }
        }
    }
});
</script>