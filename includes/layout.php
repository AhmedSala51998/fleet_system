<?php 
include("auth.php");

session_start();

// تغيير اللغة
if(isset($_GET['lang'])){
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'ar';

$trans = [

    'ar' => [
        'dashboard' => 'لوحة التحكم',
        'home' => 'الرئيسية',
        'drivers' => 'السائقين',
        'vehicles' => 'المركبات',
        'expenses' => 'البنزين والصيانة',
        'reports' => 'التقارير',
        'logout' => 'تسجيل خروج',
        'welcome' => 'مرحباً',
        'confirm_logout' => 'تأكيد الخروج',
        'logout_msg' => 'هل أنت متأكد أنك تريد تسجيل الخروج؟',
        'cancel' => 'إلغاء',
        'yes_logout' => 'نعم، تسجيل الخروج'
    ],

    'en' => [
        'dashboard' => 'Dashboard',
        'home' => 'Home',
        'drivers' => 'Drivers',
        'vehicles' => 'Vehicles',
        'expenses' => 'Fuel & Maintenance',
        'reports' => 'Reports',
        'logout' => 'Logout',
        'welcome' => 'Welcome',
        'confirm_logout' => 'Confirm Logout',
        'logout_msg' => 'Are you sure you want to logout?',
        'cancel' => 'Cancel',
        'yes_logout' => 'Yes, Logout'
    ]
];

$t = $trans[$lang];
?>

<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $lang=='ar' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $t['dashboard']; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f1f4f9;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            background: linear-gradient(180deg, #4e73df, #224abe);
            color: #fff;
            padding-top: 20px;

            <?php echo $lang=='ar' ? 'right:0;' : 'left:0;'; ?>
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 15px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            border-radius: 8px;
            margin: 5px 10px;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
        }

        .sidebar a.active {
            background: #fff;
            color: #224abe;
            font-weight: bold;
        }

        .content {
            <?php echo $lang=='ar' ? 'margin-right:270px;' : 'margin-left:270px;'; ?>
            padding: 20px;
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fa fa-car"></i> <?php echo $t['dashboard']; ?></h4>

    <a href="/dashboard.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php') echo 'active'; ?>">
        <i class="fa fa-home"></i> <?php echo $t['home']; ?>
    </a>

    <a href="/drivers/index.php">
        <i class="fa fa-user"></i> <?php echo $t['drivers']; ?>
    </a>

    <a href="/vehicles/index.php">
        <i class="fa fa-car"></i> <?php echo $t['vehicles']; ?>
    </a>

    <a href="/expenses/index.php">
        <i class="fa fa-gas-pump"></i> <?php echo $t['expenses']; ?>
    </a>

    <a href="/reports/index.php">
        <i class="fa fa-chart-bar"></i> <?php echo $t['reports']; ?>
    </a>

    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fa fa-sign-out"></i> <?php echo $t['logout']; ?>
    </a>

    <!-- Language Switch -->
    <div class="text-center mt-3">
        <a href="?lang=ar" class="btn btn-sm btn-light">AR</a>
        <a href="?lang=en" class="btn btn-sm btn-dark">EN</a>
    </div>
</div>

<!-- Content -->
<div class="content">

<div class="topbar d-flex justify-content-between align-items-center mb-4" style="
    background: linear-gradient(90deg, #4e73df, #224abe);
    color: #fff;
    padding: 12px 25px;
    border-radius: 12px;
    font-weight: bold;
">
    <div>
        <i class="fa fa-user-circle me-2"></i>
        <?php echo $t['welcome']; ?>, <?php echo $_SESSION['admin']; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title"><?php echo $t['confirm_logout']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <?php echo $t['logout_msg']; ?>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
            <?php echo $t['cancel']; ?>
        </button>
        <a href="/login/logout.php" class="btn btn-danger">
            <?php echo $t['yes_logout']; ?>
        </a>
      </div>

    </div>
  </div>
</div>

</div>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>