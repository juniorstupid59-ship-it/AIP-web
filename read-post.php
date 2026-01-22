<?php
session_start();

// ------------------------------
// เชื่อมต่อฐานข้อมูล
// ------------------------------
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lighttoon";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ------------------------------
// ตรวจสอบการล็อกอิน
// ------------------------------
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ------------------------------
// ดึงโพสต์จากฐานข้อมูล
// ------------------------------
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post = null;

if ($post_id > 0) {
    $stmt = $conn->prepare("SELECT p.*, u.username, u.avatar FROM posts p LEFT JOIN users u ON p.user_id=u.id WHERE p.id=? LIMIT 1");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
}

// ------------------------------
// ดึงคอมเมนต์
// ------------------------------
$comments = [];
if ($post) {
    $stmt = $conn->prepare("SELECT c.*, u.username, u.avatar FROM comments c LEFT JOIN users u ON c.user_id=u.id WHERE c.post_id=? ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>อ่านโพสต์ - LightTooN</title>
<link rel="stylesheet" href="css/detail.css">
<script src="js/detail.js" defer></script>
<style>
/* ปรับปุ่มรีโพสต์/แชร์/ติดตาม */
.btn-repost, .btn-share, .btn-follow {
  padding: 6px 12px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--surface-1);
  color: var(--text-300);
  cursor: pointer;
  transition: transform .15s ease, background .2s ease;
}
.btn-follow.active {
  background: var(--brand-500);
  color: #02102a;
  border-color: transparent;
}
</style>
</head>
<body>
<?php include "phpinclude/header.php"; ?>

<main class="detail-page">
  <div class="content">

    <?php if ($post): ?>
    <!-- Post Card -->
    <div class="post-card">
      <div class="avatar">
        <img src="<?php echo htmlspecialchars($post['avatar'] ?: 'assets/user.png'); ?>" alt="Avatar">
      </div>
      <div class="content">
        <div class="name">
          <?php echo htmlspecialchars($post['username']); ?>
          <button class="btn-follow">ติดตาม</button>
        </div>
        <div class="text"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
        <div class="like-bookmark">
          <button class="btn btn-like">
            ❤️ <span class="like-count"><?php echo intval($post['likes']); ?></span>
          </button>
          <button class="btn btn-repost">🔄 รีโพสต์</button>
          <button class="btn btn-share">📤 แชร์</button>
        </div>
      </div>
    </div>

    <!-- Comment Section -->
    <div class="work-synopsis">
      <h3>คอมเมนต์</h3>
      <form id="commentForm">
        <textarea placeholder="เขียนคอมเมนต์..." required></textarea>
        <button type="submit" class="reader-btn">โพสต์</button>
      </form>
      <div class="comment-list">
        <?php foreach($comments as $c): ?>
          <div class="comment-item">
            <strong><?php echo htmlspecialchars($c['username']); ?></strong>
            <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <?php else: ?>
      <p>ไม่พบโพสต์</p>
    <?php endif; ?>

  </div>
</main>

<?php include "phpinclude/footer.php"; ?>
<script>
// ฟังก์ชัน toggle ติดตาม
document.querySelectorAll('.btn-follow').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    btn.classList.toggle('active');
    btn.textContent = btn.classList.contains('active') ? 'ติดตามแล้ว' : 'ติดตาม';
  });
});
</script>
</body>
</html>
