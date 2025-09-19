<!DOCTYPE html>
<!--  -->
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="refresh" content="5;url=index.php">

  <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $debugLogFile = __DIR__ . '/debug_script.log';
    function script_log($message) {
        global $debugLogFile;
        $timestamp = date("Y-m-d H:i:s");
        file_put_contents($debugLogFile, "[$timestamp] " . $message . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    // script_log("--- Skript gestartet (Seitenaufruf) ---");

    $fmnet = "N/A";
    $includeFile = __DIR__ . '/include/get_fmnet.php';
    if (file_exists($includeFile)) {
        include_once $includeFile;
        if (function_exists('getFMNetName')) {
            $fmnet = getFMNetName();
            // script_log("getFMNetName() initial aufgerufen. FMNet: " . $fmnet);
        } else {
            $fmnet = "FMNet Funktion nicht gefunden!";
            // script_log("FEHLER: Funktion getFMNetName() nicht gefunden nach Include von $includeFile.");
        }
    } else {
        $fmnet = "Include-Datei nicht gefunden!";
        // script_log("FEHLER: Include-Datei $includeFile nicht gefunden.");
    }

    $feedback = '';
    $feedbackClass = 'success';
    $reflectorFile = __DIR__ . '/letzter_reflektor.txt';
    $contentForReflectorFile = ""; // Inhalt für die Datei letzter_reflektor.txt

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // script_log("POST-Request empfangen. POST-Daten: " . print_r($_POST, true));
        $actionTaken = false;

        // Konfiguration der Reflektor-Aktionen
        // Key ist der POST-Parameter (Name des Buttons)
        $reflectorActionsConfig = [
            'Reflector_6' => ['script' => 'reflector6.sh', 'number' => '6', 'log_msg_action' => 'Aktion: Reflector_6'],
            'Reflector_1' => ['script' => 'reflector1.sh', 'number' => '1', 'log_msg_action' => 'Aktion: Reflector_1'],
            'Reflector_2' => ['script' => 'reflector2.sh', 'number' => '2', 'log_msg_action' => 'Aktion: Reflector_2'],
            'Reflector_3' => ['script' => 'reflector3.sh', 'number' => '3', 'log_msg_action' => 'Aktion: Reflector_3'],
            'Reflector_4' => ['script' => 'reflector4.sh', 'number' => '4', 'log_msg_action' => 'Aktion: Reflector_4'],
            'Reflector_5' => ['script' => 'reflector5.sh', 'number' => '5', 'log_msg_action' => 'Aktion: Reflector_5'],
        ];

        // Bestimme, welcher Button gedrückt wurde
        $pressedButtonName = null;
        foreach (array_keys($_POST) as $postKey) {
            if (strpos($postKey, 'Reflector_') === 0 || $postKey === 'licht_an') {
                $pressedButtonName = $postKey;
                break;
            }
        }

        if ($pressedButtonName === 'licht_an') {
            ;
        } elseif ($pressedButtonName && isset($reflectorActionsConfig[$pressedButtonName])) 
        {
            $config = $reflectorActionsConfig[$pressedButtonName];
            $actionTaken = true;
            $command = 'sudo /home/pi/' . $config['script'] . ' 2>&1';
            $output = shell_exec($command);

            if (function_exists('getFMNetName')) { // FMNet nach Umschaltung neu abrufen
                $fmnet = getFMNetName();
            }

            $feedback = "switch to " . $config['number'] . " " . $fmnet;
            $contentForReflectorFile = $config['number'];
        } elseif (!empty($_POST)) 
        { // Fall für unbekannte Aktionen, wenn POST nicht leer ist
            $feedback = "Unbekannte POST-Aktion empfangen.";
            $feedbackClass = 'error';
            $contentForReflectorFile = "UNKNOWN_ACTION";
        }

        // Schreiben in letzter_reflektor.txt, wenn ein Inhalt dafür vorgesehen ist
        if ($contentForReflectorFile !== "") {
            if (file_put_contents($reflectorFile, $contentForReflectorFile . PHP_EOL, LOCK_EX) === false) {
                $errorDetails = error_get_last();
                $fileWriteError = $errorDetails ? $errorDetails['message'] : 'Unbekannter Fehler';
            }
        }
    } // end if POST

    // Meta-Refresh nur, wenn kein Feedback angezeigt wird (d.h. kein POST-Request mit Aktion)
    if (empty($feedback)) {
        echo '<meta http-equiv="refresh" content="60;url=index.php" />';
    }
  ?>
  <title>SSvxDSI SWITCH</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
      width: 800px; height: 480px; background-color: #121212;
      overflow: hidden; font-family: 'Roboto', sans-serif;
    }
    .grid {
      display: grid; grid-template-columns: repeat(2, 1fr);
      gap: 15px; padding: 20px;
    }
    .tile {
      background: linear-gradient(145deg,rgb(122, 121, 121), #1a1a1a);
      border: none; border-radius: 16px;
      font-size: 36px; /* vorher 19px */
      padding: 15px; height: 114px; color: #f1f1f1;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
      transition: transform 0.1s ease-in-out, background 0.3s;
      touch-action: manipulation;
      display: flex; flex-direction: column; justify-content: center; align-items: center;
      user-select: none;
    }
    .tile:hover { background: linear-gradient(145deg, #3a3a3a, #2a2a2a); }
    .tile:active { transform: scale(0.97); background: linear-gradient(145deg, #444, #2b2b2b); }
    .tile i { font-size: 34px; margin-bottom: 6px; } /* vorher 30px */

    .feedback {
      position: fixed; width: 800px; height: 480px; top: 0; left: 0;
      display: flex; justify-content: center; align-items: center;
      font-size: 42px; padding: 20px; border-radius: 12px;
      box-shadow: 0 4px 12px rgba(109, 106, 106, 0.6);
      background-color: rgb(0, 0, 0); text-align: center; color: #fff;
      word-wrap: break-word; z-index: 1000;
    }
    .feedback.success { color: rgb(168, 175, 76); }
    .feedback.error { color: #f44336; }
    .hidden { display: none !important; }

    /* Farben wie zuvor */
    .tetra{background:linear-gradient(145deg,#1e88e5,#1565c0)!important;color:#fff;}
    .kremlink{background:linear-gradient(145deg,#e53935,#b71c1c)!important;color:#fff;}
    .rolink{background:linear-gradient(145deg,#8e24aa,#6a1b9a)!important;color:#fff;}
    .fmfunknetz{background:linear-gradient(145deg,#43a047,#2e7d32)!important;color:#fff;}
    .funknetz-celle{background:linear-gradient(145deg,#f9a825,#f57f17)!important;color:#111;text-shadow:none;}
    .misu4{background:linear-gradient(145deg,#00acc1,#00838f)!important;color:#fff;}
  </style>
</head>
<body>

  <?php
    // Feedback-Anzeige und JavaScript-Weiterleitung, falls Feedback vorhanden ist
    if (!empty($feedback)) { // Gilt nur wenn ein POST eine Aktion ausgelöst hat
        echo '<div class="feedback ' . $feedbackClass . '">' . htmlspecialchars($feedback) . '</div>';
        echo '<script>
          window.addEventListener("DOMContentLoaded", () => {
            console.log("Feedback angezeigt. Weiterleitung in 3 Sek.");
            setTimeout(() => {
              window.location.href = "index.php";
            }, 3000);
          });
        </script>';
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($feedback)) {
        echo '<div class="feedback error">Aktion verarbeitet, aber kein Feedback generiert.</div>';
        echo '<script>
          window.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => { window.location.href = "index.php"; }, 3000);
          });
        </script>';
    }
  ?>

  <form method="post">
    <main class="grid <?php if (!empty($feedback) && $_SERVER['REQUEST_METHOD'] === 'POST') echo 'hidden'; ?>">
      <button class="tile tetra" type="submit" name="Reflector_6">
        <i class="material-icons-outlined">settings</i>
        Reflector TETRA
      </button>

      <button class="tile kremlink" type="submit" name="Reflector_1">
        <i class="material-icons-outlined">settings</i>
        KREMLINK 
      </button>

      <button class="tile rolink" type="submit" name="Reflector_2">
        <i class="material-icons-outlined">settings</i>
        ROLINK
      </button>

      <button class="tile fmfunknetz" type="submit" name="Reflector_3">
        <i class="material-icons-outlined">settings</i>
        FM FUNKNETZ
      </button>

      <button class="tile funknetz-celle" type="submit" name="Reflector_4">
        <i class="material-icons-outlined">settings</i>
        FUNKNETZ CELLE
      </button>

      <button class="tile misu4" type="submit" name="Reflector_5">
        <i class="material-icons-outlined">sync</i>
        MISU 4
      </button>
    </main>
  </form>
</body>
</html>
