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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Careers
// -----------------------------
$careers_content_th = '
    <h1 data-key="careers_title">ร่วมงานกับเรา</h1>
    <p class="subtitle" data-key="careers_subtitle">มาเป็นส่วนหนึ่งของทีมที่ขับเคลื่อนวงการสร้างสรรค์ให้ก้าวไปข้างหน้า</p>
    <hr>
    
    <h2 data-key="careers_culture_heading">วัฒนธรรมองค์กรของเรา</h2>
    <p data-key="careers_culture_body">
        ที่ LightTooN เราเชื่อว่าพลังขับเคลื่อนที่ยิ่งใหญ่ที่สุดมาจากความหลงใหล ทีมงานของเราคือกลุ่มคนที่มีความคิดสร้างสรรค์ รักในเทคโนโลยี และมุ่งมั่นที่จะสร้างสรรค์แพลตฟอร์มที่ดีที่สุดเพื่อคอมมูนิตี้ เราทำงานร่วมกันอย่างใกล้ชิด สนับสนุนไอเดียใหม่ๆ และให้ความสำคัญกับสมดุลระหว่างการทำงานและชีวิตส่วนตัว
    </p>

    <h2 data-key="careers_why_join_heading">ทำไมคุณควรเข้าร่วมกับเรา?</h2>
    <p data-key="careers_why_join_body">
        คุณจะได้ทำงานในสภาพแวดล้อมที่เต็มไปด้วยแรงบันดาลใจ มีโอกาสพัฒนาทักษะและความสามารถอย่างไร้ขีดจำกัด และได้เป็นส่วนหนึ่งในการสร้างผลกระทบเชิงบวกให้กับวงการครีเอเตอร์ไทย เรามองหาผู้ที่มีแพสชั่นและพร้อมที่จะเติบโตไปพร้อมกับเรา
    </p>

    <h2 data-key="careers_positions_heading">ตำแหน่งงานที่เปิดรับ</h2>
    <p data-key="careers_positions_body">
        ในขณะนี้เรายังไม่มีตำแหน่งงานว่างอย่างเป็นทางการ แต่เรามองหาคนเก่งมาร่วมทีมอยู่เสมอ หากคุณเชื่อว่าคุณมีทักษะและความมุ่งมั่นที่จะสร้างความเปลี่ยนแปลงในวงการนี้ โปรดส่งประวัติ (Resume/CV) และแฟ้มผลงาน (Portfolio) ของคุณมาให้เราพิจารณา
    </p>

    <h2 data-key="careers_apply_heading">ส่งใบสมัครของคุณ</h2>
    <p data-key="careers_apply_body">
        สนใจร่วมเป็นส่วนหนึ่งของครอบครัว LightTooN ใช่ไหม? ส่งเอกสารการสมัครของคุณมาที่อีเมล: <a href="mailto:careers@lighttoon.com">careers@lighttoon.com</a> เราจะติดต่อกลับไปหากมีตำแหน่งงานที่เหมาะสมกับคุณ
    </p>
';

$careers_content_en = '
    <h1 data-key="careers_title">Careers at LightTooN</h1>
    <p class="subtitle" data-key="careers_subtitle">Be a part of the team that drives the creative industry forward.</p>
    <hr>

    <h2 data-key="careers_culture_heading">Our Corporate Culture</h2>
    <p data-key="careers_culture_body">
        At LightTooN, we believe the greatest driving force comes from passion. Our team is a group of creative individuals who love technology and are dedicated to building the best platform for the community. We work closely together, support new ideas, and value work-life balance.
    </p>
    
    <h2 data-key="careers_why_join_heading">Why Should You Join Us?</h2>
    <p data-key="careers_why_join_body">
        You will work in an inspiring environment, have limitless opportunities to develop your skills and abilities, and be part of making a positive impact on the Thai creator industry. We are looking for passionate individuals who are ready to grow with us.
    </p>

    <h2 data-key="careers_positions_heading">Open Positions</h2>
    <p data-key="careers_positions_body">
        Currently, we do not have any official vacancies. However, we are always on the lookout for talented people to join our team. If you believe you have the skills and determination to make a difference in this industry, please send us your Resume/CV and Portfolio for consideration.
    </p>

    <h2 data-key="careers_apply_heading">Submit Your Application</h2>
    <p data-key="careers_apply_body">
        Interested in becoming a part of the LightTooN family? Send your application documents to our email: <a href="mailto:careers@lighttoon.com">careers@lighttoon.com</a>. We will get in touch if a suitable position becomes available.
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
    <title>LightTooN - Careers</title>

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
            echo $careers_content_en;
        } else {
            echo $careers_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
