<?php
session_start();
require_once('../funcs.php');
require_once('../common/head_parts.php');
require_once('../common/header_nav.php');

loginCheck();

$title = '';
$content = '';

if (isset($_SESSION['post']['title'])) {
    $title = $_SESSION['post']['title'];
}

if (isset($_SESSION['post']['content'])) {
    $content = $_SESSION['post']['content'];
}

if (isset($_SESSION['post']['image_data']) && $_SESSION['post']['image_data'] !== '') {
    $image_data = $_SESSION['post']['image_data'];
}


?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('記事管理') ?>
</head>

<body>
    <?=  $header_nav?>

    <!-- // もしURLパラメータがある場合 -->
    <?php if (isset($_GET['error'])) : ?>
        <p class='text-danger'>記入内容を確認してください。</p>
    <?php endif ?>

    <form method="POST" action="confirm.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">タイトル</label>
            <input type="text" class="form-control" name="title"
            id="title" aria-describedby="title"
            value="<?= $title ?>">
            <div id="emailHelp" class="form-text">※入力必須</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">記事内容</label>
            <textArea type="text" class="form-control" name="content"
            id="content" aria-describedby="content"
            rows="4" cols="40"><?= $content ?></textArea>
            <div id="emailHelp" class="form-text">※入力必須</div>
        </div>

        <div class="mb-3">
            <?php if (isset($image_data)): ?>
            <img src="image.php">
            <?php endif;?>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">画像</label>
            <input type="file" name="img">
        </div>

        <button type="submit" class="btn btn-primary">確認する</button>
    </form>
</body>
</html>
