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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Terms of Service
// -----------------------------
$terms_content_th = '
    <h1 data-key="terms_title">ข้อตกลงและเงื่อนไขการใช้งาน</h1>
    <p class="subtitle" data-key="terms_subtitle">โปรดอ่านข้อตกลงเหล่านี้อย่างละเอียดก่อนใช้งาน LightTooN</p>
    <hr>

    <h2 data-key="terms_section1_heading">1. การสมัครสมาชิก</h2>
    <p data-key="terms_section1_body">
        ผู้ใช้งานต้องลงทะเบียนด้วยข้อมูลที่ถูกต้องและเป็นความจริง 
        การสมัครสมาชิกถือว่าผู้ใช้งานยอมรับข้อตกลงและนโยบายทั้งหมดของเว็บไซต์
    </p>

    <h2 data-key="terms_section2_heading">2. การเผยแพร่และการขายผลงาน</h2>
    <p data-key="terms_section2_body">
        ศิลปินที่นำผลงานมาลงเผยแพร่ยังคงถือสิทธิ์ในผลงานทั้งหมด 
        และสามารถเลือกขายผลงานผ่านแพลตฟอร์มได้ 
        เว็บไซต์จะทำหน้าที่เป็นสื่อกลางในการเชื่อมต่อระหว่างผู้ซื้อและผู้ขาย
    </p>

    <h2 data-key="terms_section3_heading">3. ความรับผิดชอบของผู้ใช้งาน</h2>
    <p data-key="terms_section3_body">
        ผู้ใช้งานต้องไม่เผยแพร่ผลงานที่ละเมิดลิขสิทธิ์ กฎหมาย 
        หรือเนื้อหาที่ไม่เหมาะสม หากมีการละเมิดเกิดขึ้น 
        ผู้ใช้งานจะต้องรับผิดชอบต่อผลกระทบที่ตามมา
    </p>

    <h2 data-key="terms_section4_heading">4. การซื้อขายและการชำระเงิน</h2>
    <p data-key="terms_section4_body">
        การซื้อขายผลงานทั้งหมดจะดำเนินการผ่านระบบที่ปลอดภัย 
        เว็บไซต์จะไม่เก็บข้อมูลบัตรเครดิตหรือข้อมูลทางการเงินของผู้ใช้งาน 
        ผู้ซื้อควรตรวจสอบรายละเอียดผลงานก่อนชำระเงิน
    </p>

    <h2 data-key="terms_section5_heading">5. ช่องทางหางานและการจ้างงาน</h2>
    <p data-key="terms_section5_body">
        LightTooN เปิดพื้นที่สำหรับการประกาศหางานและการจ้างงาน 
        ผู้ใช้งานควรติดต่อและตกลงเงื่อนไขการทำงานอย่างชัดเจนระหว่างกัน 
        เว็บไซต์จะไม่รับผิดชอบหากเกิดข้อพิพาทระหว่างคู่สัญญา
    </p>

    <h2 data-key="terms_section6_heading">6. การใช้งานคอมมูนิตี้</h2>
    <p data-key="terms_section6_body">
        ผู้ใช้งานสามารถโพสต์ แสดงความคิดเห็น และสื่อสารภายในคอมมูนิตี้ได้ 
        โดยต้องใช้ถ้อยคำสุภาพ และไม่ก่อให้เกิดความเสียหายหรือรบกวนผู้อื่น
    </p>

    <h2 data-key="terms_section7_heading">7. การแก้ไขข้อตกลง</h2>
    <p data-key="terms_section7_body">
        เว็บไซต์มีสิทธิ์ปรับปรุงหรือเปลี่ยนแปลงข้อตกลงการใช้งานได้ทุกเวลา 
        โดยจะแจ้งผู้ใช้งานผ่านทางหน้าเว็บไซต์ 
        การใช้งานต่อไปถือว่าผู้ใช้งานยอมรับเงื่อนไขใหม่โดยอัตโนมัติ
    </p>
';

$terms_content_en = '
    <h1 data-key="terms_title">Terms of Service</h1>
    <p class="subtitle" data-key="terms_subtitle">Please read these terms carefully before using LightTooN</p>
    <hr>

    <h2 data-key="terms_section1_heading">1. Membership Registration</h2>
    <p data-key="terms_section1_body">
        Users must register with accurate and truthful information. 
        By registering, you agree to comply with all website policies and terms.
    </p>

    <h2 data-key="terms_section2_heading">2. Publishing and Selling Works</h2>
    <p data-key="terms_section2_body">
        Artists retain full ownership of their works when publishing on LightTooN 
        and may choose to sell their works through the platform. 
        The website serves solely as a medium connecting buyers and sellers.
    </p>

    <h2 data-key="terms_section3_heading">3. User Responsibility</h2>
    <p data-key="terms_section3_body">
        Users must not publish works that infringe copyright, laws, 
        or contain inappropriate content. Any violations will be the sole responsibility of the user.
    </p>

    <h2 data-key="terms_section4_heading">4. Transactions and Payments</h2>
    <p data-key="terms_section4_body">
        All transactions are processed through secure systems. 
        The website does not store credit card details or sensitive financial information. 
        Buyers should carefully review works before making payments.
    </p>

    <h2 data-key="terms_section5_heading">5. Job Opportunities and Hiring</h2>
    <p data-key="terms_section5_body">
        LightTooN provides a space for job postings and hiring opportunities. 
        Users should clearly agree on terms of work between themselves. 
        The website is not responsible for disputes between parties.
    </p>

    <h2 data-key="terms_section6_heading">6. Community Usage</h2>
    <p data-key="terms_section6_body">
        Users may post, comment, and communicate within the community, 
        but must do so respectfully and without causing harm or disturbance to others.
    </p>

    <h2 data-key="terms_section7_heading">7. Amendments to Terms</h2>
    <p data-key="terms_section7_body">
        LightTooN reserves the right to update or change these terms at any time. 
        Updates will be announced on the website, and continued use of the platform 
        constitutes acceptance of the new terms.
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
    <title>LightTooN - Terms of Service</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/policy.css">
</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <?php
        // ตรวจสอบภาษาที่เลือก แล้วแสดงเนื้อหาที่ถูกต้อง
        if ($currentLang == "en") {
            echo $terms_content_en;
        } else {
            echo $terms_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
