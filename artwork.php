<?php
session_start();

// โหลดฟังก์ชันและเชื่อมต่อฐานข้อมูล
require 'phpinclude/db_connect.php';
require 'phpinclude/functions.php';

// ตรวจสอบการล็อกอิน (สามารถเอาไปใช้ JS)
$isLoggedIn = function_exists('isLoggedIn') ? isLoggedIn() : false;

// ดึงหมวดหมู่
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");

// ดึงงานอาร์ตทั้งหมด
$artworks = $conn->query("
    SELECT a.*, c.name AS category_name 
    FROM artworks a 
    LEFT JOIN categories c ON a.category_id = c.id 
    ORDER BY a.popularity DESC
");

// รับค่าตัวกรองหมวดหมู่
$cat_filter = isset($_GET['cat']) ? intval($_GET['cat']) : 0;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title data-key="artwork">งานศิลปะ | LightTooN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/content.css?v=<?php echo time(); ?>">

    <!-- ส่งตัวแปร PHP → JS -->
    <script>
        const IS_LOGGED_IN = <?php echo json_encode($isLoggedIn); ?>;
        const CAT_FILTER = <?php echo json_encode($cat_filter); ?>;
    </script>
</head>

<body>

    <?php include 'phpinclude/header.php'; ?>

    <main class="main-content" style="padding: 20px;">
        <h1 data-key="artwork">งานศิลปะ</h1>

        <!-- งานอาร์ต (ตัวอย่าง 8 อัน) -->
        <section class="artwork-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
            <?php
            // สร้าง array ตัวอย่าง
            $sample_artworks = [
                ['title'=>'🏖️','artist'=>'Rega','views'=>1245,'likes'=>237,'img'=>'assets/art1.jpg','full'=>'assets/art1-full.jpg'],
                ['title'=>'彼シャツスズミ','artist'=>'Flippy','views'=>875,'likes'=>181,'img'=>'assets/art2.png','full'=>'assets/art2-full.jpg'],
                ['title'=>'Suit','artist'=>'たっくす','views'=>560,'likes'=>117,'img'=>'assets/art3.png','full'=>'assets/art3-full.png'],
                ['title'=>'78日目 朱城ルミ','artist'=>'カフェナミ','views'=>1025,'likes'=>223,'img'=>'assets/art4.jpg','full'=>'assets/art4-full.png'],
                ['title'=>'1日目','artist'=>'殘夜 ZANYA','views'=>894,'likes'=>145,'img'=>'assets/art5.jpg','full'=>'assets/art5-full.png'],
                ['title'=>'のせさんと下校','artist'=>'アクエ','views'=>760,'likes'=>121,'img'=>'assets/art6.jpg','full'=>'assets/art6-full.png'],
                ['title'=>'ｵﾏｴﾓﾜﾗｴｯ!','artist'=>'りんごゼリー','views'=>982,'likes'=>211,'img'=>'assets/art7.jpg','full'=>'assets/art7-full.png'],
                ['title'=>'白尾エリ','artist'=>'華葡。（かぶ。）','views'=>1121,'likes'=>234,'img'=>'assets/art8.jpg','full'=>'assets/art8-full.png'],
                ['title'=>'「おいで♡」','artist'=>'NANAKI24','views'=>3455,'likes'=>452,'img'=>'assets/art9.jpg','full'=>'assets/art9-full.png'],
                ['title'=>'かっこいい🍃','artist'=>'aoha','views'=>1568,'likes'=>234,'img'=>'assets/art10.jpg','full'=>'assets/art10-full.png'],
                ['title'=>'トワ様','artist'=>'青乃むめい','views'=>853,'likes'=>132,'img'=>'assets/art11.jpg','full'=>'assets/art11-full.png'],
                ['title'=>'やっっっと会えたね…','artist'=>'るぅ','views'=>942,'likes'=>141,'img'=>'assets/art12.jpg','full'=>'assets/art12-full.png'],
                ['title'=>'姉と妹たち','artist'=>'knt','views'=>3451,'likes'=>348,'img'=>'assets/art13.jpg','full'=>'assets/art13-full.png'],
                ['title'=>'スズミ生誕祭 〜厳選されし観客〜','artist'=>'シロパンダクロ','views'=>983,'likes'=>149,'img'=>'assets/art14.jpg','full'=>'assets/art14-full.png'],
            ];

            foreach($sample_artworks as $i => $art): ?>
            <div class="art-card" style="background: var(--card); border-radius: var(--r-lg); box-shadow: var(--shadow-2); overflow: hidden; text-align: center;">
                <img src="<?= $art['img'] ?>" 
                     data-full="<?= $art['full'] ?>" 
                     alt="<?= $art['title'] ?>" 
                     class="art-img" 
                     data-index="<?= $i ?>" 
                     style="width:100%; height:200px; object-fit:cover; cursor:pointer;">
                <div class="art-info" style="padding: 10px;">
                    <h3><?= $art['title'] ?></h3>
                    <p style="color: var(--text-400); font-size:14px;">
                        <span data-key="artist">นักวาด</span>: <?= $art['artist'] ?>
                    </p>
                    <p style="color: var(--text-600); font-size:12px;">
                        👁️ <?= $art['views'] ?> | 
                        <button class="like-btn" data-id="<?= $i ?>" style="background:none;border:none;cursor:pointer;color:red;font-size:14px;">
                            ❤️ <span class="like-count"><?= $art['likes'] ?></span>
                        </button>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </section>
    </main>

    <?php include 'phpinclude/footer.php'; ?>

    <!-- Pop-up ดูภาพเต็ม -->
    <div id="img-popup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); align-items:center; justify-content:center; z-index:1000;">
        <span id="popup-close" style="position:absolute; top:20px; right:30px; font-size:30px; color:white; cursor:pointer;">&times;</span>
        <img id="popup-img" src="" alt="full" style="max-width:90%; max-height:90%; border-radius:10px; box-shadow:0 0 20px rgba(0,0,0,0.5);">
    </div>

    <!-- JS -->
    <script src="js/content.js"></script>
    <script>
        // ฟังก์ชัน toggle กดถูกใจ / ยกเลิก
        document.querySelectorAll(".like-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                let countSpan = this.querySelector(".like-count");
                let currentLikes = parseInt(countSpan.textContent);
                let isLiked = this.classList.contains("liked");

                if (isLiked) {
                    // ยกเลิกถูกใจ
                    countSpan.textContent = currentLikes - 1;
                    this.classList.remove("liked");
                } else {
                    // กดถูกใจ
                    countSpan.textContent = currentLikes + 1;
                    this.classList.add("liked");
                }
            });
        });

        // Pop-up แสดงภาพ (ใช้ data-full)
        const popup = document.getElementById("img-popup");
        const popupImg = document.getElementById("popup-img");
        const popupClose = document.getElementById("popup-close");

        document.querySelectorAll(".art-img").forEach(img => {
            img.addEventListener("click", function() {
                popup.style.display = "flex";
                popupImg.src = this.dataset.full || this.src; // ถ้ามี full ใช้ full
            });
        });

        popupClose.addEventListener("click", function() {
            popup.style.display = "none";
        });

        popup.addEventListener("click", function(e) {
            if (e.target === popup) popup.style.display = "none";
        });
    </script>

</body>
</html>
