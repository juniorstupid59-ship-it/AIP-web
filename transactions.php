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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Transactions
// -----------------------------
$transactions_content_th = '
    <h1 data-key="transactions_title">ธุรกรรมและความปลอดภัย</h1>
    <p class="subtitle" data-key="transactions_subtitle">LightTooN ให้ความสำคัญกับความเชื่อมั่นและความปลอดภัยของผู้ใช้งานทุกคน</p>
    <hr>

    <h2 data-key="transactions_section1_heading">1. ระบบธุรกรรมที่โปร่งใส</h2>
    <p data-key="transactions_section1_body">
        ทุกการซื้อขายผลงานจะถูกบันทึกไว้อย่างชัดเจน ผู้ซื้อและผู้ขายสามารถตรวจสอบประวัติการทำธุรกรรมของตนเองได้ตลอดเวลา
    </p>

    <h2 data-key="transactions_section2_heading">2. ความปลอดภัยในการชำระเงิน</h2>
    <p data-key="transactions_section2_body">
        ระบบของ LightTooN ใช้มาตรการเข้ารหัสข้อมูล และไม่เก็บข้อมูลบัตรเครดิตหรือข้อมูลทางการเงินที่อ่อนไหวของผู้ใช้งาน
    </p>

    <h2 data-key="transactions_section3_heading">3. การคุ้มครองสิทธิ์ของศิลปิน</h2>
    <p data-key="transactions_section3_body">
        ศิลปินยังคงเป็นเจ้าของลิขสิทธิ์ผลงานของตนเอง 100% การเผยแพร่หรือการขายผ่านแพลตฟอร์มนี้เป็นเพียงการอนุญาตให้เข้าถึงและซื้อผลงาน
    </p>

    <h2 data-key="transactions_section4_heading">4. ความน่าเชื่อถือระหว่างผู้ซื้อและผู้ขาย</h2>
    <p data-key="transactions_section4_body">
        ระบบรีวิวและประวัติการทำธุรกรรมช่วยสร้างความน่าเชื่อถือทั้งสองฝ่าย เพื่อให้ทุกการซื้อขายเป็นไปอย่างยุติธรรมและมั่นใจ
    </p>

    <h2 data-key="transactions_section5_heading">5. ช่องทางการหางานและการจ้างงาน</h2>
    <p data-key="transactions_section5_body">
        เมื่อมีการจ้างงาน ศิลปินและผู้ว่าจ้างควรกำหนดเงื่อนไขที่ชัดเจน การชำระเงินจะถูกดูแลผ่านระบบกลาง เพื่อป้องกันปัญหาการค้างชำระหรือการผิดข้อตกลง
    </p>

    <h2 data-key="transactions_section6_heading">6. ความโปร่งใสและการรายงาน</h2>
    <p data-key="transactions_section6_body">
        หากพบการทำธุรกรรมที่ไม่ถูกต้อง ผู้ใช้งานสามารถรายงานมายังทีมงาน LightTooN เพื่อให้มีการตรวจสอบและแก้ไขโดยเร็ว
    </p>
';

$transactions_content_en = '
    <h1 data-key="transactions_title">Transactions & Security</h1>
    <p class="subtitle" data-key="transactions_subtitle">LightTooN prioritizes trust and security for every user.</p>
    <hr>

    <h2 data-key="transactions_section1_heading">1. Transparent Transactions</h2>
    <p data-key="transactions_section1_body">
        Every purchase or sale is clearly recorded, and both buyers and sellers can review their transaction history at any time.
    </p>

    <h2 data-key="transactions_section2_heading">2. Secure Payments</h2>
    <p data-key="transactions_section2_body">
        LightTooN uses encrypted systems and does not store credit card details or sensitive financial information.
    </p>

    <h2 data-key="transactions_section3_heading">3. Protection of Artist Rights</h2>
    <p data-key="transactions_section3_body">
        Artists retain 100% ownership of their works. Publishing or selling on the platform only grants access to view or purchase the work.
    </p>

    <h2 data-key="transactions_section4_heading">4. Trust Between Buyers and Sellers</h2>
    <p data-key="transactions_section4_body">
        Reviews and transaction histories build credibility for both parties, ensuring fair and confident exchanges.
    </p>

    <h2 data-key="transactions_section5_heading">5. Job Opportunities and Hiring</h2>
    <p data-key="transactions_section5_body">
        When hiring occurs, terms should be clearly defined. Payments are processed through a central system to prevent unpaid or breached agreements.
    </p>

    <h2 data-key="transactions_section6_heading">6. Transparency and Reporting</h2>
    <p data-key="transactions_section6_body">
        If any suspicious transactions are detected, users can report them to the LightTooN team for prompt review and resolution.
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
    <title>LightTooN - Transactions</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/policy.css">
</head>
<body>
    <?php include 'phpinclude/header.php'; ?>

    <main class="policy-container">
        <?php
        if ($currentLang == "en") {
            echo $transactions_content_en;
        } else {
            echo $transactions_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
