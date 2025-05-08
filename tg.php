<?php
$progname = basename($_SERVER['SCRIPT_FILENAME'], '.php');
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

  <link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png" />
  <title>SVXLink Dashboard</title>

  <?php include_once 'include/browserdetect.php'; ?>

  <link rel="stylesheet" href="css/featherlight.css" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 16px Arial, sans-serif;
      color: #fff;
    }

    .content {
      padding: 10px;
    }

    .center-container {
      display: flex;
      flex-wrap: wrap;
      /* justify-content: flex-start; */
      justify-content: center;
      gap: 8px;
    }

    .touch-button {
      width: 100%;
      max-width: 200px;
      height: 60px;
      font-size: 22px;
      font-weight: 600;
      color: #ffffff;
      background-color: #007bff;
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

    .touch-button8 {
      background: linear-gradient(to bottom, #00b09b, #96c93d);
    }

    .back-button-container {
      width: 100%;
      text-align: center;
      margin-top: 5px;
    }
  </style>
</head>
<body>

<?php
// DTMF-Ausführung
foreach ($_POST as $key => $val) {
  if (preg_match('/^button(\d+)$/', $key, $matches)) {
    $btnNum = $matches[1];
    $constName = 'KEY' . $btnNum;
    if (defined($constName)) {
      $cmd = constant($constName)[1];
      if (!empty($cmd)) {
        if ($btnNum == 8) {
          exec($cmd, $output);
        } else {
          exec("echo '$cmd' > /tmp/dtmf_svx", $output);
        }
      }
    }
    echo "<meta http-equiv='refresh' content='0'>";
    exit;
  }
}
?>

<div class="content">
  <form method="post">
    <div class="center-container">
      <?php
      $buttonKeys = [111, 112, 113, 1, 2, 3, 4, 5, 6, 7, 11, 12, 13, 14, 15, 16];
      foreach ($buttonKeys as $i) {
        $keyVar = 'KEY' . $i;
        if (defined($keyVar)) {
          $data = constant($keyVar);
          $label = $data[0] ?? "KEY$i";
          $cssClass = $data[2] ?? "touch-button";
          echo '<button class="touch-button ' . htmlspecialchars($cssClass) . '" name="button' . $i . '">' . htmlspecialchars($label) . '</button>' . PHP_EOL;
        }
      }
      ?>

      <div class="back-button-container">
        <a href="index.php">
          <button type="button" class="touch-button touch-button8">BACK</button>
        </a>
      </div>
    </div>
  </form>
</div>

<script src="scripts/jquery.min.js"></script>
<script src="scripts/functions.js"></script>
<script src="scripts/pcm-player.min.js"></script>
<script src="scripts/featherlight.js" charset="utf-8"></script>
<script>
  $.ajaxSetup({ cache: false });
</script>

</body>
</html>
