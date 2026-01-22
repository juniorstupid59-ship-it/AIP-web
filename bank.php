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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Bank
// -----------------------------
$bank_content_th = '
    <h1 data-key="bank_title">ธนาคาร LightTooN</h1>
    <p class="subtitle" data-key="bank_subtitle">ระบบธนาคารออนไลน์จำลองสำหรับการนำเสนอโปรเจค</p>
    <hr>

    <h2 data-key="bank_section1_heading">1. การเชื่อมต่อกับบัญชี</h2>
    <p data-key="bank_section1_body">
        ผู้ใช้งานสามารถเชื่อมต่อบัญชี LightTooN ของตนเข้ากับระบบธนาคารจำลองได้อย่างปลอดภัย
        ข้อมูลบัญชีทั้งหมดจะถูกจัดเก็บเป็นตัวอย่างเท่านั้น ไม่มีการติดต่อกับธนาคารจริง
    </p>

    <h2 data-key="bank_section2_heading">2. การทำธุรกรรม</h2>
    <p data-key="bank_section2_body">
        ระบบจำลองการโอนเงินและฝากเงิน เพื่อแสดงฟีเจอร์การทำธุรกรรมออนไลน์
        การทำธุรกรรมทั้งหมดเป็นเพียงข้อมูลตัวอย่าง ไม่ใช่เงินจริง
    </p>

    <h2 data-key="bank_section3_heading">3. ความปลอดภัยของข้อมูล</h2>
    <p data-key="bank_section3_body">
        ข้อมูลผู้ใช้งานในระบบธนาคารจำลองนี้ถูกเข้ารหัสอย่างเหมาะสม
        เพื่อให้การนำเสนอโปรเจคดูสมจริงและปลอดภัย
    </p>

    <h2 data-key="bank_section4_heading">4. การตรวจสอบบัญชี</h2>
    <p data-key="bank_section4_body">
        ผู้ใช้งานสามารถดูประวัติธุรกรรมตัวอย่างและยอดเงินจำลองได้
        ทั้งหมดเพื่อวัตถุประสงค์ในการนำเสนอระบบเท่านั้น
    </p>

    <h2 data-key="bank_section5_heading">5. การสนับสนุนและติดต่อ</h2>
    <p data-key="bank_section5_body">
        หากต้องการข้อมูลเพิ่มเติมเกี่ยวกับระบบธนาคารจำลอง สามารถติดต่อทีมงาน LightTooN
        เพื่อสอบถามรายละเอียดเพิ่มเติมเกี่ยวกับโปรเจค
    </p>
';

$bank_content_en = '
    <h1 data-key="bank_title">LightTooN Bank</h1>
    <p class="subtitle" data-key="bank_subtitle">Simulated online banking system for project presentation purposes</p>
    <hr>

    <h2 data-key="bank_section1_heading">1. Account Connection</h2>
    <p data-key="bank_section1_body">
        Users can securely connect their LightTooN account to this simulated banking system.
        All account information is stored as sample data only; no real bank is involved.
    </p>

    <h2 data-key="bank_section2_heading">2. Transactions</h2>
    <p data-key="bank_section2_body">
        The system simulates deposit and transfer features to showcase online transaction functionality.
        All transactions are sample data and not real money.
    </p>

    <h2 data-key="bank_section3_heading">3. Data Security</h2>
    <p data-key="bank_section3_body">
        User data in this simulated banking system is properly encrypted to ensure the demo is safe
        and realistic.
    </p>

    <h2 data-key="bank_section4_heading">4. Account Overview</h2>
    <p data-key="bank_section4_body">
        Users can view example transaction history and simulated balances,
        all for demonstration purposes only.
    </p>

    <h2 data-key="bank_section5_heading">5. Support & Contact</h2>
    <p data-key="bank_section5_body">
        For more information about the simulated banking system, contact the LightTooN team
        for further project details.
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
    <title>LightTooN - Bank</title>

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
            echo $bank_content_en;
        } else {
            echo $bank_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
