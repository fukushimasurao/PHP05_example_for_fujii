<?php

session_start();
require_once('../funcs.php');
loginCheck();

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$img = '';

// 簡単なバリデーション処理。
if (trim($_POST['title']) === '') {
    $err[] = 'タイトルを確認してください。';
}
if (trim($_POST['content']) === '') {
    $err[] = '内容を確認してください';
}

// imgがある場合
$fileName = '';
$img = '';
$isImg = false;
if (isset($_FILES['img']['name']) && $_FILES['img']['name'] !== '') {
    $isImg = true;
}

if ($isImg) {
    $fileName = $_FILES['img']['name'];
    $img = date('YmdHis') . '_' . $fileName;
    $extension =  substr($fileName, -3);
    if ($extension !== 'jpg' && $extension !== 'gif' && $extension !== 'png') {
        $err[] = '写真の内容を確認してください。';
    }
}

// もしerr配列に何か入っている場合はエラーなので、redirect関数でindexに戻す。その際、GETでerrを渡す。
if (isset($err) && count($err) > 0) {
    redirect('post.php?error=1');
}

//2. DB接続します
$pdo = db_conn();

// 古い画像を削除するために、削除する画像の名前を取得する。
$old_img = '';
if ($isImg) {
    $stmt = $pdo->prepare('SELECT img from gs_content_table WHERE id = :id;');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $status = $stmt->execute(); //実行
    if ($status == false) {
        $error = $stmt->errorInfo();
        exit("ErrorQuery:".$error[2]);
    } else {
        $row = $stmt->fetch();
        $old_img = $row['img'];
    }
}
//３．データ登録SQL作成
if ($isImg) {
    $fileName = $_FILES['img']['name'];
    $img = date('YmdHis') . '_' . $_FILES['img']['name'];
    $destinationPath = '../images/' . $img;
    if (!move_uploaded_file($_FILES['img']['tmp_name'], $destinationPath)) {
        exit('Failed to move uploaded file.');
    }

    $stmt = $pdo->prepare('UPDATE gs_content_table
                        SET
                            title = :title,
                            content = :content,
                            img = :img,
                            update_time = sysdate()
                        WHERE id = :id;');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':img', $img, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
} else {
    //  画像がない場合imgは省略する。
    $stmt = $pdo->prepare('UPDATE gs_content_table
                        SET
                            title = :title,
                            content = :content,
                            update_time = sysdate()
                        WHERE id = :id;');
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
}
$status = $stmt->execute(); //実行

//４．データ登録処理後
if (!$status) {
    sql_error($stmt);
}

// 古い画像を削除する
if ($old_img !== '' && file_exists("../images/" . $old_img)) {
    unlink("../images/" . $old_img);
}

redirect('index.php');
