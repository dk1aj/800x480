<?php
// 2025-05-17 21:22:16
include_once __DIR__ . '/include/tgdb.dat';         // Talkgroup-Datenbank (TG-Nummer => Name)
include_once __DIR__ . '/include/userdb.dat';       // Benutzerdatenbank (Callsign => Name, QTH, Info)


// Funktionen definieren
function getTGNameByNumber($tgNumber): string {
    global $tgdb_array;

    if (!is_array($tgdb_array)) {
        return 'Unbekannt';
    }

    $tgKey = (string)$tgNumber;
    return $tgdb_array[$tgKey] ?? 'Unbekannt';
}

function getFMNetName(string $confFile = '/etc/svxlink/svxlink.conf'): string {
    if (!file_exists($confFile)) {
        return 'Netz Unbekannt';
    }

    $config = parse_ini_file($confFile, true, INI_SCANNER_RAW);
    return $config['ReflectorLogic']['FMNET'] ?? 'Netz Unbekannt';
}

// Hauptlogik
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
<html lang="en">
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
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100%;
    }
    .highlight {
      color: yellow;
    }
    .netname {
      color: cyan;
      font-size: 32px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h1>SvxDSI</h1>
  <div class="message">
    <?php if ($error): ?>
      <?php echo htmlspecialchars($error); ?><br>
    <?php else: ?>
      <!-- TG# <span class="highlight"><?php echo htmlspecialchars($tg); ?></span>
      (<?php echo htmlspecialchars(getTGNameByNumber($tg)); ?>)<br> -->
      <span class="highlight">TG# <?php echo htmlspecialchars($tg); ?> (<?php echo htmlspecialchars(getTGNameByNumber($tg)); ?>)</span><br>
      <div class="netname">Netzwerk: <?php echo htmlspecialchars(getFMNetName()); ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
