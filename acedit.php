<?php
session_start();

// ------------------------------
// 1. นำเข้าไฟล์ functions.php ไว้ด้านบนสุด
// ------------------------------
include 'phpinclude/functions.php';

// ------------------------------
// 2. เชื่อมต่อฐานข้อมูล
// ------------------------------
$conn = dbConnect();

// ------------------------------
// 3. ตรวจสอบการล็อกอิน
// ------------------------------
if (!isLoggedIn()) {
    header("Location: signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// ------------------------------
// 4. ดึงข้อมูลผู้ใช้ปัจจุบัน
// ------------------------------
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// ------------------------------
// 5. อัปเดตโปรไฟล์
// ------------------------------
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    
    // ตรวจสอบการจำกัดการแก้ไข (7 วันต่อครั้ง)
    if ($user['last_profile_update'] !== null) {
        $last_update = new DateTime($user['last_profile_update']);
        $now = new DateTime();
        $diff = $now->diff($last_update);
        $days_since_last_update = $diff->days;
    
        if ($days_since_last_update < 7) {
            $error = "คุณสามารถแก้ไขโปรไฟล์ได้สูงสุด 2 ครั้งต่อสัปดาห์ กรุณารอ 7 วันหลังจากแก้ไขครั้งล่าสุด";
        }
    }

    if (empty($error)) {
        $username = $_POST['username'];
        $country = $_POST['country'];
        $bio = $_POST['bio'];
        
        // รูปโปรไฟล์
        $profile_file = $user['profile_pic']; // ตั้งค่าเป็นค่าเดิมไว้ก่อน
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $profile_file = 'uploads/' . time() . '_' . basename($_FILES['profile_pic']['name']);
            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_file)) {
                $error = "เกิดข้อผิดพลาดในการอัปโหลดรูปโปรไฟล์";
            }
        }

        // แบนเนอร์
        $banner_file = $user['banner_pic']; // ตั้งค่าเป็นค่าเดิมไว้ก่อน
        if (isset($_FILES['banner_pic']) && $_FILES['banner_pic']['error'] == 0) {
            $banner_file = 'uploads/' . time() . '_' . basename($_FILES['banner_pic']['name']);
            if (!move_uploaded_file($_FILES['banner_pic']['tmp_name'], $banner_file)) {
                $error = "เกิดข้อผิดพลาดในการอัปโหลดรูปแบนเนอร์";
            }
        }

        if (empty($error)) {
            $update = "UPDATE users SET username=?, country=?, bio=?, profile_pic=?, banner_pic=?, last_profile_update=CURRENT_TIMESTAMP WHERE id=?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("sssssi", $username, $country, $bio, $profile_file, $banner_file, $user_id);

            if ($stmt->execute()) {
                $success = "อัปเดตโปรไฟล์เรียบร้อยแล้ว";
                // อัปเดตตัวแปร user ใหม่
                $user['username'] = $username;
                $user['country'] = $country;
                $user['bio'] = $bio;
                $user['profile_pic'] = $profile_file;
                $user['banner_pic'] = $banner_file;
            } else {
                $error = "เกิดข้อผิดพลาดในการบันทึกโปรไฟล์";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขโปรไฟล์ - LightTooN</title>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/account.css">
<style>
/* Form Container */
.profile-edit-container {
    max-width: 700px;
    margin: 50px auto;
    background: #0b1220;
    padding: 30px;
    border-radius: 12px;
    box-shadow: var(--shadow-2);
    color: #dfeff6;
}

.profile-edit-container h2 {
    text-align: center;
    margin-bottom: 25px;
}

.profile-edit-container label {
    display: block;
    margin: 12px 0 4px;
}

.profile-edit-container input[type="text"],
.profile-edit-container textarea,
.profile-edit-container select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.03);
    color: #fff;
}

.profile-edit-container input[type="file"] {
    color: #fff;
}

.profile-edit-container textarea {
    resize: vertical;
}

.profile-edit-container .checkbox-group {
    display: flex;
    gap: 20px;
    margin: 10px 0;
}

/* เพิ่ม CSS สำหรับปุ่มย้อนกลับและกลุ่มปุ่ม */
.form-actions {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.form-actions a,
.form-actions button {
    flex: 1;
    padding: 12px;
    border-radius: 8px;
    font-weight: bold;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    border: none;
}

.form-actions .back-btn {
    background: #475569;
    color: #fff;
}

.form-actions .back-btn:hover {
    background: #64748b;
}

.form-actions button[type="submit"] {
    background: #0ea5d6;
    color: #fff;
}

.form-actions button[type="submit"]:hover {
    background: #1fb7ff;
}
/* สิ้นสุดการเพิ่ม CSS */

.success-msg { color: #22c55e; text-align: center; margin-bottom: 15px; }
.error-msg { color: #ef4444; text-align: center; margin-bottom: 15px; }

.profile-preview img { max-width: 100%; border-radius: 12px; margin-bottom: 15px; }
</style>
</head>
<body>
<?php include 'phpinclude/header.php'; ?>

<div class="profile-edit-container">
    <h2>แก้ไขโปรไฟล์</h2>

    <?php if($success) echo "<p class='success-msg'>{$success}</p>"; ?>
    <?php if($error) echo "<p class='error-msg'>{$error}</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>รูปโปรไฟล์</label>
        <?php if(is_array($user) && isset($user['profile_pic']) && $user['profile_pic']): ?>
            <div class="profile-preview"><img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile"></div>
        <?php endif; ?>
        <input type="file" name="profile_pic" accept="image/*">

        <label>แบนเนอร์</label>
        <?php if(is_array($user) && isset($user['banner_pic']) && $user['banner_pic']): ?>
            <div class="profile-preview"><img src="<?= htmlspecialchars($user['banner_pic']) ?>" alt="Banner"></div>
        <?php endif; ?>
        <input type="file" name="banner_pic" accept="image/*">

        <label>ชื่อผู้ใช้</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>ประเทศ</label>
        <input type="text" name="country" value="<?= htmlspecialchars($user['country']) ?>">

        <label>Bio</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

        <div class="form-actions">
            <a href="account.php" class="back-btn">ย้อนกลับ</a>
            <button type="submit" name="save_profile">บันทึก</button>
        </div>
    </form>
</div>

<?php include 'phpinclude/footer.php'; ?>
</body>
</html>
