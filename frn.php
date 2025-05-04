<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once 'include/config.php';
include_once 'include/tools.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="author" content="DK1AJ" />
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
  <link href="css/featherlight.css" rel="stylesheet" />
  <script src="scripts/featherlight.js" charset="utf-8"></script>
</head>
<body style="max-width: 790px; background-color:rgb(55, 177, 25); font: 11pt arial, sans-serif; margin: 0;">
<center>
<p><span style="color: #ffffff; font-size: 12pt; background-color: #000000;">Free Radio Network</span></p>
<?php
  if (MENUBUTTON == "BOTTOM") {
    include_once __DIR__ . "/include/buttons_frn.php";
  }
?>
<?php
  echo '<script type="text/javascript">' . "\n";
  echo 'function reloadSysInfo(){' . "\n";
  echo '  $("#sysInfo").load("include/talkfrn.php",function(){ setTimeout(reloadSysInfo,1000) });' . "\n";
  echo '}' . "\n";
  echo 'setTimeout(reloadSysInfo,1000);' . "\n";
  echo '$(window).trigger("resize");' . "\n";
  echo '</script>' . "\n";
  echo '<div id="sysInfo">' . "\n";
  include 'include/talkfrn.php';
  echo '</div>' . "\n";
?>
<p style="margin-top:30px;margin-bottom:0px;"></p>
<a href="index.php">
  <button class="white" style="height: 35px; width: 130px; font-size:16px;" name="button0">BACK</button>
</a>
</center>
</body>
</html>
