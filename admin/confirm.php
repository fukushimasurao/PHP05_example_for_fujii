<?php
// confirm.phpの中身は、ほとんどpost.phpに似ています。
session_start();
require_once('../funcs.php');
require_once('../common/head_parts.php');
require_once('../common/header_nav.php');

loginCheck();

// post受け取る
$title = $_POST['title'];
$content = $_POST['content'];
$_SESSION['post']['title'] = $_POST['title'];
$_SESSION['post']['content'] = $_POST['content'];

// formで送られてきたら
if ($_FILES['img']['name'] !== '') {
    $file_name = $_SESSION['post']['file_name'] = $_FILES['img']['name'];
    $image_data = $_SESSION['post']['image_data'] = file_get_contents($_FILES['img']['tmp_name']);
    $image_type = $_SESSION['post']['image_type'] = exif_imagetype($_FILES['img']['tmp_name']);

} elseif(isset($_SESSION['post']) && isset($_SESSION['post']['image_data']) && $_SESSION['post']['image_data'] !== '' && $_FILES['img']['name'] === '') {
    // ファイルで送らないけどセッションの中にデータがあれば
    $file_name = $_SESSION['post']['file_name'];
    $image_data = $_SESSION['post']['image_data'];
    $image_type = $_SESSION['post']['image_type'];

    // formにも、セッションにも何もデータがなければ。
} else {
    $file_name = $_SESSION['post']['file_name'] = '';
    $image_data = $_SESSION['post']['image_data'] = '';
    $image_type = $_SESSION['post']['image_type'] = '';
}

// 簡単なバリデーション処理。
if (trim($title) === '' || trim($content) === '') {
    redirect('post.php?error');
}

// imgある場合
// 添付ファイルの拡張子を確認する。
if (!empty($file_name)) {
    $extension = substr($file_name, -3);
    if ($extension != 'jpg' && $extension != 'gif' && $extension != 'png') {
        redirect('post.php?error=1');
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('記事管理') ?>
</head>

<body>
    <?=  $header_nav?>
    <!-- errorを受け取ったら、エラー文出力。 -->

    <form method="POST" action="register.php" enctype="multipart/form-data" class="mb-3">
        <div class="mb-3">
            <label for="title" class="form-label">タイトル</label>
            <input type="hidden"name="title" value="<?= $title ?>">
            <p><?= $title ?></p>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">記事内容</label>
            <input type="hidden"name="content" value="<?= $content ?>">
            <div><?= nl2br($content) ?></div>
        </div>

        <?php if ($image_data) :?>
            <div class="mb-3">
                <img src="image.php">
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">投稿</button>
    </form>

    <a href="post.php?re-register=true">前の画面に戻る</a>
</body>
</html>
