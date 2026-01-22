<?php
// newest.php
session_start();
require_once 'phpinclude/functions.php';
$conn = dbConnect(); // เชื่อมต่อ DB

// ตัวอย่าง 7 ผลงานล่าสุดแบบ static
$samplePosts = [
    ['title'=>'はなばな落書きまとめ','author'=>'はる','img'=>'assets/manga1-1.jpg','link'=>'read-manga.php','views'=>3212,'likes'=>471],
    ['title'=>'春名ヒスイ','author'=>'ファイカミン.','img'=>'assets/manga3-1.jpg','link'=>'a-read-manga.php','views'=>2875,'likes'=>351],
    ['title'=>'【お知らせ】6周年＆新婚ミニ漫画','author'=>'色のん','img'=>'assets/manga4-1.jpg','link'=>'b-read-manga.php','views'=>4219,'likes'=>632],
    ['title'=>'C105 新刊『Whiteout』サンプル','author'=>'みふじやまい💊新刊委托中','img'=>'assets/manga5-1.jpg','link'=>'c-read-manga.php','views'=>3812,'likes'=>512],
    ['title'=>'อ้อนรักหมอขา','author'=>'พยอล','img'=>'assets/novel1.jpg','link'=>'read-novel.php?id=1','views'=>1345,'likes'=>245],
    ['title'=>'พิษรักนายมาเฟียวิศวะ','author'=>'Duck bell','img'=>'assets/novel2.jpg','link'=>'a-read-novel.php?id=1','views'=>980,'likes'=>198],
    ['title'=>'เมียพลาดรัก','author'=>'นามปากกาดาวเหนือ','img'=>'assets/novel3.jpg','link'=>'b-read-novel.php?id=1','views'=>765,'likes'=>123],
];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-key="newest">ใหม่ล่าสุด | LightTooN</title>
    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/home.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/content.css?v=<?= time() ?>">
</head>
<body>

<?php include 'phpinclude/header.php'; ?>

<main class="main-content" style="padding:20px;">
    <h1 data-key="newest">ใหม่ล่าสุด</h1>

    <!-- ================= NEWEST POSTS STRIP ================= -->
    <section class="strip" id="newest-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px;">
    </section>
</main>

<?php include 'phpinclude/footer.php'; ?>

<script>
    const samplePosts = <?php echo json_encode($samplePosts, JSON_UNESCAPED_UNICODE); ?>;
    const newestList = document.getElementById('newest-list');

    samplePosts.forEach((p, i) => {
        const a = document.createElement('a');
        a.href = p.link;
        a.className = 'card';
        a.style.textDecoration = 'none';
        a.innerHTML = `
            <img src="${p.img}" alt="${p.title}" style="width:100%; height:250px; object-fit:cover;">
            <div class="overlay" data-key="details">ดูรายละเอียด</div>
            <h3>${p.title}</h3>
            <div class='meta'><span data-key="author">ผู้แต่ง:</span> ${p.author}</div>
            <p style="color: var(--text-600); font-size:12px; margin-top:5px;">
                👁️ ${p.views.toLocaleString()} | 
                <button class="like-btn" data-id="${i}" style="background:none;border:none;cursor:pointer;color:red;font-size:14px;">
                    ❤️ <span class="like-count">${p.likes.toLocaleString()}</span>
                </button>
            </p>
        `;
        newestList.appendChild(a);
    });

    // toggle กดถูกใจ / ยกเลิก
    document.addEventListener("click", function(e) {
        if (e.target.closest(".like-btn")) {
            e.preventDefault(); // กันไม่ให้กดแล้วเปลี่ยนหน้า
            let btn = e.target.closest(".like-btn");
            let countSpan = btn.querySelector(".like-count");
            let currentLikes = parseInt(countSpan.textContent.replace(/,/g,'')); // แปลงเป็นตัวเลข
            let isLiked = btn.classList.contains("liked");

            if (isLiked) {
                countSpan.textContent = (currentLikes - 1).toLocaleString();
                btn.classList.remove("liked");
            } else {
                countSpan.textContent = (currentLikes + 1).toLocaleString();
                btn.classList.add("liked");
            }
        }
    });
</script>

</body>
</html>

<?php $conn->close(); ?>
