<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>DEVCODE | LOGIN</title>
    <!-- CSS locate component -->
    <?= $this->include('components/css.php') ?>
    <link href="<?= base_url() ?>/components/docs/animBack/index.css" rel="stylesheet">
  </head>

  <body class="login large-header" id="large-header"> 
    <canvas id="demo-canvas"></canvas>
    <!-- loading -->
    <div id="loading">
      <div class="d-flex justify-content-center align-items-center vh-100">
          <div class="prifix_loading_box"> <span></span> <span></span> <span></span> <span></span> <span></span> </div>
      </div>
    </div>
    <!-- loading -->

    <div id="contenue">

    </div>

    <script src="<?= base_url() ?>/components/docs/animBack/TweenMax.min.js"></script>
    <script src="<?= base_url() ?>/components/docs/animBack/index.js"></script>
    <?= $this->include('components/js.php') ?>
    <script src="<?= base_url()?>/function/constant.js"></script>
    <script src="<?= base_url()?>/function/user/choice.js"></script>

  </body>
</html>
