<?php 
include("includes/db.php");
include("includes/layout.php");

// ===== فلترة الشهر =====
$month = $_GET['month'] ?? date('Y-m');

// ===== تحديد بداية ونهاية الشهر (أسرع من DATE_FORMAT) =====
$start = $month . "-01";
$end = date("Y-m-t", strtotime($start));

// ===== العدادات (مربوطة بالشهر) =====
$drivers = mysqli_num_rows(mysqli_query($conn,"
    SELECT * FROM drivers 
    WHERE created_at BETWEEN '$start' AND '$end'
"));

$vehicles = mysqli_num_rows(mysqli_query($conn,"
    SELECT * FROM vehicles 
    WHERE created_at BETWEEN '$start' AND '$end'
"));

// ===== المصروفات حسب النوع =====
$fuel = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(amount) as total 
    FROM expenses 
    WHERE service_type='fuel' 
    AND created_at BETWEEN '$start' AND '$end'
"))['total'] ?? 0;

$maintenance = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(amount) as total 
    FROM expenses 
    WHERE service_type='maintenance' 
    AND created_at BETWEEN '$start' AND '$end'
"))['total'] ?? 0;

$internet = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(amount) as total 
    FROM expenses 
    WHERE service_type='internet' 
    AND created_at BETWEEN '$start' AND '$end'
"))['total'] ?? 0;

$other = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT SUM(amount) as total 
    FROM expenses 
    WHERE service_type='other' 
    AND created_at BETWEEN '$start' AND '$end'
"))['total'] ?? 0;
?>

<h3>الرئيسية</h3>

<!-- فلتر الشهر -->
<form method="get" class="mb-4">
    <div class="card p-3 shadow-sm border-0">
        <div class="row align-items-center">

            <div class="col-md-4">
                <label class="form-label fw-bold">📅 اختر الشهر</label>
                <input type="month" 
                       name="month" 
                       class="form-control form-control-lg"
                       value="<?php echo $month; ?>"
                       onchange="this.form.submit()">
            </div>

            <div class="col-md-3 mt-3 mt-md-0">
                <a href="?" class="btn btn-outline-secondary w-100 btn-lg">
                    ♻️ إعادة تعيين
                </a>
            </div>

        </div>
    </div>
</form>

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