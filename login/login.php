<?php
session_start();
include("../includes/db.php");

$error = "";

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = MD5($_POST['password']);

    $q = mysqli_query($conn,"SELECT * FROM admins WHERE username='$username' AND password='$password'");
    
    if(mysqli_num_rows($q) > 0){
        $_SESSION['admin'] = $username;
        header("Location: ../dashboard.php");
        exit;
    }else{
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #4e73df, #224abe);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Tajawal', sans-serif;
        }

        .login-card {
            width: 400px;
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
        }

        .login-card h3 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: bold;
            color: #4e73df;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
        }

        .btn-login {
            background: #4e73df;
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            width: 100%;
            font-size: 18px;
        }

        .btn-login:hover {
            background: #224abe;
            color:#FFF
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        .logo {
            text-align: center;
            font-size: 40px;
            color: #4e73df;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="login-card">
    <div class="logo">
        <i class="fa fa-user-shield"></i>
    </div>

    <h3>تسجيل دخول الأدمن</h3>

    <?php if($error != ""){ ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="POST">

        <div class="mb-3">
            <label>اسم المستخدم</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span>
                <input type="text" name="username" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label>كلمة المرور</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <button name="login" class="btn btn-login">تسجيل الدخول</button>

    </form>
</div>

</body>
</html>