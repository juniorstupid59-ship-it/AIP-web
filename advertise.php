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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Advertise
// -----------------------------
$advertise_content_th = '
    <h1 data-key="advertise_title">โฆษณากับเรา</h1>
    <p class="subtitle" data-key="advertise_subtitle">เข้าถึงกลุ่มเป้าหมายที่ใช่ในคอมมูนิตี้ของครีเอเตอร์และผู้เสพผลงานสร้างสรรค์</p>
    <hr>
    
    <h2 data-key="advertise_why_heading">ทำไมต้องโฆษณากับ LightTooN?</h2>
    <p data-key="advertise_why_body">
        LightTooN คือแพลตฟอร์มที่รวมตัวศิลปิน นักเขียน นักอ่าน และผู้ที่หลงใหลในวัฒนธรรมป๊อปไว้นับพันคน การลงโฆษณากับเราหมายถึงการนำแบรนด์ สินค้า หรือบริการของคุณไปสู่สายตาของกลุ่มคนที่มีความสนใจเฉพาะทาง มีส่วนร่วมสูง และพร้อมที่จะสนับสนุนสิ่งที่พวกเขารัก
    </p>

    <h2 data-key="advertise_audience_heading">กลุ่มเป้าหมายของเรา</h2>
    <p data-key="advertise_audience_body">
        ผู้ใช้งานของเราประกอบไปด้วยศิลปินดิจิทัล, นักวาดการ์ตูน, นักเขียนนิยาย, นักอ่านตัวยง และแฟนๆ ที่ติดตามผลงานสร้างสรรค์อย่างใกล้ชิด พวกเขาคือกลุ่มคนรุ่นใหม่ที่เปิดรับและมองหาสินค้า บริการ หรือเครื่องมือที่จะช่วยเติมเต็มไลฟ์สไตล์และงานอดิเรกของพวกเขา
    </p>

    <h2 data-key="advertise_options_heading">รูปแบบการโฆษณา</h2>
    <p data-key="advertise_options_body">
        เรามีพื้นที่โฆษณาหลากหลายรูปแบบเพื่อตอบโจทย์แคมเปญของคุณ ไม่ว่าจะเป็นแบนเนอร์ในหน้าหลัก, แบนเนอร์ในหน้าอ่านผลงาน, การโปรโมทโพสต์ในหน้าคอมมูนิตี้ หรือการเป็นสปอนเซอร์ให้กับกิจกรรมพิเศษต่างๆ ของเรา เราพร้อมที่จะทำงานร่วมกับคุณเพื่อสร้างแคมเปญที่เหมาะสมและมีประสิทธิภาพที่สุด
    </p>

    <h2 data-key="advertise_contact_heading">ติดต่อเพื่อลงโฆษณา</h2>
    <p data-key="advertise_contact_body">
        หากคุณสนใจที่จะเป็นส่วนหนึ่งในการเติบโตไปพร้อมกับเราและต้องการเข้าถึงคอมมูนิตี้ที่มีชีวิตชีวาแห่งนี้ กรุณาติดต่อทีมการตลาดของเราเพื่อขอข้อมูลเพิ่มเติมและใบเสนอราคาได้ที่อีเมล: <a href="mailto:marketing@lighttoon.com">marketing@lighttoon.com</a>
    </p>
';

$advertise_content_en = '
    <h1 data-key="advertise_title">Advertise with Us</h1>
    <p class="subtitle" data-key="advertise_subtitle">Reach the right audience in a community of creators and creative enthusiasts.</p>
    <hr>

    <h2 data-key="advertise_why_heading">Why Advertise with LightTooN?</h2>
    <p data-key="advertise_why_body">
        LightTooN is a platform that brings together thousands of artists, writers, readers, and pop culture enthusiasts. Advertising with us means putting your brand, product, or service in front of a highly engaged, niche audience that is ready to support what they love.
    </p>
    
    <h2 data-key="advertise_audience_heading">Our Audience</h2>
    <p data-key="advertise_audience_body">
        Our user base consists of digital artists, comic illustrators, novelists, avid readers, and dedicated fans who closely follow creative works. They are a modern demographic, open to and actively looking for products, services, or tools that can enhance their lifestyle and hobbies.
    </p>

    <h2 data-key="advertise_options_heading">Advertising Options</h2>
    <p data-key="advertise_options_body">
        We offer various advertising formats to suit your campaign needs, including homepage banners, banners on content-reading pages, promoted posts in the community feed, or sponsorship of our special events. We are ready to work with you to create the most suitable and effective campaign.
    </p>

    <h2 data-key="advertise_contact_heading">Contact Us for Advertising</h2>
    <p data-key="advertise_contact_body">
        If you are interested in growing with us and reaching this vibrant community, please contact our marketing team for more information and a rate card at: <a href="mailto:marketing@lighttoon.com">marketing@lighttoon.com</a>
    </p>
';
?>

<!-- =========================
     HTML โครงสร้างหน้าเพจ
========================= -->
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightTooN - Advertise with Us</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/policy.css">

</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <?php
        // ตรวจสอบภาษาที่เลือก แล้วแสดงเนื้อหาที่ถูกต้อง
        if ($currentLang == 'en') {
            echo $advertise_content_en;
        } else {
            echo $advertise_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
