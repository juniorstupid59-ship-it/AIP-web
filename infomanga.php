<?php
// เริ่มเซสชันและโหลดฟังก์ชัน
session_start();
require __DIR__ . "/phpinclude/functions.php";

// เชื่อมฐานข้อมูล
$mysqli = new mysqli("localhost", "root", "", "lighttoon");
if ($mysqli->connect_errno) {
    die("DB Connection failed: " . $mysqli->connect_error);
}

// รับ ID ของมังงะจาก URL
$manga_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// ดึงข้อมูลมังงะ
$sql = "SELECT * FROM mangas WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $manga_id);
$stmt->execute();
$manga = $stmt->get_result()->fetch_assoc();

if (!$manga) {
    die("ไม่พบมังงะที่คุณค้นหา");
}

// ดึงตอน preview (เช่นตอนที่ 1)
$preview_sql = "SELECT * FROM manga_chapters WHERE manga_id = ? ORDER BY chapter_number ASC LIMIT 1";
$preview_stmt = $mysqli->prepare($preview_sql);
$preview_stmt->bind_param("i", $manga_id);
$preview_stmt->execute();
$preview = $preview_stmt->get_result()->fetch_assoc();
?>

<?php include __DIR__ . "/phpinclude/header.php"; ?>

<!-- CSS -->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/manga.css">

<main class="container">
  <div class="manga-info-card">
    <!-- ปกมังงะ -->
    <div class="manga-cover">
      <img src="uploads/manga_covers/<?= htmlspecialchars($manga['cover']) ?>" alt="<?= htmlspecialchars($manga['title']) ?>">
    </div>

    <!-- รายละเอียด -->
    <div class="manga-details">
      <h2 class="manga-title"><?= htmlspecialchars($manga['title']) ?></h2>
      <p class="manga-meta">
        <strong data-key="author">ผู้แต่ง:</strong> <?= htmlspecialchars($manga['author']) ?><br>
        <strong>หมวดหมู่:</strong> <?= htmlspecialchars($manga['category']) ?>
      </p>
      <p class="manga-desc"><?= nl2br(htmlspecialchars($manga['description'])) ?></p>

      <!-- ส่วนซื้อ / รายการโปรด -->
      <div class="manga-purchase">
        <p class="price">ราคา: <?= number_format($manga['price']) ?> บาท</p>
        <?php if (isLoggedIn()): ?>
          <form method="POST" action="phpinclude/purchase.php">
            <input type="hidden" name="manga_id" value="<?= $manga['id'] ?>">
            <button type="submit" class="btn-primary">🛒 ซื้อเล่มนี้</button>
          </form>
          <form method="POST" action="bookmark.php" style="display:inline;">
            <input type="hidden" name="manga_id" value="<?= $manga['id'] ?>">
            <button type="submit" class="btn-secondary">⭐ เพิ่มเข้ารายการโปรด</button>
          </form>
        <?php else: ?>
          <a href="phpinclude/signin.php" class="btn-primary">เข้าสู่ระบบเพื่อซื้อ</a>
        <?php endif; ?>
      </div>

      <!-- Preview -->
      <?php if ($preview): ?>
      <div class="manga-preview">
        <h3>📖 ตัวอย่างฟรี: ตอนที่ <?= htmlspecialchars($preview['chapter_number']) ?></h3>
        <a href="phpinclude/read-manga.php?chapter_id=<?= $preview['id'] ?>" class="btn-secondary">อ่านตอนตัวอย่าง</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include __DIR__ . "phpinclude/footer.php"; ?>
