<?php
// account.php

// ดึงฟังก์ชันที่จำเป็นมาใช้ก่อนใครเพื่อน
require __DIR__ . "/phpinclude/functions.php";

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่ และทำการเปลี่ยนเส้นทางถ้ายังไม่ได้ล็อกอิน
// การตรวจสอบนี้ต้องอยู่ก่อนการแสดงผล HTML
if (!isLoggedIn()) {
    header("Location: signin.php");
    exit;
}

// ดึงไฟล์ header.php มาแสดงผล (ในไฟล์นี้มีการเรียกใช้ isLoggedIn() )
include("phpinclude/header.php");

// ดึงข้อมูลผู้ใช้จาก DB
$user_id = $_SESSION['user_id'];
// เพิ่ม 'profile_pic' เข้ามาในคำสั่ง SQL เพื่อดึงข้อมูลรูปโปรไฟล์
$sql = "SELECT username, country, bio, followers, following, profile_pic, banner_pic FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีข้อมูลผู้ใช้หรือไม่
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // หากไม่พบข้อมูลผู้ใช้ในฐานข้อมูล ให้เปลี่ยนเส้นทางไปหน้าล็อกอิน
    // อาจมีการลบบัญชีไปแล้ว
    header("Location: signin.php");
    exit;
}
?>

<!-- เรียก CSS/JS -->
<?php
// ดึงเวลาแก้ไขไฟล์ล่าสุดเพื่อบังคับให้เบราว์เซอร์โหลดไฟล์ใหม่ทุกครั้ง
$css_version = filemtime('css/style.css');
$auth_css_version = filemtime('css/account.css');
?>
<link rel="stylesheet" href="css/style.css?v=<?= $css_version ?>">
<link rel="stylesheet" href="css/account.css?v=<?= $auth_css_version ?>">

<main class="account-container">
    <!-- ส่วนแบนเนอร์โปรไฟล์ -->
    <div class="profile-banner">
        <!-- เช็คว่ามีรูปแบนเนอร์หรือไม่ ถ้าไม่มีจะใช้รูปภาพหรือพื้นหลังเป็นค่าเริ่มต้น -->
        <?php 
            $banner_path = !empty($user['banner_pic']) ? htmlspecialchars($user['banner_pic']) : 'assets/default-banner.png';
        ?>
        <img src="<?= $banner_path ?>" alt="Profile Banner">
    </div>

    <!-- ส่วนโปรไฟล์ -->
    <div class="profile-card">
        <div class="profile-left">
            <img src="<?= htmlspecialchars($user['profile_pic'] ?? 'assets/user.png'); ?>" alt="Profile" class="profile-image">
            <div>
                <h2><?= htmlspecialchars($user['username']); ?></h2>
                <p class="bio"><?= htmlspecialchars($user['bio']); ?></p>
                <p class="user-stats">
                    <span class="country-icon">🌍</span> <?= htmlspecialchars($user['country']); ?> |
                    <span class="followers-icon">👥</span> ผู้ติดตาม <?= $user['followers']; ?> | กำลังติดตาม <?= $user['following']; ?>
                </p>
            </div>
        </div>
        <div class="profile-right">
            <a href="acedit.php" class="edit-btn">แก้ไขโปรไฟล์</a>
        </div>
    </div>

    <!-- เมนูของ account -->
    <nav class="account-nav">
        <a href="account.php" class="nav-btn">🏠 Home</a>
        <a href="?tab=posts" class="nav-btn">📝 โพสต์ของฉัน</a>
        <a href="?tab=fav" class="nav-btn">⭐ รายการโปรด</a>
    </nav>

    <!-- Sub Menu ของโพสต์ -->
    <?php if (isset($_GET['tab']) && $_GET['tab'] == "posts"): ?>
        <div class="sub-nav">
            <a href="?tab=posts&type=manga" class="sub-nav-btn">📚 มังงะ</a>
            <a href="?tab=posts&type=novel" class="sub-nav-btn">📖 นิยาย</a>
            <a href="?tab=posts&type=art" class="sub-nav-btn">🎨 งานวาด</a>
            <a href="?tab=posts&type=video" class="sub-nav-btn">🎬 คลิปวิดีโอ</a>
        </div>
    <?php endif; ?>

    <!-- พื้นที่แสดงโพสต์ -->
    <section class="account-content">
        <?php
        $tab = $_GET['tab'] ?? 'posts';
        $type = $_GET['type'] ?? 'all';

        if ($tab == "posts") {
            echo "<h3>โพสต์ของฉัน (" . htmlspecialchars($type) . ")</h3>";
            // TODO: query จาก DB ตาม type
        } elseif ($tab == "fav") {
            echo "<h3>⭐ รายการโปรดของคุณ</h3>";
        } else {
            echo "<p>เลือกเมนูด้านบน</p>";
        }
        ?>
    </section>
</main>

<?php include("phpinclude/footer.php"); ?>
