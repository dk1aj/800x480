<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-RXf+QSDCUQs6Q0z6f13Qa9+v5XLuKDxloAg8CVy6GDaMZB2RT0rRQzFz3+/WzazHlXK7RzW4QDE9J/cd1w8uUg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: sans-serif;
      background-color: #111;
      color: #fff;
    }

    .button-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 6px;
      box-sizing: border-box;
    }

    .button-row a {
      flex: 1;
      min-width: 160px;
      max-width: 160px;
      height: 60px;
      font-size: 24px;
      font-weight: bold;
      font-family: inherit;
      line-height: 1;
      text-align: center;
      user-select: none;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: transform 0.1s ease, box-shadow 0.2s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      margin: 0 6px;
      text-decoration: none;
      color: #000;
      padding: 0;
    }

    .button-row a:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .button-row a:active {
      transform: scale(0.97);
      box-shadow: 0 2px 2px rgba(0, 0, 0, 0.4);
    }

    .button-row a i {
      font-size: 28px;
      color: #333;
    }

    .blue {
      background: linear-gradient(to bottom, #e0f7fa,rgb(0, 51, 128));
    }

    .red {
      background: linear-gradient(to bottom, #fce9e9, #cc0000);
    }

    .yellow {
      background: linear-gradient(to bottom, #ffff00, #cc3300);
    }

    .green {
      background: linear-gradient(to bottom, #ffffff, #00cc22);
    }
  </style>
</head>
<body>

  <div class="button-row">
    <a href="dtmf.php" class="blue"><i class="fas fa-keyboard"></i> DTMF</a>
    <a href="switch.php" class="red"><i class="fas fa-toggle-on"></i> SWITCH</a>
    <a href="svx.php" class="yellow"><i class="fas fa-microphone"></i> SvxLink</a>
    <a href="config.php" class="green"><i class="fas fa-cog"></i> Config</a>
  </div>

</body>
</html>
