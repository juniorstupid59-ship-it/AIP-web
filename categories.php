<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lighttoon";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ดึงหมวดหมู่จากฐานข้อมูล
$categories = [];
$sql = "SELECT * FROM categories ORDER BY name ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories - LightTooN</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'phpinclude/header.php'; ?>

<main style="padding: 20px;">
    <h1 data-key="categories">หมวดหมู่</h1>
    <div class="categories-container" style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
        <?php if(count($categories) > 0): ?>
            <?php foreach($categories as $cat): ?>
                <a href="category.php?id=<?= $cat['id'] ?>" class="category-card" 
                   style="background: var(--card); color: var(--text-100); padding: 20px; border-radius: var(--r-lg); text-decoration: none; flex: 1 0 200px; text-align: center; box-shadow: var(--shadow-2); transition: transform 0.25s;">
                    <h3><?= htmlspecialchars($cat['name']) ?></h3>
                    <p><?= htmlspecialchars($cat['description']) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีหมวดหมู่ให้แสดง</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'phpinclude/footer.php'; ?>

<script>
// Hover effect ให้ card
document.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('mouseenter', () => card.style.transform = 'scale(1.05)');
    card.addEventListener('mouseleave', () => card.style.transform = 'scale(1)');
});
</script>
</body>
</html>
