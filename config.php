<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DSI Pixel-perfect display Control</title>
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="refresh" content="2;url=index.php"> 
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 11pt Arial, sans-serif;
      overflow: hidden;
      width: 800px;
      height: 480px;
      position: relative;
    }

    .container {
      width: 100%;
      height: 100%;
      padding: 20px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: flex-start;
    }

    .button-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: repeat(4, auto);
      gap: 10px;
      width: 100%;
      margin-bottom: 20px;
    }

    button {
      padding: 14px 20px;
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 0.5px;
      border-radius: 10px;
      color: white;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
      transition: transform 0.1s ease, box-shadow 0.1s ease;
      touch-action: manipulation;
      width: 100%;
      height: 60px;
    }

    button:active {
      transform: scale(0.96);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) inset;
    }

    .red { background-color: #b00020; }
    .orange { background-color: darkorange; color: black; }
    .green { background-color: #448f47; }
    .blue {
      background: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);
    }
    .purple { background-color: purple; }

    .nav-container {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
      margin-top: 10px;
    }

    .nav-button {
      display: inline-block;
    }

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
  <div class="container">
    <?php
      $statusMessage = "";

      if (isset($_POST['button99'])) {
        shell_exec('sudo /home/pi/pkill-pi.sh');
        echo '<h1 style="color:#FFFFFF;" data-auto-redirect>Chrome will restart on DSI</h1>';
      }
      if (isset($_POST['button98'])) {
        shell_exec('sudo shutdown now');
        echo '<h1 style="color:#454545;" data-auto-redirect>Raspberry Shutdown</h1>';
      }
      if (isset($_POST['button97'])) {
        shell_exec('sudo reboot');
        echo '<h1 style="color:#454545;">Raspberry Reboot</h1>';
      }
      if (isset($_POST['button96'])) {
        shell_exec('sudo systemctl stop svxlink');
        echo '<h1 style="color:red;">The SvxLink service has stopped.<br>SvxLink is no longer running. </h1>';
      }
      if (isset($_POST['button95'])) {
        shell_exec('sudo systemctl restart svxlink');
        echo '<h1 style="color:green;" data-auto-redirect> SvxLink service is about to restart</h1>';
      }

      // Neue Dummy-Buttons mit sichtbarem Feedback
      if (isset($_POST['spk_mute'])) {
        shell_exec('sudo /home/pi/vol_ctr.sh spk_mute');
        $statusMessage = "Speaker wurde stummgeschaltet.";
      }
      if (isset($_POST['mic_mute'])) {
        shell_exec('sudo /home/pi/dummy.sh mic_mute');
        $statusMessage = "nichts gemacht.";
      }
      if (isset($_POST['spk_vol_up'])) {
        shell_exec('sudo /home/pi/vol_ctr.sh spk_vol_up');
        $statusMessage = "Lautsprecherlautstärke erhöht.";
      }
      if (isset($_POST['spk_vol_dwn'])) {
        shell_exec('sudo /home/pi/vol_ctr.sh spk_vol_dwn');
        $statusMessage = "Lautsprecherlautstärke verringert.";
      }
      if (isset($_POST['mic_vol_up'])) {
        shell_exec('sudo /home/pi/vol_ctr.sh mic_vol_up');
        $statusMessage = "Mikrofonlautstärke erhöht.";
      }
      if (isset($_POST['mic_vol_dwn'])) {
        shell_exec('sudo /home/pi/vol_ctr.sh mic_vol_dwn');
        $statusMessage = "Mikrofonlautstärke verringert.";
      }

      // Nachricht ausgeben (wenn gesetzt)
      if (!empty($statusMessage)) {
        echo '<h1 style="color:lightblue;">' . htmlspecialchars($statusMessage) . '</h1>';
      }
    ?>

    <form method="post" class="button-grid">
      <button class="red" name="button97">Raspberry<br>reboot</button>
      <button class="red" name="button98">Raspberry<br>shutdown</button>
      <button class="green" name="button199">never touch<br>this button</button>
      <button class="orange" name="button96">SvxLink<br>stop</button>
      <button class="orange" name="button95">SvxLink<br>restart</button>
      <button class="blue" name="button99">Chrome<br>restart</button>
    </form>

    <div class="nav-container">
      <div class="nav-button"><a href="reflector.php"><button class="blue">SvxReflector switch</button></a></div>
      <div class="nav-button"><a href="status.php"><button class="green">Status<br>svxlink</button></a></div>
      <div class="nav-button"><a href="lh.php"><button class="green">Lastheards<br>List</button></a></div>
      <div class="nav-button"><a href="extra.php"><button class="orange">EXTRA<br>SET</button></a></div>
      <div class="nav-button"><a href="update.php"><button class="purple"> ADD<br>CALL & TG </button></a></div>
      <div style="width: 100%; height: 20px;"></div>
    </div>

    <form method="post" class="nav-container">
      <div class="nav-button"><button class="blue" name="spk_mute">SPK<br>MUTE</button></div>
      <div class="nav-button"><button class="blue" name="mic_mute">MIC<br>MUTE</button></div>
      <div class="nav-button"><button class="orange" name="spk_vol_up">SPK<br>VOL UP</button></div>
      <div class="nav-button"><button class="blue" name="spk_vol_dwn">SPK<br>VOL DWN</button></div>
      <div class="nav-button"><button class="green" name="mic_vol_up">MIC<br>VOL UP</button></div>
      <div class="nav-button"><button class="blue" name="mic_vol_dwn">MIC<br>VOL DOWN</button></div>
    </form>
  </div>

  <?php if (empty($statusMessage)) : ?>
    <div class="back-button-container">
      <?php
      $backTarget = 'index.php';
      include_once 'include/back_button.php'; 
      ?>
    </div>
  <?php endif; ?>

  <script>
    const messageShown = document.querySelector('h1[data-auto-redirect]');
    if (messageShown) {
      setTimeout(() => {
        window.location.href = "index.php";
      }, 1000);
    }
  </script>
</body>
</html>
