<?php
// เรียกก่อน HTML ทั้งหมด
include 'phpinclude/functions.php'; // ต้องแน่ใจว่าไฟล์นี้ถูกต้อง ไม่มี error
include 'phpinclude/header.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LightTooN – Home</title>

    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/home.css?v=<?php echo time(); ?>">

    <!-- สร้างตัวแปร JS จาก PHP -->
    <script>
        <?php
        // ตรวจสอบฟังก์ชัน isLoggedIn() ว่ามีอยู่จริง
        if (function_exists('isLoggedIn')) {
            echo "const IS_LOGGED_IN = " . (isLoggedIn() ? 'true' : 'false') . ";";
        } else {
            echo "const IS_LOGGED_IN = false;";
        }
        ?>
    </script>
</head>
<body>

    <!-- ================= BANNER ================= -->
<div class="container mt-16 banner" style="background-image: url('/LightTooN/assets/manga1-1.jpg');">
    <div class="banner-content">
        <!-- หัวข้อเป็นชื่อเรื่อง -->
        <h2 data-key="headline">はなばな落書きまとめ</h2>
        <p data-key="hero_desc">อ่านตอนล่าสุดก่อนใคร พร้อมคอมเมนท์และเก็บเป็นรายการโปรด</p>
        <div class="mt-12 flex gap-12">
            <!-- ปุ่มอ่านตอนล่าสุด พร้อม data-key สำหรับภาษาไทย/อังกฤษ -->
            <button class="btn" data-key="read_latest" onclick="window.location.href='read-manga.php';">
                อ่านตอนล่าสุด
            </button>
        </div>
    </div>
</div>





        <aside class="sidebar">
            <h4 data-key="popular_today">ยอดนิยมวันนี้</h4>
            <div id="trend-list">
                <div class="trend">
                    <div class="badge">1</div>
                    <div style="flex:1">Infinite Night — ตอน 45</div>
                    <div style="font-size:12px;color:#aaa;"><span data-key="read">อ่าน</span> 12.4K</div>
                </div>
                <div class="trend">
                    <div class="badge">2</div>
                    <div style="flex:1">Oceanheart — ตอน 12</div>
                    <div style="font-size:12px;color:#aaa;"><span data-key="read">อ่าน</span> 9.8K</div>
                </div>
                <div class="trend">
                    <div class="badge">3</div>
                    <div style="flex:1">Mage of Alley — ตอน 7</div>
                    <div style="font-size:12px;color:#aaa;"><span data-key="read">อ่าน</span> 8.2K</div>
                </div>
            </div>
            <button id="show-more" class="btn primary mt-4" data-key="see_more">ดูเพิ่มเติม</button>
        </aside>
    </div>

    <!-- ================= FEATURED STRIP ================= -->
    <main class="container mt-16">
        <h3 data-key="recommended_for_you">แนะนำสำหรับคุณ</h3>
        <div class="strip" id="featured"></div>

        <!-- ================= POSTS FEED ================= -->
        <h3 class="mt-8" data-key="all">ทั้งหมด</h3>
        <div id="feed" class="feed"></div>
    </main>

    <!-- ================= FOOTER ================= -->
    <?php include 'phpinclude/footer.php'; ?>

    <!-- ================= JS ================= -->
    <!-- ================= JS ================= -->
<script src="js/home.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    // Sample posts
    const posts = [
        {
            author: "こぎこぎ",
            content: "無題",
            img: "assets/post1.jpg",
            views: 3200,
            likes: 273,
            comments: [
                {user:"Inferno", text:"Your drawing is so good!!"},
                {user:"Barb", text:"Keep on working!"}
            ]
        },
        {
            author: "TLSLime",
            content: "シグレ",
            img: "assets/post2.png",
            views: 2890,
            likes: 231,
            comments: [
                {user:"Alex", text:"I always love your arts!"}
            ]
        }
    ];

    const feed = document.getElementById("feed");

    // ฟังก์ชันสร้างโพสต์
    function createPost(p) {
        const div = document.createElement("div");
        div.className = "feed-post";

        div.innerHTML = `
            <div class="post-header">
                <div class="author-info">
                    <div class="author-name">${p.author}</div>
                    <div class="post-meta">${p.views} views</div>
                </div>
            </div>
            <div class="post-content">
                <p>${p.content}</p>
                ${p.img ? `<div class="post-image"><img src="${p.img}" alt="Post Image"></div>` : ""}
            </div>
            <div class="post-actions">
                <button class="btn ghost like-btn">❤️ ${p.likes}</button>
                <button class="btn ghost comment-btn">💬 ${p.comments.length}</button>
            </div>
        `;

        // Like button toggle
        div.querySelector(".like-btn").addEventListener("click", e=>{
            e.target.classList.toggle("active");
        });

        // Comment pop-up
        div.querySelector(".comment-btn").addEventListener("click", ()=>openComments(p.comments));

        feed.appendChild(div);
    }

    // ฟังก์ชันเปิดหน้าคอมเมนต์
    function openComments(comments) {
        const overlay = document.createElement("div");
        overlay.className = "overlay active";
        overlay.style.cssText = `
            position:fixed;
            top:0; left:0; right:0; bottom:0;
            background: rgba(0,0,0,0.5);
            display:flex;
            justify-content:center;
            align-items:flex-start;
            z-index:1000;
        `;

        overlay.innerHTML = `
            <div class="comment-popup" style="
                max-width:400px;
                margin: 80px auto;
                background: var(--bg-secondary);
                padding: 20px;
                border-radius: 12px;
                color: var(--text-100);
                position: relative;
            ">
                <button id="closeComments" style="
                    position:absolute;
                    top:10px;
                    right:10px;
                    background:none;
                    border:none;
                    font-size:20px;
                    cursor:pointer;
                    color: var(--text-100);
                ">✖</button>
                <h3>Comments</h3>
                <div class="comments-list">
                    ${comments.map(c=>`<p><strong>${c.user}:</strong> ${c.text}</p>`).join('')}
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        overlay.querySelector("#closeComments").addEventListener("click", ()=> {
            document.body.removeChild(overlay);
        });

        overlay.addEventListener("click", e=>{
            if(e.target === overlay) document.body.removeChild(overlay);
        });
    }

    // สร้างโพสต์ทั้งหมด
    posts.forEach(createPost);
    // ===============================
    // ทำให้รูปใน feed กดแล้วดูเต็มจอได้
    // ===============================
    feed.addEventListener("click", e => {
        if (e.target.tagName === "IMG") {
            const imgSrc = e.target.getAttribute("src");

            const overlay = document.createElement("div");
            overlay.style.cssText = `
                position: fixed;
                top:0; left:0; right:0; bottom:0;
                background: rgba(0,0,0,0.8);
                display:flex;
                justify-content:center;
                align-items:center;
                z-index:2000;
            `;

            overlay.innerHTML = `
                <img src="${imgSrc}" style="max-width:90%; max-height:90%; border-radius:8px; box-shadow:0 0 20px #000;">
            `;

            document.body.appendChild(overlay);

            overlay.addEventListener("click", () => {
                document.body.removeChild(overlay);
            });
        }
    });
});// ===============================
    // ทำให้รูปใน feed กดแล้วดูเต็มจอได้
    // ===============================
    feed.addEventListener("click", e => {
        if (e.target.tagName === "IMG") {
            const imgSrc = e.target.getAttribute("src");

            const overlay = document.createElement("div");
            overlay.style.cssText = `
                position: fixed;
                top:0; left:0; right:0; bottom:0;
                background: rgba(0,0,0,0.8);
                display:flex;
                justify-content:center;
                align-items:center;
                z-index:2000;
            `;

            overlay.innerHTML = `
                <img src="${imgSrc}" style="max-width:90%; max-height:90%; border-radius:8px; box-shadow:0 0 20px #000;">
            `;

            document.body.appendChild(overlay);

            overlay.addEventListener("click", () => {
                document.body.removeChild(overlay);
            });
        }
    });
</script>


    <script>
        // Featured sample data
        const sample = [
            {title:'はなばな落書きまとめ',author:'はる',img:'assets/manga1-1.jpg', link: 'read-manga.php'},
            {title:'春名ヒスイ',author:'ファイカミン.',img:'assets/manga3-1.jpg', link: 'a-read-manga.php'},
            {title:'【お知らせ】6周年＆新婚ミニ漫画',author:'色のん',img:'assets/manga4-1.jpg', link: 'b-read-manga.php'},
            {title:'C105 新刊『Whiteout』サンプル',author:'みふじやまい💊新刊委託中',img:'assets/manga5-1.jpg', link: 'c-read-manga.php'}
        ];

        const featured = document.getElementById('featured');
        sample.forEach(s=>{
            // สร้าง element 'a' เพื่อให้สามารถคลิกได้
            const c = document.createElement('a');
            c.href = s.link; // ตั้งค่า URL ที่จะไป
            c.className='card';
            c.style.textDecoration = 'none'; // เพื่อไม่ให้มีขีดเส้นใต้ตามปกติของ a tag

            c.innerHTML=`<img src="${s.img}" alt="${s.title}">
                         <div class="overlay" data-key="details">ดูรายละเอียด</div>
                         <h3>${s.title}</h3>
                         <div class='meta'><span data-key="author">ผู้แต่ง:</span> ${s.author}</div>`;
            featured.appendChild(c);
        });

        // Show more trends
        document.getElementById('show-more').addEventListener('click',()=>{
            const trends=[
                {title:"Shadow Realm — ตอน 9", read:"7.5K"},
                {title:"Starbound Saga — ตอน 15", read:"6.3K"},
                {title:"Legend of Void — ตอน 3", read:"5.8K"}
            ];
            const trendList=document.getElementById("trend-list");
            trends.forEach((t,i)=>{
                const div=document.createElement("div");
                div.className="trend";
                div.innerHTML=`<div class="badge">${i+4}</div>
                               <div style="flex:1">${t.title}</div>
                               <div style="font-size:12px;color:#aaa;">
                                  <span data-key="read">อ่าน</span> ${t.read}
                               </div>`;
                trendList.appendChild(div);
            });
            document.getElementById('show-more').style.display='none';
        });
    </script>
</body>
</html>
