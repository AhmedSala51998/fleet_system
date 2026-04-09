<?php
include("../includes/db.php");
include("../includes/layout.php");

// فلترة بالشهر
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$driver_id = isset($_GET['driver_id']) ? $_GET['driver_id'] : null;

// إجمالي التكاليف
$totalQ = mysqli_query($conn,"
    SELECT service_type, SUM(amount) as total
    FROM expenses
    WHERE DATE_FORMAT(created_at,'%Y-%m') = '$month'
    GROUP BY service_type
");

$totals = [
    'fuel'=>0,
    'maintenance'=>0,
    'internet'=>0,
    'other'=>0
];

while($t = mysqli_fetch_assoc($totalQ)){
    $totals[$t['service_type']] = $t['total'];
}

// بيانات السائقين
$driver_filter = isset($_GET['driver_id']) && $_GET['driver_id'] != '' ? " AND d.id=".intval($_GET['driver_id']) : "";

$q = mysqli_query($conn,"
    SELECT d.id, d.driver_name,
    SUM(CASE WHEN e.service_type='fuel' THEN e.amount ELSE 0 END) as fuel,
    SUM(CASE WHEN e.service_type='maintenance' THEN e.amount ELSE 0 END) as maintenance,
    SUM(CASE WHEN e.service_type='internet' THEN e.amount ELSE 0 END) as internet,
    SUM(CASE WHEN e.service_type='other' THEN e.amount ELSE 0 END) as other
    FROM drivers d
    LEFT JOIN expenses e ON d.id = e.driver_id
    AND DATE_FORMAT(e.created_at,'%Y-%m') = '$month'
    WHERE 1 $driver_filter
    GROUP BY d.id
");
?>

<div class="card shadow-lg p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-purple">
            <i class="fa fa-chart-bar"></i> تقارير السائقين
        </h3>

        <div class="d-flex gap-2">

            <input type="month" id="filterMonth" value="<?php echo $month; ?>" class="form-control">

            <select id="filterDriver" class="form-control">
                <option value="">كل السائقين</option>
                <?php
                $drivers_list = mysqli_query($conn,"SELECT id, driver_name FROM drivers");
                while($drv = mysqli_fetch_assoc($drivers_list)){
                    $selected = (isset($_GET['driver_id']) && $_GET['driver_id']==$drv['id']) ? 'selected' : '';
                    echo "<option value='{$drv['id']}' $selected>{$drv['driver_name']}</option>";
                }
                ?>
            </select>

            <a id="exportExcel" href="export_excel.php?month=<?php echo $month; ?>&driver_id=<?php echo $driver_id; ?>" class="btn btn-success">
                <i class="fa fa-file-excel"></i>
            </a>

            <a id="exportPDF" href="export_pdf.php?month=<?php echo $month; ?>&driver_id=<?php echo $driver_id; ?>" class="btn btn-danger">
                <i class="fa fa-file-pdf"></i>
            </a>

        </div>
    </div>

    <!-- CARDS -->
    <div class="row mb-4 text-center">

        <div class="col-md-3">
            <div class="stat-card bg-blue">
                <h6>بنزين</h6>
                <h4><?php echo $totals['fuel']; ?> ريال</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-orange">
                <h6>صيانة</h6>
                <h4><?php echo $totals['maintenance']; ?> ريال</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-green">
                <h6>انترنت</h6>
                <h4><?php echo $totals['internet']; ?> ريال</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-red">
                <h6>أخرى</h6>
                <h4><?php echo $totals['other']; ?> ريال</h4>
            </div>
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table id="reportTable" class="table table-bordered table-hover text-center align-middle">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>السائق</th>
                    <th>بنزين</th>
                    <th>صيانة</th>
                    <th>انترنت</th>
                    <th>أخرى</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>

            <tbody>
                <?php while($row = mysqli_fetch_assoc($q)){ 
                    $total = $row['fuel'] + $row['maintenance'] + $row['internet'] + $row['other'];
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['driver_name']; ?></td>
                    <td><?php echo $row['fuel']; ?></td>
                    <td><?php echo $row['maintenance']; ?></td>
                    <td><?php echo $row['internet']; ?></td>
                    <td><?php echo $row['other']; ?></td>
                    <td><b><?php echo $total; ?></b></td>
                </tr>
                <?php } ?>
            </tbody>

        </table>
    </div>

</div>

<style>

/* Header */
.text-purple {
    color: #6f42c1;
}

/* Cards */
.stat-card {
    padding: 20px;
    border-radius: 15px;
    color: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.bg-blue { background: linear-gradient(45deg,#4e73df,#224abe); }
.bg-green { background: linear-gradient(45deg,#1cc88a,#13855c); }
.bg-orange { background: linear-gradient(45deg,#f6c23e,#dda20a); }
.bg-red { background: linear-gradient(45deg,#e74a3b,#be2617); }

/* Table */
#reportTable thead {
    background: linear-gradient(45deg,#6f42c1,#a374d1);
    color: #fff;
}

#reportTable tbody tr:hover {
    background: rgba(111,66,193,0.1);
}

</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toast -->
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
$(document).ready(function(){

    $('#reportTable').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json"
        }
    });

    // فلترة بالشهر أو السائق
    $('#filterMonth, #filterDriver').change(function(){
        let m = $('#filterMonth').val();
        let d = $('#filterDriver').val();
        let url = "?month=" + m;
        if(d) url += "&driver_id=" + d;
        window.location = url;
    });

});
</script>