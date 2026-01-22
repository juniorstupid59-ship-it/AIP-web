<?php
session_start();

// ------------------------------
// 1. เชื่อมต่อฐานข้อมูล
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
// 2. ตรวจสอบการล็อกอิน
// ------------------------------
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ------------------------------
// 3. ดึงข้อมูลนิยาย
// ------------------------------
$novel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($novel_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM novels WHERE id = ?");
    $stmt->bind_param("i", $novel_id);
    $stmt->execute();
    $novel_result = $stmt->get_result();
    $novel = $novel_result->fetch_assoc();
    if (!$novel) {
        die("นิยายไม่พบ");
    }
} else {
    die("ไม่พบ ID นิยาย");
}

// ------------------------------
// 4. ดึงตอนของนิยาย
// ------------------------------
$stmt = $conn->prepare("SELECT * FROM chapters WHERE novel_id = ? ORDER BY chapter_number ASC");
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$chapters_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($novel['title']); ?> - LightTooN</title>
<link rel="stylesheet" href="css/style.css">
<style>
/* ------------------ สไตล์เพิ่มเติมสำหรับนิยาย ------------------ */
.novel-details {
    background: var(--card);
    padding: 20px;
    border-radius: var(--r-lg);
    box-shadow: var(--shadow-2);
    margin: 20px auto;
    max-width: 900px;
}
.novel-details h1 { margin-bottom: 8px; }
.chapters-list {
    background: var(--card);
    padding: 20px;
    border-radius: var(--r-lg);
    box-shadow: var(--shadow-2);
    margin: 20px auto;
    max-width: 900px;
}
.chapters-list ul { list-style: none; padding: 0; }
.chapters-list li { margin-bottom: 10px; }
.chapters-list li a {
    text-decoration: none;
    color: #1fb7ff;
    transition: color 0.25s;
}
.chapters-list li a:hover { color: #0ea5d6; text-decoration: underline; }
</style>
</head>
<body>
<!-- ================= HEADER ================= -->
<header class="main-header">
  <button class="menu-toggle" id="menuToggle">☰</button>
  <div class="logo"><a href="phpinclude/index.php">LightTooN</a></div>

  <div class="search-bar">
    <input type="text" id="searchInput" placeholder="Search...">
    <div class="search-results" id="searchResults"></div>
  </div>

  <div class="header-right">
    <a href="phpinclude/notifications.php" class="notif-btn">🔔</a>
    <button id="toggleTheme" class="header-btn">🌙</button>
    <button id="toggleLang" class="header-btn">EN</button>
    <div class="profile-menu" id="profileMenu">
      <img src="assets/user.png" alt="Profile" class="profile-icon">
      <div class="dropdown" id="profileDropdown">
        <?php if (isLoggedIn()) : ?>
          <a href="account.php" data-key="account">บัญชี</a>
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
<div class="novel-details">
  <h1><?php echo htmlspecialchars($novel['title']); ?></h1>
  <p><strong data-key="author"><?php echo $novel['author']; ?></strong></p>
  <p><?php echo nl2br(htmlspecialchars($novel['description'])); ?></p>
</div>

<div class="chapters-list">
  <h2 data-key="all">ตอนทั้งหมด</h2>
  <ul>
    <?php while($chapter = $chapters_result->fetch_assoc()): ?>
      <li>
        <a href="phpinclude/read.php?novel_id=<?php echo $novel_id; ?>&chapter=<?php echo $chapter['chapter_number']; ?>">
          <?php echo htmlspecialchars($chapter['chapter_title']); ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
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
<script>
// Sidebar
const menuToggle = document.getElementById("menuToggle");
const sidebar = document.getElementById("sidebar");
menuToggle && menuToggle.addEventListener("click", () => sidebar.classList.toggle("open"));
document.addEventListener("click", (e) => { if(!sidebar.contains(e.target) && !menuToggle.contains(e.target)) sidebar.classList.remove("open"); });

// Profile Dropdown
const profileMenu = document.getElementById("profileMenu");
const profileDropdown = document.getElementById("profileDropdown");
profileMenu && profileMenu.addEventListener("click", (e)=>{e.stopPropagation(); profileDropdown.classList.toggle("open");});
document.addEventListener("click", ()=> profileDropdown.classList.remove("open"));

// Theme Toggle
const themeBtn = document.getElementById("toggleTheme");
themeBtn && themeBtn.addEventListener("click", ()=>{
  document.body.classList.toggle("light-theme");
  localStorage.setItem("theme", document.body.classList.contains("light-theme")?"light":"dark");
});
if(localStorage.getItem("theme")==="light") document.body.classList.add("light-theme");

// Language Toggle
const langBtn = document.getElementById("toggleLang");
let currentLang = localStorage.getItem("lang") || "th";
async function loadLang(lang){
  try{
    const res = await fetch("phpinclude/lang.json");
    const data = await res.json();
    const texts = data[lang];
    document.querySelectorAll("[data-key]").forEach(el=>{
      const key = el.getAttribute("data-key");
      if(texts[key]) el.textContent = texts[key];
    });
    localStorage.setItem("lang", lang);
    currentLang = lang;
    langBtn && (langBtn.textContent = lang==="th"?"TH":"EN");
  }catch(err){ console.error("ไม่สามารถโหลดไฟล์ภาษาได้:",err); }
}
loadLang(currentLang);
langBtn && langBtn.addEventListener("click", ()=>{loadLang(currentLang==="th"?"en":"th");});

// Search Suggestion (Mock)
const searchInput = document.getElementById("searchInput");
const searchResults = document.getElementById("searchResults");
if(searchInput && searchResults){
  const mockData = {Artwork:["Art1","Art2"],Manga:["Manga1"],Novel:["Novel1","Novel2"],User:["User1"]};
  searchInput.addEventListener("input", ()=>{
    const query = searchInput.value.trim().toLowerCase();
    if(!query) return searchResults.classList.remove("active");
    let html="";
    for(let cat in mockData){
      const matches = mockData[cat].filter(i=>i.toLowerCase().includes(query));
      if(matches.length>0){ html+=`<strong>${cat}</strong><ul>`; matches.forEach(m=>html+=`<li>${m}</li>`); html+="</ul>";}
    }
    searchResults.innerHTML = html || "<p>No results found</p>";
    searchResults.classList.add("active");
  });
  document.addEventListener("click",(e)=>{if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) searchResults.classList.remove("active");});
}
</script>
</body>
</html>
