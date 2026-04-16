<?php
session_start();

// احفظ اللغة قبل ما تمسح السيشن
if (isset($_SESSION['lang'])) {
    $lang = $_SESSION['lang'];
} elseif (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
} else {
    $lang = 'ar';
}

// امسح السيشن (تسجيل خروج)
session_unset();
session_destroy();

// (اختياري) لو عايز تحفظ اللغة بعد اللوجين تاني
session_start();
$_SESSION['lang'] = $lang;

// تحويل مع تمرير اللغة في الرابط
header("Location: login.php?lang=" . urlencode($lang));
exit;