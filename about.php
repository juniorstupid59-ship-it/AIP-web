<?php
// -----------------------------
// Include functions + DB connect
// -----------------------------
require_once 'phpinclude/functions.php';

// Start session ถ้ายังไม่เริ่ม
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------
// จัดการภาษา (EN/TH)
// -----------------------------
$currentLang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['en', 'th'])
    ? $_COOKIE['lang']
    : 'th';

// -----------------------------
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า About Us
// -----------------------------
$about_content_th = '
    <h1 data-key="about_title">เกี่ยวกับ LightTooN</h1>
    <p class="subtitle" data-key="about_subtitle">พื้นที่ที่จินตนาการและโอกาสมาบรรจบกันสำหรับครีเอเตอร์และผู้เสพผลงาน</p>
    <hr>
    
    <h2 data-key="about_vision_heading">วิสัยทัศน์ของเรา</h2>
    <p data-key="about_vision_body">
        เราก่อตั้ง LightTooN ขึ้นมาด้วยความเชื่อที่ว่าศิลปินและครีเอเตอร์ทุกคนควรมีพื้นที่ในการแสดงผลงานอันน่าทึ่งของตนเองให้โลกได้เห็น และในขณะเดียวกัน ผู้คนทั่วโลกก็ควรเข้าถึงศิลปะและเรื่องราวเหล่านั้นได้อย่างง่ายดาย เป้าหมายของเราคือการเป็นศูนย์กลางที่เชื่อมโยงครีเอเตอร์มากความสามารถ เข้ากับคอมมูนิตี้และผู้ชมที่หลงใหลและพร้อมสนับสนุนผลงานของพวกเขา
    </p>

    <h2 data-key="about_for_creators_heading">สำหรับครีเอเตอร์ 🎨</h2>
    <p data-key="about_for_creators_body">
        LightTooN คือสตูดิโอและแกลเลอรีดิจิทัลของคุณ ที่นี่คุณสามารถเผยแพร่ผลงานได้หลากหลายรูปแบบ ไม่ว่าจะเป็นภาพอาร์ตสวยงาม, มังงะที่เต็มไปด้วยเรื่องราวน่าติดตาม, หรือนิยายที่ชวนให้จินตนาการ นอกจากนี้เรายังสร้างช่องทางให้คุณสามารถสร้างรายได้จากผลงานที่คุณรักผ่านการวางขายผลงาน และที่สำคัญที่สุดคือ "ตลาดงาน" (Job Board) ที่เปิดโอกาสให้คุณสามารถรับงานจ้างจากผู้ว่าจ้างที่กำลังมองหาศิลปินฝีมือดี (เช่น งานวาดปกนิยาย) หรือประกาศโปรไฟล์เพื่อให้ผู้คนเข้ามาจ้างงานคุณได้โดยตรง
    </p>

    <h2 data-key="about_for_readers_heading">สำหรับผู้อ่านและแฟนๆ 📖</h2>
    <p data-key="about_for_readers_body">
        เตรียมตัวดำดิ่งสู่จักรวาลแห่งจินตนาการที่ไม่มีที่สิ้นสุด ค้นพบผลงานศิลปะ มังงะ และนิยายเรื่องเยี่ยมจากศิลปินหน้าใหม่และมืออาชีพทั่วประเทศ แพลตฟอร์มของเราถูกออกแบบมาเพื่อมอบประสบการณ์การอ่านที่ดีที่สุด อ่านง่าย สบายตา และยังสามารถมีส่วนร่วมกับครีเอเตอร์ได้โดยตรงผ่านระบบคอมเมนต์และคอมมูนิตี้ นอกจากนี้ คุณยังสามารถสนับสนุนศิลปินที่ชื่นชอบได้โดยตรง หรือแม้กระทั่งว่าจ้างพวกเขาให้มาสร้างสรรค์ผลงานสุดพิเศษสำหรับคุณ
    </p>

    <h2 data-key="about_community_heading">คอมมูนิตี้ของเรา 🌐</h2>
    <p data-key="about_community_body">
        หัวใจสำคัญของ LightTooN คือ "ชุมชน" เราได้สร้างระบบการโพสต์และสื่อสารที่เรียบง่าย ชัดเจน และสะดวกสบาย เพื่อให้ครีเอเตอร์และแฟนๆ สามารถพูดคุย แลกเปลี่ยนความคิดเห็น และสร้างความสัมพันธ์ที่แข็งแกร่งได้อย่างง่ายดาย เราเชื่อว่าการเชื่อมต่อนี้คือพลังที่ช่วยขับเคลื่อนวงการสร้างสรรค์ให้เติบโตต่อไป
    </p>
';

$about_content_en = '
    <h1 data-key="about_title">About LightTooN</h1>
    <p class="subtitle" data-key="about_subtitle">Where imagination and opportunity converge for creators and fans.</p>
    <hr>

    <h2 data-key="about_vision_heading">Our Vision</h2>
    <p data-key="about_vision_body">
        We founded LightTooN on the belief that every artist and creator deserves a space to showcase their incredible work to the world. At the same time, people everywhere should have easy access to that art and those stories. Our goal is to be the central hub that connects talented creators with a community and audience that is passionate and ready to support their work.
    </p>
    
    <h2 data-key="about_for_creators_heading">For Creators 🎨</h2>
    <p data-key="about_for_creators_body">
        LightTooN is your digital studio and gallery. Here, you can publish a wide variety of works, from beautiful art pieces and compelling manga to imaginative novels. We also provide a way for you to monetize your passion by selling your creations. Most importantly, our "Job Board" opens up opportunities for you to take on commissioned work from clients looking for skilled artists (e.g., for a novel cover) or to post your profile to be hired directly.
    </p>

    <h2 data-key="about_for_readers_heading">For Readers & Fans 📖</h2>
    <p data-key="about_for_readers_body">
        Prepare to dive into an endless universe of imagination. Discover amazing art, manga, and novels from new and professional artists across the country. Our platform is designed to provide the best reading experience—easy to read, comfortable on the eyes, and engaging. You can interact directly with creators through our comment and community systems. Furthermore, you can directly support your favorite artists or even hire them to create something special just for you.
    </p>

    <h2 data-key="about_community_heading">Our Community 🌐</h2>
    <p data-key="about_community_body">
        The heart of LightTooN is its community. We have built a simple, clear, and convenient posting and communication system that allows creators and fans to talk, exchange ideas, and build strong relationships with ease. We believe this connection is the power that drives the creative industry forward.
    </p>
';
?>

<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightTooN - About Us</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/policy.css">

</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <?php
        // ตรวจสอบภาษาที่เลือก แล้วแสดงเนื้อหาที่ถูกต้อง
        if ($currentLang == 'en') {
            echo $about_content_en;
        } else {
            echo $about_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>