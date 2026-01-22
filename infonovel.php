<?php
// เริ่ม session
session_start();
require __DIR__ . "/phpinclude/functions.php";

// เชื่อมต่อฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "lighttoon");
if ($mysqli->connect_errno) {
    die("DB Connection failed: " . $mysqli->connect_error);
}

// รับ ID ของนิยาย
$novel_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// ดึงข้อมูลนิยาย
$sql = "SELECT * FROM novels WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$novel = $stmt->get_result()->fetch_assoc();

if (!$novel) {
    die("ไม่พบนิยายที่คุณค้นหา");
}
?>

<?php include __DIR__ . "/phpinclude/header.php"; ?>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/novel.css">

<main class="container">
  <div class="novel-info-card">
    <!-- ปกนิยาย -->
    <div class="novel-cover">
      <img src="uploads/novel_covers/<?= htmlspecialchars($novel['cover']) ?>" alt="<?= htmlspecialchars($novel['title']) ?>">
    </div>

    <!-- รายละเอียด -->
    <div class="novel-details">
      <h2 class="novel-title"><?= htmlspecialchars($novel['title']) ?></h2>
      <p class="novel-meta">
        <strong data-key="author">ผู้แต่ง:</strong> <?= htmlspecialchars($novel['author']) ?><br>
        <strong>หมวดหมู่:</strong> <?= htmlspecialchars($novel['category']) ?>
      </p>
      <p class="novel-desc"><?= nl2br(htmlspecialchars($novel['description'])) ?></p>

      <!-- ส่วนซื้อ / รายการโปรด -->
      <div class="novel-purchase">
        <p class="price">ราคา: <?= number_format($novel['price']) ?> บาท</p>
        <?php if (isLoggedIn()): ?>
          <form method="POST" action="purchase.php">
            <input type="hidden" name="novel_id" value="<?= $novel['id'] ?>">
            <button type="submit" class="btn-primary">🛒 ซื้อเรื่องนี้</button>
          </form>
          <form method="POST" action="bookmark.php" style="display:inline;">
            <input type="hidden" name="novel_id" value="<?= $novel['id'] ?>">
            <button type="submit" class="btn-secondary">⭐ เพิ่มเข้ารายการโปรด</button>
          </form>
        <?php else: ?>
          <a href="phpinclude/signin.php" class="btn-primary">เข้าสู่ระบบเพื่อซื้อ</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php include __DIR__ . "/phpinclude/footer.php"; ?>
