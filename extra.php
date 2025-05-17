<?php
define('ALLOW_LOGIC_CHANGES', false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="DK1AJ">
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="refresh" content="5;url=index.php">
  <title>SvxDSI Control</title>
  <style>
    :root {
      --bg-dark: #000;
      --text-dark: #ccc;
      --accent: DarkOrange;
    }

    html, body {
      margin: 0;
      padding: 0;
      width: 800px;
      height: 480px;
      background-color: var(--bg-dark);
      color: var(--text-dark);
      font: 12pt Arial, sans-serif;
      overflow: hidden;
      position: relative;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      align-items: center;
      padding-top: 40px;
      box-sizing: border-box;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    }

    .button-row {
      display: flex;
      gap: 20px;
      justify-content: center;
    }

    .button {
      background-color: var(--accent);
      color: black;
      font-size: 20px;
      font-weight: bold;
      border: none;
      padding: 25px 50px;
      border-radius: 12px;
      cursor: pointer;
      width: 320px;
      min-height: 100px;
      transition: transform 0.2s, box-shadow 0.3s;
    }

    .button:active {
      transform: scale(0.7);
    }

    .button:hover {
      transform: scale(1.1);
      box-shadow: 0 0 10px var(--accent);
    }

    .loading::after {
      content: " \231B";
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { content: " \231B"; }
      25% { content: " \1F504"; }
      50% { content: " \23F1"; }
      75% { content: " \231B"; }
      100% { content: " \231B"; }
    }

    .status-message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 16px;
      color: red;
    }

    .back-button-container {
      position: absolute;
      bottom: 10px;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: center;
      z-index: 1000;
    }
  </style>
</head>
<body>

<div class="main-content">
  <div class="status-message">
    <?php
      if (isset($_POST['button96'])) {
          echo "<p>PTT LED is Activated</p>";
      }
      if (isset($_POST['button95'])) {
          echo "<p>Encoder is Activated</p>";
      }
      if (isset($_POST['button94'])) {
          echo "<p>PTT LED is Deactivated</p>";
      }
      if (isset($_POST['button93'])) {
          echo "<p>Encoder is Deactivated</p>";
      }
    ?>
  </div>

  <form method="post" onsubmit="return showLoading(event)">
    <div class="button-row">
      <button class="button" name="button96" id="btn96">PTT LED<br>ON</button>
      <button class="button" name="button94" id="btn94">PTT LED<br>OFF</button>
    </div>
    <div class="button-row">
      <button class="button" name="button95" id="btn95">Rotary Encoder<br>ON</button>
      <button class="button" name="button93" id="btn93">Rotary Encoder<br>OFF</button>
    </div>
  </form>
</div>

<div class="back-button-container">
  <?php
    include_once 'include/back_button.php';
  ?>
</div>

<script>
  function showLoading(event) {
    const btn = event.submitter;
    btn.classList.add('loading');
    return true;
  }
</script>

</body>
</html>
