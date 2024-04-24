<?php
require_once('../common/head_parts.php');
?>

<!doctype html>
<html lang="ja">
<head>
    <?= head_parts('login') ?>
    <style>
    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
    }
    </style>
</head>
<body class="text-center">
    <main class="form-signin">
        <form name="form1" action="login_act.php" method="post">
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
            <?php if (isset($_GET['form_empty'])): ?>
            <p class="text-danger">ID,PWを確認してください。</p>
            <?php elseif (isset($_GET['form_validation'])): ?>
            <p class="text-danger">IDかPWに間違いがあります。</p>
            <?php elseif (isset($_GET['login_error'])): ?>
            <p class="text-danger">loginエラーです</p>
            <?php endif;?>
            <div class="form-floating">
                <input type="text" class="form-control" name="lid" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">ID</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="lpw" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <br>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </main>
</body>
</html>
