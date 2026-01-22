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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Privacy Policy
// -----------------------------
$privacy_content_th = '
    <h1 data-key="privacy_title">นโยบายความเป็นส่วนตัว</h1>
    <p class="subtitle" data-key="privacy_subtitle">เรามุ่งมั่นที่จะปกป้องข้อมูลและความปลอดภัยของผู้ใช้งาน LightTooN ทุกคน</p>
    <hr>

    <h2 data-key="privacy_section1_heading">1. การเก็บข้อมูลผู้ใช้งาน</h2>
    <p data-key="privacy_section1_body">
        เราเก็บเฉพาะข้อมูลที่จำเป็นต่อการใช้งาน เช่น ข้อมูลการสมัครสมาชิก อีเมล และข้อมูลที่เกี่ยวข้องกับการซื้อขายผลงาน
        โดยเราจะไม่เปิดเผยข้อมูลส่วนบุคคลให้บุคคลที่สามโดยไม่ได้รับอนุญาต
    </p>

    <h2 data-key="privacy_section2_heading">2. ความปลอดภัยของข้อมูล</h2>
    <p data-key="privacy_section2_body">
        ข้อมูลทั้งหมดของคุณจะถูกจัดเก็บด้วยมาตรการรักษาความปลอดภัยที่เหมาะสม
        เพื่อป้องกันการเข้าถึงที่ไม่ได้รับอนุญาตหรือการนำไปใช้ในทางที่ผิด
    </p>

    <h2 data-key="privacy_section3_heading">3. สิทธิ์ของผู้ใช้งาน</h2>
    <p data-key="privacy_section3_body">
        ผู้ใช้งานสามารถเข้าถึง แก้ไข หรือลบข้อมูลส่วนบุคคลของตนเองได้ตามต้องการ
        รวมถึงสามารถตั้งค่าความเป็นส่วนตัวในการเผยแพร่ผลงานได้
    </p>

    <h2 data-key="privacy_section4_heading">4. การใช้งานผลงานที่เผยแพร่</h2>
    <p data-key="privacy_section4_body">
        ผลงานที่ผู้ใช้งานเผยแพร่บน LightTooN จะเป็นสิทธิ์ของเจ้าของผลงานโดยสมบูรณ์
        เราจะไม่แก้ไขหรือเปลี่ยนแปลงผลงานของคุณโดยไม่ได้รับอนุญาต
        และผลงานที่นำมาขายยังคงเป็นทรัพย์สินของผู้สร้างสรรค์
    </p>

    <h2 data-key="privacy_section5_heading">5. การซื้อขายและการทำธุรกรรม</h2>
    <p data-key="privacy_section5_body">
        การซื้อขายผลงานผ่าน LightTooN จะดำเนินการด้วยระบบที่ปลอดภัย
        โดยเราจะไม่เก็บข้อมูลบัตรเครดิตหรือรหัสผ่านทางการเงินของผู้ใช้งาน
        เพื่อให้คุณมั่นใจได้ว่าทุกธุรกรรมเป็นไปอย่างปลอดภัย
    </p>

    <h2 data-key="privacy_section6_heading">6. การอัปเดตนโยบาย</h2>
    <p data-key="privacy_section6_body">
        เราอาจมีการปรับปรุงนโยบายความเป็นส่วนตัวเป็นครั้งคราว
        เพื่อให้สอดคล้องกับการพัฒนาของแพลตฟอร์มและการคุ้มครองผู้ใช้งานที่ดียิ่งขึ้น
    </p>
';

$privacy_content_en = '
    <h1 data-key="privacy_title">Privacy Policy</h1>
    <p class="subtitle" data-key="privacy_subtitle">We are committed to protecting the privacy and security of all LightTooN users.</p>
    <hr>

    <h2 data-key="privacy_section1_heading">1. Information Collection</h2>
    <p data-key="privacy_section1_body">
        We only collect the necessary information for using our services, such as account registration,
        email address, and transaction-related details. Your personal data will never be shared with third parties without your consent.
    </p>

    <h2 data-key="privacy_section2_heading">2. Data Security</h2>
    <p data-key="privacy_section2_body">
        All user data is protected with appropriate security measures to prevent unauthorized access,
        misuse, or disclosure of your personal information.
    </p>

    <h2 data-key="privacy_section3_heading">3. User Rights</h2>
    <p data-key="privacy_section3_body">
        Users have the right to access, edit, or delete their personal information at any time,
        as well as configure privacy settings for their published works.
    </p>

    <h2 data-key="privacy_section4_heading">4. Ownership of Published Works</h2>
    <p data-key="privacy_section4_body">
        All works published on LightTooN remain the sole property of their creators.
        We will not modify or alter your work without permission, and works listed for sale remain the intellectual property of their authors.
    </p>

    <h2 data-key="privacy_section5_heading">5. Transactions and Sales</h2>
    <p data-key="privacy_section5_body">
        Transactions conducted on LightTooN are secured with trusted systems.
        We do not store sensitive financial details such as credit card information or payment passwords,
        ensuring that all purchases and sales are safe.
    </p>

    <h2 data-key="privacy_section6_heading">6. Policy Updates</h2>
    <p data-key="privacy_section6_body">
        This privacy policy may be updated from time to time to reflect improvements in our platform
        and to ensure stronger protection for our users.
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
    <title>LightTooN - Privacy Policy</title>

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
            echo $privacy_content_en;
        } else {
            echo $privacy_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
