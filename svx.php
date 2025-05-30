<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'], ".php");
include_once 'include/config.php';
include_once 'include/tools.php';

$page_title = "SvxDSI Change Tg"; // Static title
$redirect_url = 'index.php';      // Static redirect URL
$redirect_delay = 5;              // Static redirect delay
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="DK1AJ">
  <meta http-equiv="refresh" content="<?php echo $redirect_delay; ?>;url=<?php echo htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8'); ?>">
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <link rel="icon" href="images/dk1aj_lg.png" sizes="16x16 32x32" type="image/png">
  <link href="css/featherlight.css" rel="stylesheet">

  <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>

  <?php include_once "include/browserdetect.php"; ?>

  <script src="scripts/jquery.min.js"></script>
  <script src="scripts/functions.js"></script>
  <script src="scripts/pcm-player.min.js"></script>
  <script>
    $.ajaxSetup({ cache: false });
  </script>
  <script src="scripts/featherlight.js" charset="utf-8"></script>

  <style>
    body {
      max-width: 790px;
      background-color: #000; 
      /* color: #fff;  Removed as there's no direct text from this page */
      font: 11pt Arial, sans-serif; 
      margin: 0 auto;
      text-align: center;
      /* padding-top: 20px; Removed as there's no content to pad */
    }
    /* Any content will now come from buttons_svx.php or back_button.php */
    .back-button-container {
      /* Adjust margin if buttons_svx.php already provides spacing or if it looks off */
      margin-top: 20px; 
    }
  </style>
</head>
<body>

<main>
  <?php
    // This include might display buttons or other UI elements.
    // If it displays nothing, the page will appear blank until redirect.
    include_once __DIR__ . "/include/buttons_svx.php";
  ?>

  <div class="back-button-container">
    <?php
      $backTarget = $redirect_url;
      include_once 'include/back_button.php';
    ?>
  </div>
</main>

</body>
</html>