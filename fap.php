<?php
session_start();

// ------------------------------
// 1. เชื่อมต่อฐานข้อมูล (ถ้าต้องการดึง FAQ จาก DB)
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
// 2. ฟังก์ชันตรวจสอบการล็อกอิน
// ------------------------------
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ------------------------------
// 3. FAQ ตัวอย่าง (สามารถเพิ่ม/แก้ไขได้)
// ------------------------------
$faq_list = [
    [
        "question" => "ฉันจะสมัครสมาชิกได้อย่างไร?",
        "answer" => "คุณสามารถสมัครสมาชิกได้โดยคลิกที่ปุ่ม 'เข้าสู่ระบบ' แล้วเลือก 'สมัครสมาชิก' กรอกข้อมูลให้ครบถ้วนแล้วกดยืนยัน"
    ],
    [
        "question" => "ฉันลืมรหัสผ่าน ต้องทำอย่างไร?",
        "answer" => "คลิกที่ 'ลืมรหัสผ่าน' บนหน้าเข้าสู่ระบบ ใส่อีเมลของคุณ ระบบจะส่งลิงก์รีเซ็ตรหัสผ่านไปให้"
    ],
    [
        "question" => "ฉันสามารถซื้อหรือเติมเงินใน LightTooN ได้อย่างไร?",
        "answer" => "ไปที่เมนู 'ธนาคาร' หรือ 'Payment Method' เลือกช่องทางการชำระเงินที่คุณสะดวกและทำตามขั้นตอน"
    ],
    [
        "question" => "สามารถอ่านนิยายหรือมังงะได้ฟรีหรือไม่?",
        "answer" => "บางเรื่องสามารถอ่านฟรีได้ แต่บางเรื่องอาจต้องใช้เครดิตหรือเป็นสมาชิกพรีเมียม"
    ],
    [
        "question" => "ฉันสามารถลบบัญชีของฉันได้ไหม?",
        "answer" => "สามารถลบบัญชีได้ โดยไปที่หน้าบัญชีและเลือก 'ลบบัญชี' ระบบจะลบข้อมูลของคุณทั้งหมด"
    ]
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ - LightTooN</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* =================== FAQ Styles =================== */
.faq-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 20px;
}
.faq-container h1 {
    margin-bottom: 20px;
    font-size: 28px;
    text-align: center;
}
.faq-item {
    background: var(--card);
    margin-bottom: 16px;
    border-radius: var(--r-lg);
    padding: 16px 20px;
    box-shadow: var(--shadow-2);
}
.faq-item h3 {
    cursor: pointer;
    margin: 0;
    font-size: 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.faq-item p {
    margin-top: 8px;
    display: none;
}
.faq-item.open p {
    display: block;
}
.faq-item h3::after {
    content: "+";
    font-weight: bold;
    transition: transform 0.3s;
}
.faq-item.open h3::after {
    content: "-";
    transform: rotate(180deg);
}
</style>
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="main-header">
  <button class="menu-toggle" id="menuToggle">☰</button>
  <div class="logo"><a href="index.php">LightTooN</a></div>
  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search...">
    <div class="search-results" id="searchResults"></div>
  </div>
  <div class="header-right">
    <a href="notifications.php" class="notif-btn">🔔</a>
    <button id="toggleTheme" class="header-btn">🌙</button>
    <button id="toggleLang" class="header-btn">EN</button>
    <div class="profile-menu" id="profileMenu">
      <img src="assets/user.png" alt="Profile" class="profile-icon">
      <div class="dropdown" id="profileDropdown">
        <?php if (isLoggedIn()) : ?>
          <a href="phpinclude/account.php" data-key="account">บัญชี</a>
          <a href="phpinclude/bookmark.php" data-key="bookmark">รายการโปรด</a>
          <a href="phpinclude/bank.php" data-key="bank">ธนาคาร</a>
          <a href="phpinclude/work.php" data-key="work">สมาชิก</a>
          <a href="phpinclude/help.php" data-key="help">ช่วยเหลือ</a>
          <a href="phpinclude/logout.php" class="logout" data-key="logout">ออกจากระบบ</a>
        <?php else : ?>
          <a href="phpinclude/signin.php" data-key="signin">เข้าสู่ระบบ</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>

<!-- ================= SIDEBAR ================= -->
<nav class="sidebar" id="sidebar">
  <a href="phpinclude/index.php" data-key="home">หน้าแรก</a>
  <a href="phpinclude/artwork.php" data-key="artwork">งานศิลปะ</a>
  <a href="phpinclude/manga.php" data-key="manga">มังงะ</a>
  <a href="phpinclude/novel.php" data-key="novel">นิยาย</a>
  <a href="phpinclude/newest.php" data-key="newest">ใหม่ล่าสุด</a>
  <a href="phpinclude/popular.php" data-key="popular">ยอดนิยม</a>
  <a href="phpinclude/categories.php" data-key="categories">หมวดหมู่</a>
</nav>

<!-- ================= CONTENT ================= -->
<div class="faq-container">
  <h1 data-key="faq">คำถามที่พบบ่อย (FAQ)</h1>
  <?php foreach ($faq_list as $faq): ?>
    <div class="faq-item">
      <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
      <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
    </div>
  <?php endforeach; ?>
</div>

<!-- ================= FOOTER ================= -->
<footer class="site-footer">
  <div class="footer-container">
    <div class="footer-column">
      <h4 data-key="about_us">เกี่ยวกับเรา</h4>
      <ul>
        <li><a href="phpinclude/work.php" data-key="signup_steps">ขั้นตอนการสมัครสมาชิก</a></li>
        <li><a href="phpinclude/bank.php" data-key="payment_method">วิธีการชำระเงิน</a></li>
        <li><a href="phpinclude/ownpost.php" data-key="join_us">ร่วมงานกับเรา</a></li>
        <li><a href="phpinclude/faq.php" data-key="faq">FAQ</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4 data-key="customer_service">บริการลูกค้า</h4>
      <ul>
        <li><a href="phpinclude/help.php" data-key="help_center">ศูนย์ช่วยเหลือ</a></li>
        <li><a href="phpinclude/policy-privacy.php" data-key="privacy_policy">นโยบายความเป็นส่วนตัว</a></li>
        <li><a href="phpinclude/policy-return.php" data-key="return_policy">นโยบายการเปลี่ยนสินค้า</a></li>
        <li><a href="phpinclude/shipping.php" data-key="shipping">การจัดส่งสินค้า</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4 data-key="footer_stores">สาขา LightTooN Stores</h4>
      <ul>
        <li>Central Pinklao Store</li>
        <li>Seacon Square Store</li>
        <li>Samyan Mitrtown Store</li>
        <li><a href="phpinclude/stores.php" data-key="other_stores">สาขาอื่น ๆ</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© LIGHT TOON OFFICIAL</p>
  </div>
</footer>

<!-- ================= JS ================= -->
<script src="js/main.js"></script>
<script>
// FAQ Toggle
document.querySelectorAll(".faq-item h3").forEach(header => {
  header.addEventListener("click", () => {
    const item = header.parentElement;
    item.classList.toggle("open");
  });
});
</script>
</body>
</html>
