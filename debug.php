<?php
// ไฟล์นี้ใช้เพื่อแก้ไขปัญหาและตรวจสอบการเชื่อมต่อฐานข้อมูล
// กรุณาเปลี่ยนชื่อฐานข้อมูล, ผู้ใช้, และรหัสผ่าน
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lighttoon_db"; // ตรวจสอบให้แน่ใจว่าชื่อนี้ถูกต้อง

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

echo "<h1>การเชื่อมต่อสำเร็จ!</h1>";
echo "<p>PHP กำลังเชื่อมต่อกับฐานข้อมูล: <strong>" . $dbname . "</strong></p>";

// ลอง Query เพื่อดูโครงสร้างของตาราง posts
$sql = "DESCRIBE posts";
$result = $conn->query($sql);

if ($result) {
    echo "<h2>โครงสร้างของตาราง 'posts':</h2>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<thead><tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . ($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>ไม่สามารถอธิบายตาราง 'posts' ได้ กรุณาตรวจสอบว่าตารางมีอยู่จริง: " . $conn->error . "</p>";
}

$conn->close();
?>
