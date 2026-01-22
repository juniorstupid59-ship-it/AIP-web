<?php
session_start();
require 'phpinclude/db_connect.php';
require 'phpinclude/functions.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title data-key="novel">นิยาย | LightTooN</title>

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/home.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/content.css?v=<?= time() ?>">
</head>
<body>

<?php include 'phpinclude/header.php'; ?>

<main class="main-content" style="padding:20px;">
    <h1 data-key="novel">นิยาย</h1>

    <!-- ================= FEATURED NOVEL STRIP ================= -->
    <section class="strip" id="featured-novel" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px;">
    </section>
</main>

<?php include 'phpinclude/footer.php'; ?>

<!-- JS -->
<script>
    // Sample novel list
    const sampleNovel = [
        {title:'อ้อนรักหมอขา', author:'พยอล', img:'assets/novel1.jpg', link:'read-novel.php?id=1', views:1345, likes:245},
        {title:'พิษรักนายมาเฟียวิศวะ', author:'Duck bell', img:'assets/novel2.jpg', link:'a-read-novel.php?id=1', views:980, likes:198},
        {title:'เมียพลาดรัก', author:'นามปากกาดาวเหนือ', img:'assets/novel3.jpg', link:'b-read-novel.php?id=1', views:765, likes:123},
        {title:'รักครั้งนั้นที่ฉันทำลาย', author:'ฝนเมษา', img:'assets/novel4.jpg', link:'c-read-novel.php?id=1', views:1142, likes:276},
        {title:'シャツのまま海に飛び込む', author:'辻', img:'assets/novel5.jpg', link:'#', views:821, likes:132},
        {title:'【書籍】９巻予約開始＆ジュニア文庫１巻発売中', author:'暁', img:'assets/novel6.jpg', link:'#', views:995, likes:187},
        {title:'名作のタイトルを最近のラノベみたいにしてみた', author:'みなもとあるた', img:'assets/novel7.jpg', link:'#', views:678, likes:101}
    ];

    const featuredNovel = document.getElementById('featured-novel');

    sampleNovel.forEach((n, i) => {
        const a = document.createElement('a');
        a.href = n.link;
        a.className = 'card';
        a.style.textDecoration = 'none';
        a.innerHTML = `
            <img src="${n.img}" alt="${n.title}" style="width:100%; height:250px; object-fit:cover;">
            <div class="overlay" data-key="details">ดูรายละเอียด</div>
            <h3>${n.title}</h3>
            <div class='meta'><span data-key="author">ผู้แต่ง:</span> ${n.author}</div>
            <p style="color: var(--text-600); font-size:12px; margin-top:5px;">
                👁️ ${n.views} | 
                <button class="like-btn" data-id="${i}" style="background:none;border:none;cursor:pointer;color:red;font-size:14px;">
                    ❤️ <span class="like-count">${n.likes}</span>
                </button>
            </p>
        `;
        featuredNovel.appendChild(a);
    });

    // toggle กดถูกใจ / ยกเลิก
    document.addEventListener("click", function(e) {
        if (e.target.closest(".like-btn")) {
            e.preventDefault(); // ป้องกันไม่ให้กดแล้วเปลี่ยนหน้า
            let btn = e.target.closest(".like-btn");
            let countSpan = btn.querySelector(".like-count");
            let currentLikes = parseInt(countSpan.textContent);
            let isLiked = btn.classList.contains("liked");

            if (isLiked) {
                countSpan.textContent = currentLikes - 1;
                btn.classList.remove("liked");
            } else {
                countSpan.textContent = currentLikes + 1;
                btn.classList.add("liked");
            }
        }
    });
</script>

</body>
</html>
