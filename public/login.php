<?php
  require_once __DIR__ . '/bootstrap.php';

  // 若已登入則導回首頁
  if (isset($_SESSION['user'])) {
    header('Location: /');
    exit();
  }

?>
<!doctype html>
<html lang="zh-Hant-TW">
  <head>
    <?php require_once(__DIR__ . '/partial/head.php'); ?>

    <title>登入</title>
    <style>
      img#ntpc {
        cursor: pointer;
        transform: scale(1);
        transition: all 0.5s;
      }
      img#ntpc:hover {
        transform: scale(1.2);
      }
    </style>
  </head>
  <body>
    <?php require_once(__DIR__ . '/partial/navbar.php'); ?>

    <div class="container my-4">
      <h2 class="text-center mb-4">登入</h2>
      <div class="row">
        <div class="col-md-4 offset-md-2">
          <form method="post" action="auth/auth.php">
            <div class="form-group">
              <label for="username">帳號</label>
              <input type="text" class="form-control" name="username" id="username" aria-describedby="emailHelp" placeholder="帳號">
            </div>
            <div class="form-group">
              <label for="password">密碼</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="密碼">
            </div>
            <button type="submit" class="btn btn-primary btn-block">登入</button>
            <input type="hidden" name="op" value="localLogin">
          </form>
        </div>
        <div class="col-md-4 d-flex justify-content-center align-items-center">
          <img src="images/ntpc.png" alt="OpenID 登入" id="ntpc" onclick="location.href='auth/ntpcOpenid/ntpcOpenidLogin.php'">
        </div>
      </div>
    </div>
    
    <?php require_once(__DIR__ . '/partial/js.php'); ?>
  </body>
</html>
