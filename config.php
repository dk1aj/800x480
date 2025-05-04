<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SVXLink Control</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <meta http-equiv="refresh" content="2;url=index.php"> -->
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 11pt arial, sans-serif;
      overflow-x: hidden;
    }

    .container {
      max-width: 790px;
      margin: 0 auto;
      text-align: center;
    }

    button {
      margin: 8px;
      padding: 14px 20px;
      font-size: 16px;
      font-weight: bold;
      letter-spacing: 0.5px;
      border-radius: 10px;
      width: 200px;
      height: 60px;
      color: white;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
      transition: transform 0.1s ease, box-shadow 0.1s ease;
      touch-action: manipulation;
    }

    button:active {
      transform: scale(0.96);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) inset;
    }

    .red {
      background-color: #b00020;
    }

    .orange {
      background-color: darkorange;
      color: black;
    }

    .green {
      background-color: #448f47;
    }

    .blue {
      background: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);
    }

    .purple {
      background-color: purple;
    }

    .nav-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 12px;
      margin-top: 20px;
    }

    .nav-button {
      display: inline-block;
    }

    .back-button-container {
      position: fixed;
      bottom: 15px;
      left: 50%;
      transform: translateX(-50%);
      width: 280px;
      z-index: 1000;
    }
    .striped {

      .striped {
 background-image: repeating-linear-gradient(
 45deg,
 #ffffff,
 #ffffff 10px,
 #000000 10px,
 #000000 20px
 );
 color: white;
}



  </style>
</head>
<body>
  <div class="container">
    <?php
    if (isset($_POST['button99'])) {
      shell_exec('sudo /home/pi/pkill-pi.sh');
      // echo '<h1 style="color:#FFFFFF;">CHROME RESTART</h1>';
      echo '<h1 style="color:#FFFFFF;" data-auto-redirect>CHROME RESTART</h1>';
    }
    if (isset($_POST['button98'])) {
      shell_exec('sudo shutdown now');
      echo '<h1 style="color:#454545;" data-auto-redirect>Shutdown</h1>';
    }
    if (isset($_POST['button97'])) {
      shell_exec('sudo reboot');
      echo '<h1 style="color:#454545;">Reboot</h1>';
    }
    if (isset($_POST['button96'])) {
      shell_exec('sudo systemctl stop svxlink');
      echo '<h1 style="color:#454545;">SvxLink Stop</h1>';
    }
    if (isset($_POST['button95'])) {
      shell_exec('sudo systemctl restart svxlink');
      echo '<h1 style="color:#ff4444;" data-auto-redirect>SvxLink Restart</h1>';
    }
// 
// 
    ?>
    <form method="post">
      <button class="red" name="button97">RPi reboot</button>
      <button class="red" name="button98">RPi shutdown</button><br>
      <button class="orange" name="button96">SvxLink stop</button>
      <button class="orange" name="button95">SvxLink restart</button>
      <!-- <button class="orange" name="button99">Chrome restart</button> -->
      <button class="blue" name="button99">Chrome restart</button>

    </form>

    <div class="nav-container">
      <div class="nav-button"><a href="reflector.php"><button class="blue">SvxReflector<br>switch</button></a></div>
      <div class="nav-button"><a href="frn.php"><button class="green">FRN<br>client</button></a></div>
      <div class="nav-button"><a href="status.php"><button class="green">Status<br>svxlink</button></a></div>
      <div class="nav-button"><a href="lh.php"><button class="green">Lastheard List</button></a></div>
      <div class="nav-button"><a href="extra.php"><button class="orange">EXTRA SET</button></a></div>
      <div class="nav-button"><a href="update.php"><button class="purple">UPDATE CALL & TG </button></a></div>
    </div>
  </div>

  <div class="back-button-container">
    <?php
    $backTarget = 'index.php';
    include_once 'include/back_button.php'; 
    ?>
  </div>
  
  <script>
  // Nur weiterleiten, wenn eine Meldung angezeigt wurde
  const messageShown = document.querySelector('h1[data-auto-redirect]');
  if (messageShown) {
    setTimeout(() => {
      window.location.href = "index.php";
    }, 1000);
  }
</script>
</body>
</html>
