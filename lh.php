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
  <meta http-equiv="refresh" content="10;url=config.php" />
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

<body style="max-width: 790px; background-color:rgb(0, 0, 0); font: 11pt arial, sans-serif; margin: 0;">
  <center>
    <?php
      echo '<script type="text/javascript">' . "\n";
      echo 'function reloadLhInfo(){' . "\n";
      echo '  $("#syslh").load("include/lastheard.php",function(){ setTimeout(reloadLhInfo,5000) });' . "\n";
      echo '}' . "\n";
      echo 'setTimeout(reloadLhInfo,5000);' . "\n";
      echo '$(window).trigger("resize");' . "\n";
      echo '</script>' . "\n";
      echo '<div id="syslh">' . "\n";
      include 'include/lastheard.php';
      echo '</div>' . "\n";
    ?>

    <div class="back-button-container">
      <?php
        $backTarget = 'index.php';
        include_once 'include/back_button.php'; 
      ?>
    </div> 
  </center>
</body>
</html>
