<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'phpinclude/functions.php';
$conn = dbConnect();

// ตรวจสอบว่า novel ID ถูกต้อง
$novel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($novel_id <= 0) {
    die("Invalid novel.");
}

// ดึงข้อมูลนิยาย
$stmt = $conn->prepare("SELECT * FROM novels WHERE id = ?");
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$result = $stmt->get_result();
$novel = $result->fetch_assoc();

// บังคับชื่อผู้แต่งเป็นพยอล
if ($novel) {
    $novel['author'] = 'พยอล';
}
$stmt->close();

if (!$novel) {
    // ถ้าไม่เจอในฐานข้อมูล ให้ใช้ข้อมูลทดสอบ
    if ($novel_id == 1) { 
        $novel = [
            'id' => 1,
            'title' => 'อ้อนรักหมอขา',
            'author' => 'พยอล',
            'updated_at' => date('Y-m-d H:i:s')
        ];
    } else {
        die("Novel not found.");
    }
}

// ดึงตอนทั้งหมด
$pages = [];
$stmt = $conn->prepare("SELECT * FROM novel_pages WHERE novel_id = ? ORDER BY page_number ASC");
$stmt->bind_param("i", $novel_id);
$stmt->execute();
$pages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// ถ้าไม่มีตอน ให้ใช้ข้อมูลทดสอบ
if (empty($pages) && $novel_id == 1) { 
    $pages = [
        ['page_number' => 1, 'content' => '<p><b>ตอนที่ 1</b></p><p>“คุณคะ ช่วยมาหาฉันหน่อยสิคะ” ชายหนุ่มรับสาย...</p>'],
        ['page_number' => 2, 'content' => '<p><b>ตอนที่ 2</b></p><p>“สวัสดีครับคนสวย” เอเคอร์ว่าด้วยรอยยิ้ม...</p>'],
        ['page_number' => 3, 'content' => '<p><b>ตอนที่ 3</b></p><p>ฟอดดด</p><p>“คิคิ ซื้อ จั๊บกาจี้ค่ะ มีหนวดๆ เหมือนคูมป้าเยย”...</p>']
    ];
}

// กำหนดหน้าปัจจุบัน
$current_page_number = isset($_GET['page']) ? intval($_GET['page']) : 1;
$current_page = null;
foreach ($pages as $page) {
    if ($page['page_number'] == $current_page_number) {
        $current_page = $page;
        break;
    }
}

// ถ้าไม่มีหน้าปัจจุบัน ให้เลือกตอนแรก
if ($current_page === null && !empty($pages)) {
    $current_page = $pages[0];
    $current_page_number = $pages[0]['page_number'];
}
?>

<?php include 'phpinclude/header.php'; ?>

<link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
<link rel="stylesheet" href="css/detail.css?v=<?= time() ?>">
<style>
.work-header {
    text-align: center;
    margin-bottom: 20px;
}
.work-header .title {
    margin: 10px 0;
}
.work-cover {
    display: block;
    width: 150px;
    height: 225px;
    margin: 0 auto 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
}
.work-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}
.novel-text {
    font-size: 18px;
    line-height: 1.9;
    text-align: justify;
    color: var(--text-200);
    white-space: pre-wrap;
    width: 100%;
    max-width: 720px;
    margin: 0 auto;
}
.page-container {
    background: var(--bg-850);
    border-radius: 12px;
    padding: 20px;
    box-shadow: var(--shadow-1);
    margin-bottom: 20px;
}
.chapter-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
    width: 100%;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}
.chapter-btn {
    padding: 12px 20px;
    border-radius: 8px;
    border: 1px solid var(--border-color, #444);
    background: var(--bg-secondary);
    color: var(--text-200);
    cursor: pointer;
    text-decoration: none;
    font-size: 16px;
    transition: background-color 0.2s ease, border-color 0.2s ease;
    text-align: left;
}
.chapter-btn:hover {
    background: var(--bg-700);
    border-color: var(--text-100);
}
.chapter-btn.active {
    background: var(--primary-color);
    color: var(--text-100);
    border: 1px solid var(--primary-color);
    font-weight: bold;
}
</style>

<main class="detail-page">
    <div class="content">

        <div class="work-header">
            <div class="work-cover">
                <img src="assets/novel1.jpg" alt="<?= htmlspecialchars($novel['title']) ?> Cover">
            </div>
            
            <h1 class="title"><?= htmlspecialchars($novel['title']) ?></h1>
            <div class="meta">
                <span data-key="author">ผู้แต่ง</span>: <span><?= htmlspecialchars($novel['author']) ?></span>
                <span data-key="chapter_count">จำนวนตอน</span>: <span><?= count($pages) ?></span>
                <span data-key="last_updated">อัปเดตล่าสุด</span>: <span><?= htmlspecialchars($novel['updated_at']) ?></span>
            </div>
        </div>

        <!-- เปลี่ยนปุ่มตอนให้เด้งไปหน้า read.php -->
        <div class="chapter-list">
            <?php foreach ($pages as $page): ?>
                <a class="chapter-btn" href="read.php?id=<?= $novel_id ?>&page=<?= $page['page_number'] ?>">
                    <span data-key="chapter">ตอนที่</span> <?= $page['page_number'] ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</main>

<?php include 'phpinclude/footer.php'; ?>

<?php
if ($conn) {
    $conn->close();
}
?>
