<?php
$error = null;
$tg = null;
$tgFormatted = null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $error = "Nur POST erlaubt.";
} else {
    foreach ($_POST as $key => $val) {
        if (preg_match('/^button(\d+)$/', $key, $matches)) {
            $btnNum = $matches[1];
            $constName = 'KEY' . $btnNum;

            if (defined($constName)) {
                $cmd = constant($constName)[1];
                if (!empty($cmd)) {
                    $dtmfCmd = "*91" . $cmd . "#";

                    if ($btnNum == 8) {
                        exec($dtmfCmd, $output);
                    } else {
                        exec("echo '$dtmfCmd' > /tmp/dtmf_svx", $output);
                    }
                }
            }

            echo "<meta http-equiv='refresh' content='0'>";
            exit;
        }
    }

    if (!isset($_POST['tg']) || !preg_match('/^(\d+)\+*$/', $_POST['tg'], $matches)) {
        $error = "Ungültige TG.";
    } else {
        $tg = (int)$matches[1];
        $tgFormatted = "*91" . $tg . "#";

        $tgFile = '/tmp/svx_tg_select';
        $dtmfFile = '/tmp/dtmf_svx';

        if (
            file_put_contents($tgFile, $tgFormatted . PHP_EOL, LOCK_EX) === false ||
            file_put_contents($dtmfFile, $tgFormatted . PHP_EOL, LOCK_EX) === false
        ) {
            $error = "Fehler beim Speichern.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="3;url=index.php">
  <title><?php echo $error ? 'Fehler' : 'TG gesetzt'; ?></title>
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <style>
    html, body {
      margin: 0;
      padding: 0;
      width: 800px;
      height: 480px;
      background-color: black;
      color: white;
      font-size: 36px;
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      overflow: hidden;
    }
    h1 {
        font-size: 64px;
        margin: 0;
        padding-top: 10px;
        color: #003366; /* Dunkelblau */
    }
    .message {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
    }
  </style>
</head>
<body>
  <h1>Pixel Vista 25</h1>
  <div class="message">
    <?php if ($error): ?>
      <?php echo htmlspecialchars($error); ?><br>
    <?php else: ?>
      TG# <?php echo htmlspecialchars($tg); ?> gesetzt.<br>
    <?php endif; ?>
  </div>
</body>
</html>
