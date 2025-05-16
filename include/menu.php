<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: sans-serif;
    }

    .button-row {
      display: flex;
      gap: 6px; /* Abstand zwischen Buttons */
    }

    .purple, .red, .blue, .green, .yellow {
      height: 65px;
      width: 180px;
      font-size: 36px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.1s ease, box-shadow 0.2s ease;
    }

    .purple {
      background: linear-gradient(to bottom,rgb(205, 202, 252),rgb(94, 75, 240));
      color: black;
    }

    .red {
      background: linear-gradient(to bottom,rgb(252, 233, 233),rgb(255, 0, 0));
      color: black;
    }

    .blue {
      background: linear-gradient(to bottom,rgb(240, 240, 240),rgb(2, 168, 146));
      color: black;
    }

    .green {
      background: linear-gradient(to bottom,rgb(255, 255, 255),rgb(0, 255, 34));
      color: black;
    }

    .yellow {
      background: linear-gradient(to bottom,rgb(251, 255, 0),rgb(245, 78, 0));
      color: black;
    }

    .purple:hover, .red:hover, .blue:hover, .green:hover, .yellow:hover {
      transform: scale(1.03);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    a {
      text-decoration: none;
    }
  </style>
</head>
<body>

<div class="button-row">
  <a href="dtmf.php"><button class="blue" name="button0">DTMF</button></a>
  <!-- <a href="tg.php"><button class="purple" name="button0">MEMO</button></a> -->
  <a href="test.php"><button class="red" name="button0">SWITCH</button></a> 
  <!-- <a href="stream_deck.php"><button class="purple" name="button0">DECK</button></a> -->

  <a href="svx.php"><button class="yellow" name="button0">SvxLink</button></a>
  <a href="config.php"><button class="green" name="button0">Config</button></a>
  <!-- Beispiel für neue Farben -->
  <!-- <a href="#"><button class="blue" name="button0">INFO</button></a> -->
  <!-- <a href="#"><button class="green" name="button0">START</button></a> -->
  <!-- <a href="#"><button class="yellow" name="button0">WARN</button></a> -->
</div>

</body>
</html>
