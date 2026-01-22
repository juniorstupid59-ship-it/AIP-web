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
// Content สำหรับ Notifications
// -----------------------------
$notifications_th = [
    [
        'title' => 'ยินดีต้อนรับ',
        'body'  => 'ยินดีต้อนรับสู่เว็บของเรา! หวังว่าคุณจะสนุกกับการใช้งาน LightTooN.',
        'type'  => 'success'
    ],
    [
        'title' => 'ข้อมูลใหม่',
        'body'  => 'เราได้อัปเดตข้อมูลล่าสุดบนเว็บไซต์ ตรวจสอบหน้าใหม่เพื่อดูรายละเอียด.',
        'type'  => 'info'
    ]
];

$notifications_en = [
    [
        'title' => 'Welcome',
        'body'  => 'Welcome to our website! We hope you enjoy using LightTooN.',
        'type'  => 'success'
    ],
    [
        'title' => 'New Update',
        'body'  => 'We have updated the latest information on the website. Check out the new page for details.',
        'type'  => 'info'
    ]
];
?>

<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightTooN - Notifications</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notifications.css">
</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <h1 data-key="notifications_title"><?php echo ($currentLang == 'en') ? 'Notifications' : 'การแจ้งเตือน'; ?></h1>
        <p class="subtitle" data-key="notifications_subtitle">
            <?php echo ($currentLang == 'en') ? 'Here are your latest notifications.' : 'นี่คือการแจ้งเตือนล่าสุดของคุณ'; ?>
        </p>
        <hr>
    </main>

    <!-- ---------- Notifications Container ---------- -->
    <div class="notifications-container">
        <?php
        $notifications = ($currentLang == 'en') ? $notifications_en : $notifications_th;

        foreach ($notifications as $notif) {
            echo '<div class="notification-card notification-'. $notif['type'] .' show">';
            echo '  <div class="notification-header">';
            echo '      <span class="notification-title">'. $notif['title'] .'</span>';
            echo '      <span class="notification-close" onclick="this.parentElement.parentElement.remove();">&times;</span>';
            echo '  </div>';
            echo '  <div class="notification-body">'. $notif['body'] .'</div>';
            echo '</div>';
        }
        ?>
    </div>

    <?php include 'phpinclude/footer.php'; ?>

    <!-- JS: ให้ Notification เลื่อนเข้ามา (ถ้าอยากทำแบบ Animation เพิ่มเติม) -->
    <script>
        document.querySelectorAll('.notification-card').forEach(card => {
            setTimeout(() => card.classList.add('show'), 100);
        });
    </script>
</body>
</html>
 