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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า FAQ
// -----------------------------
$faq_content_th = '
    <h1 data-key="faq_title">คำถามที่พบบ่อย (FAQ)</h1>
    <p class="subtitle" data-key="faq_subtitle">เราพร้อมตอบข้อสงสัยเพื่อให้คุณมั่นใจในการใช้งาน LightTooN</p>
    <hr>
    
    <div class="faq-section">
        <h2 data-key="faq_section_upload">การเผยแพร่และขายผลงาน</h2>
        <div class="faq-item">
            <h3 data-key="faq_q1">Q: ฉันสามารถนำผลงานอะไรมาลงที่ LightTooN ได้บ้าง?</h3>
            <p data-key="faq_a1">
                <strong>A:</strong> คุณสามารถเผยแพร่ผลงานสร้างสรรค์ได้หลากหลายรูปแบบ ทั้งมังงะ (การ์ตูนช่อง), นวนิยาย และงานอาร์ตเดี่ยวๆ เช่น ภาพประกอบ ซึ่งสามารถเลือกว่าจะเผยแพร่ฟรีหรือตั้งราคาขายก็ได้ครับ
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q2">Q: LightTooN มีระบบการปกป้องลิขสิทธิ์ของผลงานอย่างไร?</h3>
            <p data-key="faq_a2">
                <strong>A:</strong> LightTooN ให้ความสำคัญสูงสุดกับทรัพย์สินทางปัญญาของศิลปิน เรามีมาตรการป้องกันการคัดลอกและนำผลงานไปใช้โดยไม่ได้รับอนุญาต หากพบเห็นการละเมิดลิขสิทธิ์ คุณสามารถรายงานได้ทันที และทีมงานจะรีบดำเนินการตรวจสอบและเอาผิดตามกฎหมายโดยเร็วที่สุด
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q3">Q: ระบบการซื้อ-ขายผลงานมีความปลอดภัยหรือไม่?</h3>
            <p data-key="faq_a3">
                <strong>A:</strong> ระบบการซื้อ-ขายของเรามีความปลอดภัยสูง เราใช้ระบบชำระเงินมาตรฐานสากลเพื่อปกป้องข้อมูลทางการเงินของผู้ซื้อและผู้ขาย และมีระบบตรวจสอบธุรกรรมที่ผิดปกติ เพื่อให้คุณมั่นใจได้ว่าการซื้อ-ขายเป็นไปอย่างราบรื่นและปลอดภัยเสมอ
            </p>
        </div>
    </div>

    <div class="faq-section">
        <h2 data-key="faq_section_community">ชุมชนและการสื่อสาร</h2>
        <div class="faq-item">
            <h3 data-key="faq_q4">Q: LightTooN มีพื้นที่ให้ศิลปินและผู้อ่านสื่อสารกันได้สะดวกแค่ไหน?</h3>
            <p data-key="faq_a4">
                <strong>A:</strong> เรามีพื้นที่ชุมชนที่ใช้งานง่ายและคล่องตัว คล้ายกับโซเชียลมีเดียทั่วไป คุณสามารถโพสต์ข้อความ รูปภาพ หรือพูดคุยกับแฟนคลับและศิลปินคนอื่นได้อย่างอิสระ ทำให้การสื่อสารในคอมมูนิตี้เป็นเรื่องที่สนุกและง่ายดาย
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q5">Q: ฉันจะมั่นใจได้อย่างไรว่าการแสดงความคิดเห็นจะเป็นไปในเชิงสร้างสรรค์?</h3>
            <p data-key="faq_a5">
                <strong>A:</strong> เรามีนโยบายและแนวทางปฏิบัติที่ชัดเจนสำหรับชุมชน พร้อมระบบรายงานความคิดเห็นที่ไม่เหมาะสม ซึ่งทีมงานจะคอยดูแลและลบเนื้อหาที่ละเมิดกฎ เพื่อให้ทุกคนสามารถแสดงความคิดเห็นได้อย่างปลอดภัยและให้เกียรติซึ่งกันและกัน
            </p>
        </div>
    </div>

    <div class="faq-section">
        <h2 data-key="faq_section_jobboard">ช่องทางหางานและรับจ้าง</h2>
        <div class="faq-item">
            <h3 data-key="faq_q6">Q: "ช่องทางหางาน" ทำงานอย่างไร?</h3>
            <p data-key="faq_a6">
                <strong>A:</strong> ช่องทางหางานคือพื้นที่ที่เปิดโอกาสให้คุณโพสต์ประกาศรับงาน เช่น รับวาดปกนิยาย หรือสำหรับผู้ที่ต้องการว่าจ้างนักวาดก็ได้เช่นกัน ซึ่งจะช่วยให้ศิลปินและผู้ว่าจ้างได้มาเจอกันและสร้างสรรค์ผลงานใหม่ๆ ร่วมกันได้อย่างสะดวก
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q7">Q: ข้อมูลส่วนตัวของฉันจะปลอดภัยเมื่อใช้ช่องทางหางานหรือไม่?</h3>
            <p data-key="faq_a7">
                <strong>A:</strong> ข้อมูลส่วนตัวของคุณจะปลอดภัยอย่างแน่นอน เราแนะนำให้พูดคุยและตกลงรายละเอียดงานในระบบของเราก่อน เพื่อความปลอดภัยและหลีกเลี่ยงการเปิดเผยข้อมูลส่วนตัวที่ไม่จำเป็น และคุณสามารถเลือกได้ว่าต้องการเปิดเผยข้อมูลใดบ้างให้กับผู้ที่สนใจติดต่อคุณ
            </p>
        </div>
    </div>
';

$faq_content_en = '
    <h1 data-key="faq_title">Frequently Asked Questions (FAQ)</h1>
    <p class="subtitle" data-key="faq_subtitle">We are here to answer your questions and build confidence in using LightTooN.</p>
    <hr>
    
    <div class="faq-section">
        <h2 data-key="faq_section_upload">Publishing and Selling Your Work</h2>
        <div class="faq-item">
            <h3 data-key="faq_q1">Q: What types of work can I upload to LightTooN?</h3>
            <p data-key="faq_a1">
                <strong>A:</strong> You can publish a wide variety of creative works, including comics (manga), novels, and individual art pieces like illustrations. You can choose to publish them for free or set a price for sale.
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q2">Q: How does LightTooN protect the copyright of my work?</h3>
            <p data-key="faq_a2">
                <strong>A:</strong> LightTooN places the highest priority on the intellectual property of artists. We have measures in place to prevent unauthorized copying and use of your work. If you find any infringement, you can report it immediately, and our team will investigate and take legal action as quickly as possible.
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q3">Q: Is the buying and selling system secure?</h3>
            <p data-key="faq_a3">
                <strong>A:</strong> Our buying and selling system is highly secure. We use international standard payment systems to protect the financial information of both buyers and sellers, and we have a system for monitoring unusual transactions to ensure your transactions are always smooth and safe.
            </p>
        </div>
    </div>

    <div class="faq-section">
        <h2 data-key="faq_section_community">Community and Communication</h2>
        <div class="faq-item">
            <h3 data-key="faq_q4">Q: How easy is it for artists and readers to communicate on LightTooN?</h3>
            <p data-key="faq_a4">
                <strong>A:</strong> We have a user-friendly and dynamic community space, similar to other social media platforms. You can post messages, images, or chat with fans and other artists freely, making community communication fun and easy.
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q5">Q: How can I ensure that comments are constructive?</h3>
            <p data-key="faq_a5">
                <strong>A:</strong> We have clear policies and guidelines for our community, along with a system for reporting inappropriate comments. Our team actively moderates and removes content that violates the rules, ensuring everyone can express themselves safely and respectfully.
            </p>
        </div>
    </div>

    <div class="faq-section">
        <h2 data-key="faq_section_jobboard">Job and Freelance Opportunities</h2>
        <div class="faq-item">
            <h3 data-key="faq_q6">Q: How does the "Job Board" work?</h3>
            <p data-key="faq_a6">
                <strong>A:</strong> The Job Board is a space that gives you the opportunity to post job requests, such as needing an artist to illustrate a novel cover. It also allows artists to find work. This helps connect artists and clients to create new works together conveniently.
            </p>
        </div>
        <div class="faq-item">
            <h3 data-key="faq_q7">Q: Will my personal information be safe when I use the Job Board?</h3>
            <p data-key="faq_a7">
                <strong>A:</strong> Your personal information will be completely safe. We recommend discussing and agreeing on job details within our system for security and to avoid revealing unnecessary personal information. You can also choose which information you want to show to those who contact you.
            </p>
        </div>
    </div>
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
    <title>LightTooN - FAQ</title>

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
            echo $faq_content_en;
        } else {
            echo $faq_content_th;
        }
        ?>
    </main>

    <?php include 'phpinclude/footer.php'; ?>
</body>
</html>
