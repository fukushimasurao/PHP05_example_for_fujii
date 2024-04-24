<?php
session_start();
require_once('../funcs.php');
require_once('../common/head_parts.php');
require_once('../common/header_nav.php');
loginCheck();

$id = $_GET['id'];
$pdo = db_conn();


//２．データ登録SQL作成
$stmt = $pdo->prepare('SELECT * FROM gs_content_table WHERE id=:id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if ($status == false) {
    sql_error($stmt);
} else {
    $row = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <?= head_parts('内容更新') ?>
</head>
<body>
    <?=  $header_nav?>

    <?php if (isset($_GET['error'])): ?>
        <p class="text-danger">記入内容を確認してください</p>
    <?php endif;?>
    <form method="POST" action="update.php" class="mb-3"  enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">タイトル</label>
            <input type="text" class="form-control" name="title" id="title" aria-describedby="title" value="<?= $row["title"] ?>">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">記事内容</label>
            <textArea type="text" class="form-control" name="content" id="content" aria-describedby="content" rows="4" cols="40"><?= $row["content"] ?></textArea>
        </div>

        <div class="mb-3 w-25">
        <?php if($row['img'] !== '') : ?>
            <!-- 画像が登録されている場合は↓ -->
            <img src="../images/<?= $row['img'] ?>" alt="" class="bd-placeholder-img card-img-top" >
        <?php else : ?>
            <!-- 画像が登録されていない場合↓ -->
            <img src="../images/default_image/no_image_logo.png" alt="" class="bd-placeholder-img card-img-top" >
        <?php endif ?>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">画像</label>
            <input type="file" name="img">
        </div>

        <input type="hidden" name="id" id="id" aria-describedby="id" value="<?= $row["id"] ?>">
        <button type="submit" class="btn btn-primary">修正</button>
    </form>
    <form method="POST" action="delete.php?id=<?= $row['id'] ?>" class="mb-3">
        <button type="submit" class="btn btn-danger">削除</button>
    </form>
</body>

</html>
