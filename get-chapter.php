<?php
require_once 'phpinclude/functions.php';
$conn = dbConnect();

$novel_id   = isset($_GET['novel']) ? intval($_GET['novel']) : 0;
$chapter_no = isset($_GET['chapter']) ? intval($_GET['chapter']) : 0;

if ($novel_id <= 0 || $chapter_no <= 0) {
    http_response_code(400);
    echo "Invalid request";
    exit;
}

$stmt = $conn->prepare("SELECT content FROM novel_pages WHERE novel_id=? AND page_number=?");
$stmt->bind_param("ii", $novel_id, $chapter_no);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo nl2br(htmlspecialchars($row['content']));
} else {
    echo "ไม่พบเนื้อหาตอนนี้";
}
