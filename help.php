<?php
// help.php
session_start();
require __DIR__ . "/phpinclude/functions.php";

// ปิด Warning สำหรับฟังก์ชัน mail() (สำหรับเครื่องจำลอง)
error_reporting(E_ALL & ~E_WARNING);

// -----------------------------
// จัดการภาษา (EN/TH)
// -----------------------------
$currentLang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['en', 'th'])
    ? $_COOKIE['lang']
    : 'th';

// -----------------------------
// ข้อความ UI ภาษาไทย/อังกฤษ
// -----------------------------
$help_content_th = [
    "help_center" => "ศูนย์ช่วยเหลือ",
    "success_msg" => "✅ ขอบคุณ เราได้รับข้อความของคุณแล้ว ทีมงานจะติดต่อกลับโดยเร็วที่สุด",
    "error_required" => "กรุณากรอกข้อมูลให้ครบทุกช่อง",
    "error_email" => "กรุณากรอกรูปแบบอีเมลให้ถูกต้อง",
    "label_name" => "ชื่อของคุณ",
    "label_email" => "อีเมลติดต่อ",
    "label_issue" => "ข้อความ / ปัญหาที่พบ",
    "placeholder_name" => "กรอกชื่อของคุณ",
    "placeholder_email" => "กรอกอีเมลติดต่อ",
    "placeholder_issue" => "พิมพ์ข้อความหรือปัญหาที่พบ",
    "btn_submit" => "ส่งข้อความ"
];

$help_content_en = [
    "help_center" => "Help Center",
    "success_msg" => "✅ Thank you! Your message has been received. Our team will contact you shortly.",
    "error_required" => "Please fill in all required fields",
    "error_email" => "Please enter a valid email address",
    "label_name" => "Your Name",
    "label_email" => "Contact Email",
    "label_issue" => "Message / Issue",
    "placeholder_name" => "Enter your name",
    "placeholder_email" => "Enter your contact email",
    "placeholder_issue" => "Type your message or issue",
    "btn_submit" => "Send Message"
];

// เลือก content ตามภาษาที่เลือก
$content = ($currentLang == 'en') ? $help_content_en : $help_content_th;

// ======================
// ตั้งค่าอีเมลผู้รับ
// ======================
$to = "support@lighttoon.com";
$subject_prefix = "[LightTooN Help]";
$message_sent = false;
$error = "";

// ======================
// เมื่อส่งฟอร์ม
// ======================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $issue = trim($_POST["issue"]);

    if ($name && $email && $issue) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = $content['error_email'];
        } else {
            $subject = "$subject_prefix แจ้งปัญหาใหม่จาก $name";
            $body = "ชื่อผู้ใช้: $name\nอีเมล: $email\n\nข้อความ:\n$issue";

            $headers = "From: $email\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            mail($to, $subject, $body, $headers);
            $message_sent = true;
            unset($name, $email, $issue);
        }
    } else {
        $error = $content['error_required'];
    }
}
?>

<?php include __DIR__ . "/phpinclude/header.php"; ?>

<!-- CSS -->
<?php
$style_version = filemtime('css/style.css');
$auth_version = filemtime('css/auth.css');
$support_version = filemtime('css/support.css');
?>
<link rel="stylesheet" href="css/style.css?v=<?= $style_version ?>">
<link rel="stylesheet" href="css/auth.css?v=<?= $auth_version ?>">
<link rel="stylesheet" href="css/support.css?v=<?= $support_version ?>">

<main class="auth-container">
  <div class="signin-card">
    <h2 data-key="help_center"><?= $content['help_center'] ?></h2>

    <?php if ($message_sent): ?>
      <p class="success-msg" data-key="success_msg"><?= $content['success_msg'] ?></p>
    <?php elseif ($error): ?>
      <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
      <div class="form-group">
        <label for="name" data-key="label_name"><?= $content['label_name'] ?></label>
        <input type="text" id="name" name="name" required value="<?= isset($name) ? htmlspecialchars($name) : "" ?>">
      </div>

      <div class="form-group">
        <label for="email" data-key="label_email"><?= $content['label_email'] ?></label>
        <input type="email" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : "" ?>">
      </div>

      <div class="form-group">
        <label for="issue" data-key="label_issue"><?= $content['label_issue'] ?></label>
        <textarea id="issue" name="issue" rows="5" required><?= isset($issue) ? htmlspecialchars($issue) : "" ?></textarea>
      </div>

      <button type="submit" class="btn-primary" data-key="btn_submit"><?= $content['btn_submit'] ?></button>
    </form>
  </div>
</main>

<?php include __DIR__ . "/phpinclude/footer.php"; ?>
