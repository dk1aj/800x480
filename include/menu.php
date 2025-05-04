<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: sans-serif;
    }

    .button-row {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 20vh;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      height: 65px;
      width: 180px;
      font-size: 32px;
      font-weight: bold;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      transition: transform 0.1s ease, box-shadow 0.2s ease;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-purple {
      background-color: #e0d8ff; /* Helles Lila */
      color: #2d0c57;             /* Dunkles Violett für Text */
    }

    .btn-red {
      background-color: #c0392b; /* Sattes Rot */
      color: #ffffff;            /* Weißer Text für guten Kontrast */
    }

    .btn:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <div class="button-row">
    <!-- <a href="dtmf.php" class="btn btn-purple" aria-label="Open DTMF page">DTMF</a> -->
    <a href="test.php" class="btn btn-purple" aria-label="Open DTMF page">DTMF</a>
    <a href="tg.php" class="btn btn-purple" aria-label="Open MEMO page">MEMO</a>
    <a href="svx.php" class="btn btn-purple" aria-label="Open SvxLink page">SvxLink</a>
    <a href="config.php" class="btn btn-red" aria-label="Open Config page">Config</a>
  </div>

</body>
</html>
