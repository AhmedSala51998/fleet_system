<?php 
include("auth.php");
include("lang.php");

session_start();

if(isset($_GET['lang'])){
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $_SESSION['lang'] ?? 'ar';
$t = $trans[$lang];
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo ($lang == 'ar') ? 'rtl' : 'ltr'; ?>">
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

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;

            <?php echo ($lang == 'ar') ? 'right:0;' : 'left:0;'; ?>

            background: linear-gradient(180deg, #4e73df, #224abe);
            color: #fff;
            padding-top: 20px;
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
            font-size: 16px;
            border-radius: 8px;
            margin: 5px 10px;
        }

        .sidebar a i {
            margin-left: 10px; /* الأيقونة على اليمين */
        }

        /* Hover */
        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
            padding-right: 25px;
        }

        /* Active */
        .sidebar a.active {
            background: #fff;
            color: #224abe;
            font-weight: bold;
            padding-right: 25px;
        }

        .sidebar a.active i {
            color: #224abe;
        }

        .sidebar a i {
            <?php echo ($lang == 'ar') ? 'margin-left:10px;' : 'margin-right:10px;'; ?>
        }

        /* Content */
        .content {
            <?php echo ($lang == 'ar') ? 'margin-right:270px;' : 'margin-left:270px;'; ?>
            padding: 20px;
        }

        /* Navbar */
        .topbar {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ddd;
            margin-bottom: 20px;
        }

        /* Cards */
        .stat-card {
            padding: 20px;
            border-radius: 15px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 40px;
            position: absolute;
            left: 20px;
            bottom: 20px;
            opacity: 0.3;
        }

        .bg-blue { background: linear-gradient(45deg,#4e73df,#224abe); }
        .bg-green { background: linear-gradient(45deg,#1cc88a,#13855c); }
        .bg-orange { background: linear-gradient(45deg,#f6c23e,#dda20a); }
        .bg-red { background: linear-gradient(45deg,#e74a3b,#be2617); }

        .card-box {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px #ddd;
        }

            .stat-card {
                padding: 20px;
                border-radius: 15px;
                color: #fff;
                position: relative;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                transition: 0.3s;

                text-align: <?php echo ($lang=='ar') ? 'right' : 'left'; ?>;
            }
            .stat-card i {
                font-size: 40px;
                position: absolute;
                bottom: 20px;
                opacity: 0.3;

                <?php echo ($lang=='ar') ? 'left:20px;' : 'right:20px;'; ?>
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

    <a href="/drivers/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'drivers')) echo 'active'; ?>">
        <i class="fa fa-user"></i> <?php echo $t['drivers']; ?>
    </a>

    <a href="/vehicles/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'vehicles')) echo 'active'; ?>">
        <i class="fa fa-car"></i> <?php echo $t['vehicles']; ?>
    </a>

    <a href="/expenses/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'expenses')) echo 'active'; ?>">
        <i class="fa fa-gas-pump"></i> <?php echo $t['expenses']; ?>
    </a>

    <a href="/reports/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'reports')) echo 'active'; ?>">
        <i class="fa fa-chart-bar"></i> <?php echo $t['reports']; ?>
    </a>

    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fa fa-sign-out"></i> <?php echo $t['logout']; ?>
    </a>
</div>

<!-- Content -->
<div class="content">

<div class="topbar d-flex justify-content-between align-items-center mb-4" style="
    background: linear-gradient(90deg, #4e73df, #224abe);
    color: #fff;
    padding: 12px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    font-weight: bold;
    font-size: 18px;
">
    
    <!-- Left / Welcome -->
    <div>
        <i class="fa fa-user-circle me-2"></i>
        <?php echo $t['welcome']; ?>, <?php echo $_SESSION['admin']; ?>
    </div>

    <!-- Right / Actions -->
    <div class="d-flex align-items-center gap-2">

        <!-- Language Switch -->
        <div class="btn-group" role="group" style="border-radius:10px; overflow:hidden;">
            <a href="?lang=ar" class="btn btn-sm btn-light">AR</a>
            <a href="?lang=en" class="btn btn-sm btn-dark">EN</a>
        </div>

        <!-- Logout -->
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" 
           class="btn btn-light btn-sm" style="border-radius:10px;">
            <i class="fa fa-sign-out"></i> <?php echo $t['logout']; ?>
        </a>

    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title" id="logoutModalLabel"><?php echo $t['confirm_logout']; ?></h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <?php echo $t['logout_msg']; ?>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $t['cancel']; ?></button>
        <a href="/login/logout.php" class="btn btn-danger"><?php echo $t['yes_logout']; ?></a>
      </div>

    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>