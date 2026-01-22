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
    <title data-key="manga">มังงะ | LightTooN</title>

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/home.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/content.css?v=<?= time() ?>">
</head>
<body>

<?php include 'phpinclude/header.php'; ?>

<main class="main-content" style="padding:20px;">
    <h1 data-key="manga">มังงะ</h1>

    <!-- ================= FEATURED MANGA STRIP ================= -->
    <section class="strip" id="featured-manga" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:20px;">
    </section>
</main>

<?php include 'phpinclude/footer.php'; ?>

<!-- JS -->
<script>
    // Sample manga list
    const sampleManga = [
        {title:'はなばな落書きまとめ', author:'はる', img:'assets/manga1-1.jpg', link:'read-manga.php', views:3212, likes:471},
        {title:'春名ヒスイ', author:'ファイカミン.', img:'assets/manga3-1.jpg', link:'a-read-manga.php', views:2875, likes:351},
        {title:'【お知らせ】6周年＆新婚ミニ漫画', author:'色のん', img:'assets/manga4-1.jpg', link:'b-read-manga.php', views:4219, likes:632},
        {title:'C105 新刊『Whiteout』サンプル', author:'みふじやまい💊新刊委託中', img:'assets/manga5-1.jpg', link:'c-read-manga.php', views:3821, likes:512},
        {title:'C105 【販売終了】VGGC8th新刊【再販なし】', author:'ﾓﾝｺﾞﾘﾆｬﾝ(キサダ)', img:'assets/manga8-1.jpg', link:'#', views:1894, likes:244},
        {title:'C105 【web再録】キサルミ本（おかわりは月の裏側で）', author:'はやくねなさい', img:'assets/manga9-1.jpg', link:'#', views:2124, likes:283},
        {title:'C105 献血コラボありがとう', author:'織夏', img:'assets/manga0-1.jpg', link:'#', views:1582, likes:211},
    ];

    const featuredManga = document.getElementById('featured-manga');

    sampleManga.forEach((m, i) => {
        const a = document.createElement('a');
        a.href = m.link;
        a.className = 'card';
        a.style.textDecoration = 'none';
        a.innerHTML = `
            <img src="${m.img}" alt="${m.title}" style="width:100%; height:250px; object-fit:cover;">
            <div class="overlay" data-key="details">ดูรายละเอียด</div>
            <h3>${m.title}</h3>
            <div class='meta'><span data-key="author">ผู้แต่ง:</span> ${m.author}</div>
            <p style="color: var(--text-600); font-size:12px; margin-top:5px;">
                👁️ ${m.views} | 
                <button class="like-btn" data-id="${i}" style="background:none;border:none;cursor:pointer;color:red;font-size:14px;">
                    ❤️ <span class="like-count">${m.likes}</span>
                </button>
            </p>
        `;
        featuredManga.appendChild(a);
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
