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
  <link href="css/featherlight.css" rel="stylesheet" />
  <script src="scripts/featherlight.js" charset="utf-8"></script>
</head>
<body style="max-width: 790px; background-color: #000000; font: 11pt arial, sans-serif; margin: 0;">
<center>
<?php
  echo '<script type="text/javascript">' . "\n";
  echo 'function reloadStatusInfo(){' . "\n";
  echo '  $("#sysstatus").load("include/status.php",function(){ setTimeout(reloadStatusInfo,5000) });' . "\n";
  echo '}' . "\n";
  echo 'setTimeout(reloadStatusInfo,5000);' . "\n";
  echo '$(window).trigger("resize");' . "\n";
  echo '</script>' . "\n";
  echo '<div id="sysstatus">' . "\n";
  include 'include/status.php';
  echo '</div>' . "\n";
?>
<p style="margin-top:30px;margin-bottom:0px;"></p>
<a href="index.php">
  <button class="white" style="height: 65px; width: 230px; font-size:16px;" name="button0">BACK</button>
</a>
</center>
</body>
</html>
