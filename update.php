<?php
  define('ALLOW_LOGIC_CHANGES', false);
  $backTarget = 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="author" content="DK1AJ" />
  <meta http-equiv="refresh" content="5;url=index.php" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
 <title>SvxDSI</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      background-color: #000;
      font: 11pt arial, sans-serif;
      height: 480px;
      width: 800px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: space-between;
      overflow: hidden;
      position: relative;
    }

    .container {
      max-width: 790px;
      text-align: center;
      margin-top: 10px;
    }

    .header-box {
      color: white;
      font-size: 24px;
      font-weight: bold;
      border: 2px solid white;
      padding: 10px 20px;
      margin-top: 10px;
      margin-bottom: 10px;
      border-radius: 10px;
    }

    .button-row {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
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
      background: linear-gradient(to bottom, rebeccapurple, indigo);
    }

    button:active {
      transform: scale(0.96);
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5) inset;
    }

    .back-button-container {
      margin-bottom: 10px;
      width: 280px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="header-box">Add databank entries</div>
  <div class="container">
    <div class="button-row">
      <a href="./add_user_record.php">
        <button name="button0">Add USER<br>record</button>
      </a>
      <a href="./add_tg_record.php">
        <button name="button0">Add TG<br>record</button>
      </a>
    </div>
  </div>

  <div class="back-button-container">
    <?php include_once 'include/back_button.php'; ?>
  </div>
</body>
</html>
