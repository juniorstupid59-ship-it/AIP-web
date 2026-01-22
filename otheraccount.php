<?php
require __DIR__ . "/phpinclude/functions.php";
include __DIR__ . "/phpinclude/header.php";

// -----------------------------
// ดึงข้อมูล user จาก query string
// เช่น otheraccount.php?id=2
// -----------------------------
if (!isset($_GET['id'])) {
    die("ไม่พบผู้ใช้");
}

$user_id = intval($_GET['id']);

// ดึงข้อมูลผู้ใช้จาก database
$sql = "SELECT username, bio, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("ไม่พบผู้ใช้");
}
$user = $result->fetch_assoc();
?>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/account.css">
<script src="js/main.js" defer></script>
<script src="js/account.js" defer></script>

<main class="account-page">
  <!-- ส่วนหัวโปรไฟล์ -->
  <section class="profile-header">
    <img src="<?php echo $user['profile_pic'] ?: 'assets/user.png'; ?>" alt="Profile Picture" class="profile-pic">
    <div class="profile-info">
      <h2>@<?php echo htmlspecialchars($user['username']); ?></h2>
      <p><?php echo htmlspecialchars($user['bio'] ?: "ยังไม่มีข้อมูลแนะนำตัว"); ?></p>
    </div>
  </section>

  <!-- Tabs -->
  <section class="tabs">
    <button class="tab-btn active" data-tab="posts" data-key="posts">โพสต์</button>
    <button class="tab-btn" data-tab="reposts" data-key="reposts">รีโพสต์</button>
  </section>

  <!-- Content -->
  <section class="tab-content">
    <!-- โพสต์ -->
    <div id="posts" class="tab-panel active">
      <?php
      $sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $posts = $stmt->get_result();

      if ($posts->num_rows > 0) :
        while ($post = $posts->fetch_assoc()) : ?>
          <div class="post-card">
            <h3><?php echo htmlspecialchars($post['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <span class="meta"><?php echo $post['created_at']; ?></span>
          </div>
      <?php endwhile;
      else :
        echo "<p>ยังไม่มีโพสต์</p>";
      endif;
      ?>
    </div>

    <!-- รีโพสต์ -->
    <div id="reposts" class="tab-panel">
      <?php
      $sql = "SELECT r.*, p.title, p.content, u.username 
              FROM reposts r
              JOIN posts p ON r.post_id = p.id
              JOIN users u ON p.user_id = u.id
              WHERE r.user_id = ?
              ORDER BY r.created_at DESC";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $reposts = $stmt->get_result();

      if ($reposts->num_rows > 0) :
        while ($rp = $reposts->fetch_assoc()) : ?>
          <div class="post-card repost">
            <h4>รีโพสต์จาก @<?php echo htmlspecialchars($rp['username']); ?></h4>
            <h3><?php echo htmlspecialchars($rp['title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($rp['content'])); ?></p>
            <span class="meta"><?php echo $rp['created_at']; ?></span>
          </div>
      <?php endwhile;
      else :
        echo "<p>ยังไม่มีรีโพสต์</p>";
      endif;
      ?>
    </div>
  </section>
</main>

<?php include __DIR__ . "phpinclude/footer.php"; ?>
