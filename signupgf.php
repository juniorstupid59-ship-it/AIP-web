<?php
// signupgf.php
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title data-key="title">สมัครบัญชี | LightTooN</title>
<link rel="stylesheet" href="css/auth.css">
<link rel="stylesheet" href="css/main.css">
</head>
<body class="dark-theme">

<!-- ================= Header ================= -->
<header class="main-header">
  <div class="logo"><a href="phpinclude/index.php" data-key="logo">LightTooN</a></div>
  <button class="menu-toggle" id="menuToggle">☰</button>
  <div class="header-right">
    <button class="header-btn" id="toggleTheme" data-key="themeToggle">เปลี่ยนธีม</button>
    <button class="header-btn" id="toggleLang">TH</button>
    <div class="profile-menu" id="profileMenu">
      <img src="img/default-profile.png" alt="profile" class="profile-icon">
      <div class="dropdown" id="profileDropdown">
        <a href="#" class="logout" data-key="logout">ออกจากระบบ</a>
      </div>
    </div>
  </div>
</header>

<!-- ================= Signup Form ================= -->
<main class="auth-page">
  <div class="auth-card">
    <h1 class="title" data-key="signupTitle">สมัครบัญชี</h1>
    <p class="subtitle" data-key="signupSubtitle">สร้างบัญชีเพื่อเริ่มต้นใช้งาน</p>

    <form id="signupForm" class="auth-form" method="post" action="phpinclude/process_signup.php">
      <label for="email" data-key="emailLabel">อีเมล</label>
      <input type="email" name="email" id="email" class="input" placeholder="example@email.com" required>

      <label for="password" data-key="passwordLabel">รหัสผ่าน</label>
      <div class="input-wrap">
        <input type="password" name="password" id="password" class="input" placeholder="••••••" required>
        <span class="toggle toggle-password">👁️</span>
      </div>

      <label for="confirmPassword" data-key="confirmPasswordLabel">ยืนยันรหัสผ่าน</label>
      <div class="input-wrap">
        <input type="password" name="confirmPassword" id="confirmPassword" class="input" placeholder="••••••" required>
        <span class="toggle toggle-password">👁️</span>
      </div>

      <button type="submit" class="btn auth-submit" data-key="signupBtn">สมัคร</button>
    </form>

    <div class="auth-divider"><span data-key="or">หรือ</span></div>

    <div class="social-login">
      <div class="social-btn google btn-social" data-provider="google">
        <img src="img/google.svg" alt="Google"> <span data-key="signupGoogle">สมัครด้วย Google</span>
      </div>
      <div class="social-btn facebook btn-social" data-provider="facebook">
        <img src="img/facebook.svg" alt="Facebook"> <span data-key="signupFacebook">สมัครด้วย Facebook</span>
      </div>
    </div>

    <p class="auth-footer" data-key="haveAccount">มีบัญชีแล้ว? <a href="phpinclude/signin.php" data-key="signinLink">เข้าสู่ระบบ</a></p>
  </div>
</main>

<script src="js/main.js"></script>
<script src="js/auth.js"></script>

</body>
</html>
