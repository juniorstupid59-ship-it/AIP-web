<?php
// logout.php
require_once __DIR__ . "/phpinclude/functions.php";

// เรียกฟังก์ชันออกจากระบบ
logoutUser();

// หลังจาก logout เสร็จ → redirect ไปหน้า signin
header("phpinclude/Location: signin.php");
exit;
