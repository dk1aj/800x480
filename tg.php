<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'], ".php");
include_once 'include/config.php';
include_once 'include/tools.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="author" content="DK1AJ" />
  <meta http-equiv="refresh" content="5;url=index.php" />
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">

  <title>SVXLink Dashboard</title>

  <?php include_once "include/browserdetect.php"; ?>

  <script src="scripts/jquery.min.js"></script>
  <script src="scripts/functions.js"></script>
  <script src="scripts/pcm-player.min.js"></script>
  <script>
    $.ajaxSetup({ cache: false });
  </script>
  <link rel="stylesheet" href="css/featherlight.css" />
  <script src="scripts/featherlight.js" charset="utf-8"></script>

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 11pt arial, sans-serif;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .wrapper {
      max-width: 790px;
      width: 100%;
      text-align: center;
    }

    .back-button-container {
      text-align: center;
      margin: 10px 0;
    }

    .center-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 8px;
      padding: 10px;
    }

    .touch-button {
      width: 100%;
      max-width: 200px;
      height: 60px;
      font-size: 22px;
      font-weight: 600;
      color: #ffffff;
      border: none;
      border-radius: 18px;
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
      transition: all 0.2s ease-in-out;
      cursor: pointer;
      touch-action: manipulation;
      margin: 4px;
    }

    .touch-button:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
    }

    .touch-button:active {
      transform: scale(0.97);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .touch-button8 { background: linear-gradient(to bottom, #00b09b, #96c93d); }
  </style>
</head>
<body>
  <div class="content">
    <div class="wrapper">
      <?php
        if (MENUBUTTON == "BOTTOM") {
          include_once __DIR__ . "/include/buttons_tg.php";
        }
      ?>
    </div>
  </div>

  <div class="back-button-container">
    <a href="index.php">
      <button class="touch-button touch-button8">BACK</button>
    </a>
  </div>
</body>
</html>
