<?php

$error = null;
$tg = null;
$tgFormatted = null;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $error = "Nur POST erlaubt.";
} else {
    // DTMF-Ausführung prüfen
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

    // TG setzen, wenn kein DTMF-Button verarbeitet wurde
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
    <style>
        body {
            background-color: black;
            color: white;
            font-size: 36px;
            text-align: center;
            padding-top: 100px;
            font-family: sans-serif;
        }
    </style>
</head>
<body>
    <?php if ($error): ?>
        <?php echo htmlspecialchars($error); ?><br>
    <?php else: ?>
        TG <?php echo htmlspecialchars($tgFormatted); ?> gesetzt.<br>
    <?php endif; ?>
</body>
</html>
