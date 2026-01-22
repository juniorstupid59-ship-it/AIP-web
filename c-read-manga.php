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
$manga_title = "C105 新刊『Whiteout』サンプル";
$manga_author = "みふじやまい💊新刊委託中";
$manga_cover = "/LightTooN/assets/manga5-1.jpg";

$manga_pages = [
    "/LightTooN/assets/manga5-1.jpg",
    "/LightTooN/assets/manga5-2.jpg",
    "/LightTooN/assets/manga5-3.jpg",
    "/LightTooN/assets/manga5-4.jpg",
    "/LightTooN/assets/manga5-5.jpg",
    "/LightTooN/assets/manga5-6.jpg",
    "/LightTooN/assets/manga5-7.jpg",
    "/LightTooN/assets/manga5-8.jpg",
    "/LightTooN/assets/manga5-9.jpg",
    "/LightTooN/assets/manga5-a.jpg",
    "/LightTooN/assets/manga5-b.jpg",
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

        <!-- ปกมังงะ -->
        <div class="work-cover">
            <img src="<?= $manga_cover ?>" alt="ปกเรื่อง <?= $manga_title ?>">
        </div>

        <!-- หน้า มังงะ -->
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
