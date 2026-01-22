<?php
// -----------------------------
// Include functions + DB connect
// -----------------------------
require_once 'phpinclude/functions.php';

// Start session ถ้ายังไม่เริ่ม
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------
// จัดการภาษา (EN/TH)
// -----------------------------
$currentLang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['en', 'th']) 
    ? $_COOKIE['lang'] 
    : 'th';

// -----------------------------
// Content (ภาษาไทย / ภาษาอังกฤษ)
// -----------------------------
$announcements_content_th = '
    <h1 data-key="announcements_title">ประกาศ</h1>
    <p class="subtitle" data-key="announcements_subtitle">ข่าวสารและอัปเดตล่าสุดจาก LightTooN</p>
    <hr>
    <h2 data-key="announcements_update_heading">อัปเดตระบบใหม่</h2>
    <p data-key="announcements_update_body">ทีมงาน LightTooN ได้ทำการอัปเดตระบบครั้งใหญ่เพื่อปรับปรุงประสิทธิภาพและความเสถียรของเว็บไซต์</p>
    <h2 data-key="announcements_improvements_heading">การแจ้งเตือนและการปรับปรุง</h2>
    <p data-key="announcements_improvements_body">เรามุ่งมั่นที่จะพัฒนาแพลตฟอร์มของเราอย่างต่อเนื่อง หากท่านพบปัญหาใดๆ หรือมีข้อเสนอแนะ โปรดติดต่อทีมงานของเราผ่านหน้าศูนย์ช่วยเหลือ</p>
';

$announcements_content_en = '
    <h1 data-key="announcements_title">Announcements</h1>
    <p class="subtitle" data-key="announcements_subtitle">Latest news and updates from LightTooN</p>
    <hr>
    <h2 data-key="announcements_update_heading">New System Update</h2>
    <p data-key="announcements_update_body">The LightTooN team has performed a major system update to improve the performance and stability of the website.</p>
    <h2 data-key="announcements_improvements_heading">Notifications and Improvements</h2>
    <p data-key="announcements_improvements_body">We are committed to continuously developing our platform. If you encounter any problems or have any suggestions, please contact our team via the help center page.</p>
';
?>

<!-- =========================
     HTML โครงสร้างหน้าเพจ
========================= -->
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightTooN - Announcements</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/policy.css">

</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <?php
        if ($currentLang == 'en') {
            echo $announcements_content_en;
        } else {
            echo $announcements_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
