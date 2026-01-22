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
// 2. รับ manga_id จาก GET
// ------------------------------
$manga_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($manga_id <= 0) {
    die("Invalid manga ID.");
}

// ------------------------------
// 3. ดึงข้อมูลมังงะ
// ------------------------------
$sql_manga = "SELECT * FROM manga WHERE id = ?";
$stmt = $conn->prepare($sql_manga);
$stmt->bind_param("i", $manga_id);
$stmt->execute();
$result_manga = $stmt->get_result();
$manga = $result_manga->fetch_assoc();

if (!$manga) {
    die("Manga not found.");
}

// ------------------------------
// 4. ดึงตอนทั้งหมดของมังงะนี้
// ------------------------------
$sql_chapters = "SELECT * FROM chapters WHERE manga_id = ? ORDER BY chapter_number ASC";
$stmt_ch = $conn->prepare($sql_chapters);
$stmt_ch->bind_param("i", $manga_id);
$stmt_ch->execute();
$result_ch = $stmt_ch->get_result();
$chapters = $result_ch->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($manga['title']) ?></title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'phpinclude/header.php'; ?>

<main style="padding: 20px;">
    <h1><?= htmlspecialchars($manga['title']) ?></h1>
    <p><strong>ผู้แต่ง:</strong> <?= htmlspecialchars($manga['author']) ?></p>
    <p><?= nl2br(htmlspecialchars($manga['description'])) ?></p>

    <h2>ตอนทั้งหมด</h2>
    <ul>
        <?php foreach($chapters as $chapter): ?>
            <li>
                <a href="phpinclude/read.php?chapter_id=<?= $chapter['id'] ?>">
                    ตอน <?= $chapter['chapter_number'] ?>: <?= htmlspecialchars($chapter['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<?php include 'phpinclude/footer.php'; ?>

</body>
</html>
