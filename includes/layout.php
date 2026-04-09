<?php include("auth.php"); ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>

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
            right: 0;
            top: 0;
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

        /* Content */
        .content {
            margin-right: 270px;
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
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4><i class="fa fa-car"></i> لوحة التحكم</h4>

    <a href="/dashboard.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='dashboard.php') echo 'active'; ?>">
        <i class="fa fa-home"></i> الرئيسية
    </a>

    <a href="/drivers/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'drivers')) echo 'active'; ?>">
        <i class="fa fa-user"></i> السائقين
    </a>

    <a href="/vehicles/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'vehicles')) echo 'active'; ?>">
        <i class="fa fa-car"></i> المركبات
    </a>

    <a href="/expenses/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'expenses')) echo 'active'; ?>">
        <i class="fa fa-gas-pump"></i> البنزين والصيانة
    </a>

    <a href="/reports/index.php" class="<?php if(basename($_SERVER['PHP_SELF'])=='index.php' && strpos($_SERVER['PHP_SELF'],'reports')) echo 'active'; ?>">
        <i class="fa fa-chart-bar"></i> التقارير
    </a>

    <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fa fa-sign-out"></i> تسجيل خروج
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
    <div>
        <i class="fa fa-user-circle me-2"></i> مرحباً، <?php echo $_SESSION['admin']; ?>
    </div>
    <div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal" class="btn btn-light btn-sm" style="border-radius:10px;">
            <i class="fa fa-sign-out"></i> تسجيل خروج
        </a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title" id="logoutModalLabel">تأكيد الخروج</h5>
        <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        هل أنت متأكد أنك تريد تسجيل الخروج؟
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <a href="/fleet/login/logout.php" class="btn btn-danger">نعم، تسجيل الخروج</a>
      </div>

    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>