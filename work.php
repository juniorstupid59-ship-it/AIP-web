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
// Content (ภาษาไทย / ภาษาอังกฤษ) สำหรับหน้า Work
// -----------------------------
$work_content_th = [
    "all" => "ทั้งหมด",
    "post_job" => "โพสต์งานของคุณ",
    "submit" => "ส่ง",
    "apply" => "สมัคร",
    "save_job" => "บันทึกงาน",
    "no_jobs" => "ยังไม่มีงานโพสต์",
    "search_placeholder" => "ค้นหา...",
    "job_title_placeholder" => "ชื่องาน",
    "job_desc_placeholder" => "รายละเอียดงาน",
    "job_price_placeholder" => "ราคา (บาท)"
];

$work_content_en = [
    "all" => "All",
    "post_job" => "Post Your Job",
    "submit" => "Submit",
    "apply" => "Apply",
    "save_job" => "Save Job",
    "no_jobs" => "No jobs posted yet",
    "search_placeholder" => "Search...",
    "job_title_placeholder" => "Job Title",
    "job_desc_placeholder" => "Job Description",
    "job_price_placeholder" => "Price (THB)"
];

// -----------------------------
// ตัวอย่างข้อมูลงาน (จริงๆ โหลดจาก DB)
// -----------------------------
$jobs = [
    ["id"=>1,
     "title_th"=>"วาดภาพปกนิยาย", "title_en"=>"Novel Cover Illustration",
     "desc_th"=>"ต้องการภาพประกอบปกนิยายแฟนตาซี", "desc_en"=>"Illustrate a fantasy novel cover",
     "price"=>500, "type"=>"art"],
    ["id"=>2,
     "title_th"=>"ออกแบบตัวละคร", "title_en"=>"Character Design",
     "desc_th"=>"วาดตัวละครหลักสำหรับมังงะ", "desc_en"=>"Draw main characters for a manga",
     "price"=>800, "type"=>"manga"]
];

// เลือก content ตามภาษาที่เลือก
$content = ($currentLang == 'en') ? $work_content_en : $work_content_th;
?>

<!DOCTYPE html>
<html lang="<?= $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LightTooN - Work</title>

    <!-- CSS -->
    <?php
    $css_version = filemtime('css/style.css');
    $work_css_version = filemtime('css/work.css');
    ?>
    <link rel="stylesheet" href="css/style.css?v=<?= $css_version ?>">
    <link rel="stylesheet" href="css/work.css?v=<?= $work_css_version ?>">
</head>
<body>
    <?php include("phpinclude/header.php"); ?>

    <main class="work-page">
        <!-- Sidebar filters -->
        <aside class="work-sidebar">
            <div class="title" data-key="all"><?= $content['all'] ?></div>
            <div class="filter-group">
                <select id="jobFilter">
                    <option value="all" data-key="all"><?= $content['all'] ?></option>
                    <option value="art">Art</option>
                    <option value="manga">Manga</option>
                    <option value="novel">Novel</option>
                </select>
            </div>
            <input type="text" id="jobSearch">
        </aside>

        <!-- Job List -->
        <section class="job-list">
            <?php if(isLoggedIn()): ?>
                <!-- Post Job Form -->
                <form id="jobForm" class="post-job-form">
                    <div class="title" data-key="post_job"><?= $content['post_job'] ?></div>
                    <input type="text" id="jobTitle">
                    <textarea id="jobDesc"></textarea>
                    <input type="number" id="jobPrice">
                    <button type="submit" class="btn" data-key="submit"><?= $content['submit'] ?></button>
                </form>
            <?php endif; ?>

            <?php if(!empty($jobs)): ?>
                <?php foreach($jobs as $job): ?>
                    <div class="job-card job-item" data-type="<?= $job['type'] ?>">
                        <div class="job-header">
                            <div class="job-title"><?= $currentLang=='en' ? $job['title_en'] : $job['title_th'] ?></div>
                            <div class="job-meta"><?= $job['price'] ?> <?= $currentLang=='en' ? 'THB' : 'บาท' ?></div>
                        </div>
                        <div class="job-desc"><?= $currentLang=='en' ? $job['desc_en'] : $job['desc_th'] ?></div>
                        <div class="job-actions">
                            <button class="btn btn-apply" data-job-id="<?= $job['id'] ?>" data-key="apply"><?= $content['apply'] ?></button>
                            <button class="btn btn-save-job" data-key="save_job"><?= $content['save_job'] ?></button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="work-empty" data-key="no_jobs"><?= $content['no_jobs'] ?></div>
            <?php endif; ?>
        </section>
    </main>

    <?php include("phpinclude/footer.php"); ?>

    <script src="js/work.js"></script>
</body>
</html>
