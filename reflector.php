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
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <link rel="shortcut icon" href="images/dk1aj_lg.png" sizes="16x16 32x32" type="image/png">
   <title>SvxDSI Sound</title>

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
      if (MENUBUTTON == "BOTTOM") {
        include_once __DIR__ . "/include/buttons_ref.php";
      }
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
