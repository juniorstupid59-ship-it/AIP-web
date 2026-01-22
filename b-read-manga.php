<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'phpinclude/functions.php'; // สำหรับข้อมูล header หรือ user
$conn = dbConnect(); // เชื่อมต่อ DB สำหรับข้อมูลอื่น ๆ

// ดึงชื่อผู้ใช้หรือข้อมูล header
$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt_user = $conn->prepare("SELECT username FROM users WHERE id=?");
    $stmt_user->bind_param("i", $_SESSION['user_id']);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
    }
    $stmt_user->close();
}

// ข้อมูลมังงะแบบ static
$manga_title = "【お知らせ】6周年＆新婚ミニ漫画";
$manga_author = "色のん";
$manga_cover = "/LightTooN/assets/manga4-1.jpg";

$manga_pages = [
    "/LightTooN/assets/manga4-1.jpg",
    "/LightTooN/assets/manga4-2.jpg",
    "/LightTooN/assets/manga4-3.jpg",
    "/LightTooN/assets/manga4-4.jpg",
    "/LightTooN/assets/manga4-5.jpg",
    "/LightTooN/assets/manga4-6.jpg",
    "/LightTooN/assets/manga4-7.jpg",
    "/LightTooN/assets/manga4-8.jpg"
];
?>

<?php include 'phpinclude/header.php'; ?>

<link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
<link rel="stylesheet" href="css/detail.css?v=<?= time() ?>">

<main class="detail-page">
    <div class="content">

        <!-- Header มังงะ -->
<div class="work-header">
    <h1 class="title" data-key-th="<?= $manga_title ?>" data-key-en="<?= $manga_title_en ?>"><?= $manga_title ?></h1>
    <div class="meta">
    <span>
        <span data-key="author_label" data-key-th="ผู้แต่ง:" data-key-en="Author:">ผู้แต่ง:</span>
        <?= $manga_author ?>
    </span>
</div>
</div>

        <div class="work-cover">
            <img src="<?= $manga_cover ?>" alt="ปกเรื่อง <?= $manga_title ?>">
        </div>

        <section class="manga-pages">
            <h3>ตอนที่ 1</h3>
            <div class="page-container">
                <?php foreach ($manga_pages as $idx => $page): ?>
                    <img src="<?= $page ?>" alt="Manga Page <?= $idx + 1 ?>" class="manga-page-image">
                <?php endforeach; ?>
            </div>
        </section>

    </div>
</main>

<?php include 'phpinclude/footer.php'; ?>

<script src="js/detail.js"></script>

<?php
if ($conn) { $conn->close(); }
?>