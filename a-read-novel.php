<?php
session_start();
require_once "db.php"; // สมมติไฟล์เชื่อมต่อ DB และฟังก์ชัน isLoggedIn() อยู่ในนี้

// ตรวจสอบว่ามี ID นิยายที่ส่งมาหรือไม่
$novel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($novel_id <= 0) {
    die("นิยายไม่ถูกต้อง");
}

// ดึงข้อมูลนิยายจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM novels WHERE id = ?");
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$result = $stmt->get_result();
$novel = $result->fetch_assoc();

if (!$novel) {
    die("ไม่พบข้อมูลนิยาย");
}

// ดึงเนื้อหาตอน / หน้า
$stmt = $conn->prepare("SELECT * FROM novel_pages WHERE novel_id = ? ORDER BY page_number ASC");
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$pages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($novel['title']); ?> — LightTooN</title>
<link rel="stylesheet" href="css/detail.css">
<script src="js/detail.js" defer></script>
<style>
/* เพิ่มสไตล์เฉพาะหน้าของ read-novel */
.reader-container p {
    font-size: 18px;
    line-height: 2;
    margin: 0;
}
</style>
</head>
<body>

<?php include "phpinclude/header.php"; ?>

<main class="detail-page">
  <div class="content">

    <!-- ---------- Work Header ---------- -->
    <div class="work-header">
      <div class="title"><?php echo htmlspecialchars($novel['title']); ?></div>
      <div class="meta">
        <span><?php echo htmlspecialchars($novel['author']); ?></span>
        <span>จำนวนตอน: <?php echo count($pages); ?></span>
        <span>วันที่อัปเดต: <?php echo htmlspecialchars($novel['updated_at']); ?></span>
      </div>
    </div>

    <!-- ---------- Reader Container ---------- -->
    <div class="reader-container">
      <?php foreach($pages as $page): ?>
        <div class="page">
          <p><?php echo nl2br(htmlspecialchars($page['content'])); ?></p>
        </div>
      <?php endforeach; ?>
      <div class="reader-controls">
        <button class="reader-btn btn-prev-ep">ก่อนหน้า</button>
        <button class="reader-btn btn-next-ep">ถัดไป</button>
      </div>
    </div>

    <!-- ---------- Like / Bookmark ---------- -->
    <div class="like-bookmark">
      <div class="btn btn-like">
        ❤️ ชอบ <span class="like-count"><?php echo $novel['likes'] ?? 0; ?></span>
      </div>
      <div class="btn btn-bookmark">
        🔖 <span class="bookmark-label">บันทึก</span>
      </div>
    </div>

    <!-- ---------- Comments ---------- -->
    <div class="comments">
      <form id="commentForm">
        <textarea placeholder="แสดงความคิดเห็น..." required></textarea>
        <button type="submit">ส่ง</button>
      </form>
      <div class="comment-list">
        <!-- คอมเมนต์จาก JS -->
      </div>
    </div>

  </div>
</main>

<?php include "phpinclude/footer.php"; ?>

</body>
</html>
