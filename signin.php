<?php
// signin.php

session_start();
require __DIR__ . "/phpinclude/functions.php";

// -----------------------------
// จัดการภาษา (EN/TH)
// -----------------------------
$currentLang = isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['en', 'th'])
    ? $_COOKIE['lang']
    : 'th';

// -----------------------------
// ข้อความ UI ภาษาไทย/อังกฤษ
// -----------------------------
$signin_content_th = [
    "signin" => "เข้าสู่ระบบ",
    "login_error" => "อีเมลหรือรหัสผ่านไม่ถูกต้อง",
    "username_or_email" => "ชื่อผู้ใช้หรืออีเมล",
    "password_label" => "รหัสผ่าน",
    "login_button" => "เข้าสู่ระบบ",
    "no_account" => "ยังไม่มีบัญชี?",
    "signup_link" => "สมัครสมาชิก",
    "show_password" => "แสดงรหัสผ่าน",
    "hide_password" => "ซ่อนรหัสผ่าน"
];

$signin_content_en = [
    "signin" => "Sign In",
    "login_error" => "Incorrect email or password",
    "username_or_email" => "Username or Email",
    "password_label" => "Password",
    "login_button" => "Sign In",
    "no_account" => "Don't have an account?",
    "signup_link" => "Sign Up",
    "show_password" => "Show Password",
    "hide_password" => "Hide Password"
];

$content = ($currentLang == 'en') ? $signin_content_en : $signin_content_th;

// -----------------------------
// ประมวลผลฟอร์มล็อกอิน
// -----------------------------
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (loginUser($email, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = $content['login_error'];
    }
}
?>

<?php include __DIR__ . "/phpinclude/header.php"; ?>

<!-- CSS/JS -->
<?php
$css_version = filemtime('css/style.css');
$auth_css_version = filemtime('css/auth.css');
?>
<link rel="stylesheet" href="css/style.css?v=<?= $css_version ?>">
<link rel="stylesheet" href="css/auth.css?v=<?= $auth_css_version ?>">
<script src="js/auth.js" defer></script>

<main class="auth-container">
  <div class="signin-card">
    <h2 data-key="signin"><?= $content['signin'] ?></h2>

    <?php if ($error): ?>
      <p class="error-msg" data-key="login_error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
  <div class="form-group">
    <label for="email" data-key="username_or_email"><?= $content['username_or_email'] ?></label>
    <input type="text" id="email" name="email" required placeholder="">
  </div>
  <div class="form-group password-group">
    <label for="password" data-key="password_label"><?= $content['password_label'] ?></label>
    <input type="password" id="password" name="password" required placeholder="">
    <button type="button" class="toggle-password" aria-label="<?= $content['show_password'] ?>">
        <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 4.5c5.05 0 9.27 3.63 11 8-1.73 4.37-5.95 8-11 8S2.73 16.87 1 12c1.73-4.37 5.95-8 11-8zm0 14c-4.41 0-8.04-3.54-9.58-7.5C3.96 6.96 7.59 4 12 4s8.04 2.96 9.58 7.5c-1.54 3.96-5.17 7.5-9.58 7.5zM12 9a3 3 0 100 6 3 3 0 000-6zM12 7a5 5 0 110 10 5 5 0 010-10z"/>
        </svg>
    </button>
  </div>
  <button type="submit" class="btn-primary" data-key="login_button"><?= $content['login_button'] ?></button>
</form>

<p class="auth-switch">
  <span data-key="no_account"><?= $content['no_account'] ?></span>
  <a href="signup.php" data-key="signup_link"><?= $content['signup_link'] ?></a>
</p>


  </div>
</main>

<?php include __DIR__ . "/phpinclude/footer.php"; ?>
