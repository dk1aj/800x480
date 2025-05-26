<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'], ".php");
include_once 'include/config.php';
include_once 'include/tools.php';
include_once 'include/browserdetect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>SvxDSI Dashboard</title>
  <link rel="shortcut icon" href="images/DSI_2.png" sizes="16x16 32x32" type="image/x-icon">
  <!-- <link rel="stylesheet" href="css/style.css"> -->

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Fredoka+One&family=Tourney&family=Oswald&display=swap">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <!-- Featherlight CSS -->
  <link rel="stylesheet" href="css/featherlight.css">

  <!-- Scripts -->
  <script src="scripts/jquery.min.js"></script>
  <script src="scripts/functions.js"></script>
  <script src="scripts/pcm-player.min.js"></script>
  <script src="scripts/featherlight.js"></script>

  <script>
    $.ajaxSetup({ cache: false });

    $(document).ready(function () {
      function reloadSVXREF() {
        $("#svxref").load("include/talk.php?_=" + new Date().getTime(), function () {
          setTimeout(reloadSVXREF, 1000);
        });
      }

      function reloadSysInfo() {
        $("#sysInfo").load("include/system.php?_=" + new Date().getTime(), function () {
          setTimeout(reloadSysInfo, 10000);
        });
      }

      reloadSVXREF();
      reloadSysInfo();
    });
  </script>

  <style>
    html, body {
      margin: 0;
      padding: 0;
      background-color: black;
      font: 12pt Arial, sans-serif;
      color: white;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
    }

    .flex-content {
      width: 800px;
      height: 480px;
      margin: 0;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      position: relative;
      background-color: black;
    }

    #sysInfo {
      width: 100%;
      text-align: center;
      margin-top: 0;
      padding-top: 1;
      position: relative;
      top: 0;
    }

footer {
  position: absolute;
  bottom: 20px;
  left: 2px;
  right: 45px;
  /* background-color: white; */
  /* color: #fff; */
  /* z-index: 999; */
  /* text-align: center; */
  padding: 8px 8px;
  /* box-sizing: border-box; */
  /* font-size: 16px; */
  /* line-height: 1.4; */
}

  </style>
</head>

<body>
  <div class="flex-content">
    <div id="sysInfo">
      <?php include 'include/system.php'; ?>
    </div>

    <div id="svxref">
      <?php include 'include/talk.php'; ?>
    </div>

    <footer>
      <?php include 'include/menu.php'; ?>
    </footer>
  </div>
</body>
</html>
