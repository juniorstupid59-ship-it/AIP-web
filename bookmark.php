<?php
session_start();
require_once "phpinclude/db_connect.php"; // แยกไฟล์เชื่อม DB จะดีกว่า

// ตรวจสอบว่าล็อกอินแล้ว
if (!isset($_SESSION['user_id'])) {
    header("Location: phpinclude/signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ดึงรายการโปรดจากฐานข้อมูล
$sql = "SELECT b.id AS bookmark_id, a.title, a.thumbnail, a.type, a.id AS content_id 
        FROM bookmarks b
        JOIN content a ON b.content_id = a.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$bookmarks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>รายการโปรด - LightTooN</title>
<link rel="stylesheet" href="css/style.css">
<style>
.bookmark-container {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(200px,1fr));
    gap: 20px;
    padding: 20px;
}
.bookmark-card {
    background: var(--card);
    border-radius: var(--r-lg);
    overflow: hidden;
    box-shadow: var(--shadow-2);
    transition: transform 0.25s;
}
.bookmark-card:hover {
    transform: scale(1.03);
}
.bookmark-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}
.bookmark-card .info {
    padding: 10px;
}
.bookmark-card .info h3 {
    margin: 0;
    font-size: 16px;
    color: var(--text-100);
}
.bookmark-card .info p {
    margin: 4px 0 0;
    font-size: 14px;
    color: var(--text-300);
}
</style>
</head>
<body class="<?= isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'light' ? 'light-theme' : '' ?>">

<?php include "phpinclude/header.php"; ?>

<main>
<h2 style="padding:20px;">รายการโปรดของคุณ</h2>
<div class="bookmark-container">
    <?php if (count($bookmarks) > 0): ?>
        <?php foreach($bookmarks as $bm): ?>
            <div class="bookmark-card">
                <a href="<?= $bm['type'] ?>.php?id=<?= $bm['content_id'] ?>">
                    <img src="<?= $bm['thumbnail'] ?>" alt="<?= $bm['title'] ?>">
                    <div class="info">
                        <h3><?= $bm['title'] ?></h3>
                        <p><?= ucfirst($bm['type']) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="padding:20px;">คุณยังไม่มีรายการโปรด</p>
    <?php endif; ?>
</div>
</main>

<?php include "phpinclude/footer.php"; ?>
</body>
</html>
