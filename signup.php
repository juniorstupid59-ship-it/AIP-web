<?php
// signup.php
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
$signup_content_th = [
    "signup_title" => "สมัครสมาชิก",
    "signup_error" => "เกิดข้อผิดพลาดในการสมัครสมาชิก",
    "signup_success" => "สมัครสมาชิกสำเร็จ",
    "username_label" => "ชื่อผู้ใช้",
    "email_label" => "อีเมล",
    "password_label" => "รหัสผ่าน",
    "confirm_password_label" => "ยืนยันรหัสผ่าน",
    "signup_button" => "สมัครสมาชิก",
    "have_account" => "มีบัญชีแล้ว?",
    "signin_link" => "เข้าสู่ระบบ",
    "show_password" => "แสดงรหัสผ่าน",
    "hide_password" => "ซ่อนรหัสผ่าน"
];

$signup_content_en = [
    "signup_title" => "Sign Up",
    "signup_error" => "Error during registration",
    "signup_success" => "Registration successful",
    "username_label" => "Username",
    "email_label" => "Email",
    "password_label" => "Password",
    "confirm_password_label" => "Confirm Password",
    "signup_button" => "Sign Up",
    "have_account" => "Already have an account?",
    "signin_link" => "Sign In",
    "show_password" => "Show Password",
    "hide_password" => "Hide Password"
];

$content = ($currentLang == 'en') ? $signup_content_en : $signup_content_th;

// -----------------------------
// ประมวลผลฟอร์มสมัครสมาชิก
// -----------------------------
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $error = $content['signup_error'];
    } else {
        if (registerUser($username, $email, $password)) {
            if (loginUser($email, $password)) {
                header("Location: index.php");
                exit;
            } else {
                $error = $content['signup_success'] . ", but login failed. Please login manually.";
            }
        } else {
            $error = "Email already in use. Please use another email.";
        }
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
    <h2 data-key="signup_title"><?= $content['signup_title'] ?></h2>

    <?php if ($error): ?>
      <p class="error-msg" data-key="signup_error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
      <p class="success-msg" data-key="signup_success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" class="auth-form">
      <div class="form-group">
        <label for="username" data-key="username_label"><?= $content['username_label'] ?></label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="email" data-key="email_label"><?= $content['email_label'] ?></label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group password-group">
        <label for="password" data-key="password_label"><?= $content['password_label'] ?></label>
        <input type="password" id="password" name="password" required>
        <button type="button" class="toggle-password" aria-label="<?= $content['show_password'] ?>">
            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 4.5c5.05 0 9.27 3.63 11 8-1.73 4.37-5.95 8-11 8S2.73 16.87 1 12c1.73-4.37 5.95-8 11-8zm0 14c-4.41 0-8.04-3.54-9.58-7.5C3.96 6.96 7.59 4 12 4s8.04 2.96 9.58 7.5c-1.54 3.96-5.17 7.5-9.58 7.5zM12 9a3 3 0 100 6 3 3 0 000-6zM12 7a5 5 0 110 10 5 5 0 010-10z"/>
            </svg>
        </button>
      </div>
      <div class="form-group password-group">
        <label for="confirm" data-key="confirm_password_label"><?= $content['confirm_password_label'] ?></label>
        <input type="password" id="confirm" name="confirm" required>
        <button type="button" class="toggle-password" aria-label="<?= $content['show_password'] ?>">
            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 4.5c5.05 0 9.27 3.63 11 8-1.73 4.37-5.95 8-11 8S2.73 16.87 1 12c1.73-4.37 5.95-8 11-8zm0 14c-4.41 0-8.04-3.54-9.58-7.5C3.96 6.96 7.59 4 12 4s8.04 2.96 9.58 7.5c-1.54 3.96-5.17 7.5-9.58 7.5zM12 9a3 3 0 100 6 3 3 0 000-6zM12 7a5 5 0 110 10 5 5 0 010-10z"/>
            </svg>
        </button>
      </div>
      <button type="submit" class="btn-primary" data-key="signup_button"><?= $content['signup_button'] ?></button>
    </form>

    <p class="auth-switch">
      <span data-key="have_account"><?= $content['have_account'] ?></span>
      <a href="signin.php" data-key="signin_link"><?= $content['signin_link'] ?></a>
    </p>
  </div>
</main>

<?php include __DIR__ . "/phpinclude/footer.php"; ?>
