<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SvxDSI Soundsteuerung</title>
  <meta http-equiv="refresh" content="5;url=index.php" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 11pt Arial, sans-serif;
      width: 800px;
      height: 480px;
      overflow: hidden;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .button-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      width: 90%;
      margin-top: 10px;
    }

    .button-column {
      display: flex;
      flex-direction: column;
      gap: 12px;
      align-items: center;
    }

    .top-button {
      margin-bottom: 6px;
    }

    button {
      padding: 14px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      height: 70px;
      touch-action: manipulation;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    .half-width {
      width: 54%;
    }

    .three-quarter-width {
      width: 75%;
    }

    button:not(.half-width):not(.three-quarter-width) {
      width: 100%;
    }


    button:active {
      transform: scale(0.96);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) inset;
    }

    .blue { background-color: #265a88; color: white; }
    .orange { background-color: darkorange; color: black; }
    .green { background-color: #448f47; color: white; }

    .back-button-container {
      position: absolute;
      bottom: 10px;
      left: 50%;
      transform: translateX(-50%);
      width: 280px;
      z-index: 1000;
    }
  </style>
</head>
<body>
  <?php
    $statusMessage = "";

    if (isset($_POST['spk_mute'])) {
      shell_exec('sudo /home/pi/vol_ctr.sh spk_mute');
      $statusMessage = "Lautsprecher wurde stummgeschaltet.";
    }
    if (isset($_POST['mic_mute'])) {
      shell_exec('sudo /home/pi/dummy.sh mic_mute');
      $statusMessage = "Mikrofon wurde (nicht) stummgeschaltet.";
    }
    if (isset($_POST['spk_vol_up'])) {
      shell_exec('sudo /home/pi/vol_ctr.sh spk_vol_up');
      $statusMessage = "Lautstärke erhöht.";
    }
    if (isset($_POST['spk_vol_dwn'])) {
      shell_exec('sudo /home/pi/vol_ctr.sh spk_vol_dwn');
      $statusMessage = "Lautstärke verringert.";
    }
    if (isset($_POST['mic_vol_up'])) {
      shell_exec('sudo /home/pi/vol_ctr.sh mic_vol_up');
      $statusMessage = "Mikrofonlautstärke erhöht.";
    }
    if (isset($_POST['mic_vol_dwn'])) {
      shell_exec('sudo /home/pi/vol_ctr.sh mic_vol_dwn');
      $statusMessage = "Mikrofonlautstärke verringert.";
    }

    if (!empty($statusMessage)) {
      echo "<h1 style=\"color:lightblue;\">$statusMessage</h1>";
    }
  ?>

  <form method="post" class="button-grid">
    <div class="button-column">
      <button class="blue top-button half-width" name="mic_mute">MIC<br>Mute</button>
      <button class="green three-quarter-width" name="mic_vol_up">MIC<br>Vol Up</button>
      <button class="blue" name="mic_vol_dwn">MIC<br>Vol Down</button>
    </div>
    <div class="button-column">
      <button class="blue top-button half-width" name="spk_mute">SPK<br>Mute</button>
      <button class="orange three-quarter-width" name="spk_vol_up">SPK<br>Vol Up</button>
      <button class="blue" name="spk_vol_dwn">SPK<br>Vol Down</button>
    </div>
  </form>

  <div class="back-button-container">
    <?php
      $backTarget = 'index.php';
      include_once 'include/back_button.php';
    ?>
  </div>
</body>
</html>
