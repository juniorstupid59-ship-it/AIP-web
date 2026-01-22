<?php
session_start();

// ------------------------------
// 1. เชื่อมต่อฐานข้อมูล
// ------------------------------
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lighttoon";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ------------------------------
// 2. ฟังก์ชันล็อกอินปกติ
// ------------------------------
function loginUser($email, $password) {
    global $conn;
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    return false;
}

// ------------------------------
// 3. ตรวจสอบการส่งฟอร์มล็อกอิน
// ------------------------------
$login_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    if (!loginUser($email, $password)) {
        $login_error = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
    } else {
        header("phpinclude/Location: index.php");
        exit;
    }
}

// ------------------------------
// 4. Google Login Setup
// ------------------------------
require_once 'vendor/autoload.php';
$google_client = new Google_Client();
$google_client->setClientId('YOUR_GOOGLE_CLIENT_ID');
$google_client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$google_client->setRedirectUri('http://yourdomain.com/google-callback.php');
$google_client->addScope('email');
$google_client->addScope('profile');

// ------------------------------
// 5. Facebook Login Setup
// ------------------------------
$fb = new \Facebook\Facebook([
  'app_id' => 'YOUR_FACEBOOK_APP_ID',
  'app_secret' => 'YOUR_FACEBOOK_APP_SECRET',
  'default_graph_version' => 'v13.0',
]);
$helper = $fb->getRedirectLoginHelper();
$facebook_login_url = $helper->getLoginUrl('phpinclude/http://yourdomain.com/facebook-callback.php', ['email']);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ - LightTooN</title>
    <link rel="stylesheet" href="css/assets/style.css">
    <style>
        .login-container { max-width: 400px; margin: 100px auto; background: #0b1220; padding: 30px; border-radius: 12px; color: #dfeff6; box-shadow: 0 4px 12px rgba(0,0,0,0.5); }
        .login-container h2 { text-align: center; margin-bottom: 20px; }
        .login-container input { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.03); color: #fff; }
        .login-container button { width: 100%; padding: 10px; background: #0ea5d6; border: none; border-radius: 8px; color: #fff; cursor: pointer; font-weight: bold; }
        .login-container button:hover { background: #1fb7ff; }
        .login-container .social-btn { display: flex; gap: 10px; margin-top: 15px; }
        .login-container .social-btn a { flex: 1; text-align: center; padding: 10px; border-radius: 8px; text-decoration: none; color: #fff; font-weight: bold; }
        .google-btn { background: #db4437; }
        .facebook-btn { background: #4267B2; }
        .error { color: #ef4444; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

<header class="main-header">
  <button class="menu-toggle" id="menuToggle">☰</button>
  <div class="logo"><a href="phpinclude/index.php">LightTooN</a></div>
  <div class="search-bar">
      <input type="text" id="searchInput" placeholder="Search...">
      <div class="search-results" id="searchResults"></div>
  </div>
</header>

<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>
    <?php if($login_error): ?>
        <p class="error"><?= $login_error ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="อีเมล" required>
        <input type="password" name="password" placeholder="รหัสผ่าน" required>
        <button type="submit" name="login">เข้าสู่ระบบ</button>
    </form>

    <div class="social-btn">
        <a class="google-btn" href="<?= htmlspecialchars($google_client->createAuthUrl()) ?>">Google</a>
        <a class="facebook-btn" href="<?= htmlspecialchars($facebook_login_url) ?>">Facebook</a>
    </div>

    <p style="text-align:center; margin-top:15px;"><a href="phpinclude/register.php" style="color:#0ea5d6;">สมัครสมาชิก</a></p>
</div>

<script src="js/assets/main.js"></script>
</body>
</html>
