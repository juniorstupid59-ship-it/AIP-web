<?php
require "phpinclude/functions.php";
include "phpinclude/header.php";

if (!isLoggedIn()) header("phpinclude/Location: signin.php");

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT is_creator FROM users WHERE id=? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$isCreator = $user && $user['is_creator']==1;

$preview = false;
$title = $content = $type = "";
$forSale = 0;
$imagePreview = "";

if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['type'] ?? "normal";
    $forSale = isset($_POST['for_sale']) ? 1 : 0;

    if(!empty($_FILES['image']['name'])){
        $targetDir="uploads/";
        if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
        $filename=time()."_".basename($_FILES["image"]["name"]);
        $targetFile=$targetDir.$filename;
        if(move_uploaded_file($_FILES["image"]["tmp_name"],$targetFile)){
            $imagePreview=$targetFile;
        }
    }
    $preview = true;
}
?>

<main class="account-page">
  <section class="post-create">
    <h2>Create Post</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Title</label>
      <input type="text" name="title" required value="<?= htmlspecialchars($title) ?>">

      <label>Content</label>
      <textarea name="content" required><?= htmlspecialchars($content) ?></textarea>

      <label>Image</label>
      <input type="file" name="image" accept="image/*">

      <label>Type</label>
      <select name="type">
        <option value="normal" <?= $type=="normal"?"selected":"" ?>>Normal</option>
        <option value="manga" <?= $type=="manga"?"selected":"" ?>>Manga</option>
        <option value="novel" <?= $type=="novel"?"selected":"" ?>>Novel</option>
      </select>

      <?php if($isCreator): ?>
        <label><input type="checkbox" name="for_sale" value="1" <?= $forSale?"checked":"" ?>> For Sale</label>
      <?php endif; ?>

      <button type="submit">Preview</button>
    </form>
  </section>

  <?php if($preview): ?>
  <section class="post-preview">
    <h2>Preview</h2>
    <div class="post-card">
      <h3><?= htmlspecialchars($title) ?></h3>
      <p><?= nl2br(htmlspecialchars($content)) ?></p>
      <?php if($imagePreview): ?>
        <img src="<?= $imagePreview ?>" style="max-width:300px;">
      <?php endif; ?>
      <p>Type: <?= htmlspecialchars($type) ?></p>
      <?php if($isCreator && $forSale): ?>
        <p style="color:#3b82f6;">💰 For Sale</p>
      <?php endif; ?>
    </div>
    <form method="POST" action="phpinclude/savepost.php">
      <input type="hidden" name="title" value="<?= htmlspecialchars($title) ?>">
      <input type="hidden" name="content" value="<?= htmlspecialchars($content) ?>">
      <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
      <input type="hidden" name="for_sale" value="<?= $forSale ?>">
      <input type="hidden" name="image" value="<?= $imagePreview ?>">
      <button type="submit">Publish</button>
    </form>
  </section>
  <?php endif; ?>
</main>

<?php include "phpinclude/footer.php"; ?>
