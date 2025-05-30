<?php
// --- Konfiguration ---
// Passen Sie diesen Wert an, um die Schriftgröße des "Zurück zur Auswahl"-Links zu ändern.
// Gültige CSS-Werte sind z.B. "1.2em", "16px", "large", "120%", "1.5rem" etc.
define("ZURUECK_LINK_FONT_SIZE", "36px"); // HIER DIE SCHRIFTGRÖSSE ANPASSEN

// --- Key Definitions ---
define("KEY30", ["Taste 30", "DTMF30", ""]);
define("KEY31", ["Taste 31", "DTMF31", ""]);
define("KEY32", ["Taste 32", "DTMF32", ""]);
define("KEY33", ["Taste 33", "DTMF33", ""]);
define("KEY34", ["Taste 34", "DTMF34", ""]);
define("KEY35", ["Taste 35", "DTMF35", ""]);
define("KEY36", ["Taste 36", "DTMF36", ""]);
define("KEY37", ["Taste 37", "DTMF37", ""]);
// KEY38 is skipped
define("KEY39", ["Taste 39", "DTMF39", ""]);
// KEY40 is skipped
define("KEY41", ["Taste 41", "DTMF41", ""]);
define("KEY42", ["Taste 42", "DTMF42", ""]);
define("KEY43", ["Taste 43", "DTMF43", ""]);

// --- State Variables ---
$activeButton = null;
$activeKeyLabel = null;
$feedbackMessage = null;
$isError = false;

// --- Request Processing ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buttonProcessed = false;
    foreach ($_POST as $key => $val) {
        if (preg_match('/^button(\d+)$/', $key, $matches)) {
            $btnNum = $matches[1];
            $constName = "KEY" . $btnNum;

            if (defined($constName)) {
                $keyData = constant($constName);
                $dtmf = $keyData[1];

                if (file_put_contents("/tmp/dtmf_svx", $dtmf . PHP_EOL) !== false) {
                    $activeButton = $btnNum;
                    $activeKeyLabel = $keyData[0];
                    $feedbackMessage = "Taste '" . htmlspecialchars($activeKeyLabel) . "' wurde erfolgreich gesendet.";
                } else {
                    $feedbackMessage = "Fehler: DTMF-Code konnte nicht in /tmp/dtmf_svx gespeichert werden. Bitte Berechtigungen prüfen.";
                    $isError = true;
                }
            } else {
                $feedbackMessage = "Fehler: Taste '{$btnNum}' ist nicht definiert.";
                $isError = true;
            }
            $buttonProcessed = true;
            break;
        }
    }
    if (!$buttonProcessed && !empty($_POST)) {
        $feedbackMessage = "Fehler: Ungültige Anfrage.";
        $isError = true;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Change TG</title>
    <link rel="stylesheet" href="css/button_style_svx.css">
    <?php if ($activeButton !== null && !$isError): ?>
        <meta http-equiv="refresh" content="3;url=index.php">
    <?php endif; ?>
    <style>
        body {
            display: flex;
            justify-content: center;
            padding-top: 20px;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .center-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .button-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        .feedback-message {
            margin-top: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }
        .feedback-message.success { color: green; }
        .feedback-message.error   { color: red; }

        .nav-link {
            margin-top: 15px;
        }
        .nav-link a {
            font-size: <?php echo ZURUECK_LINK_FONT_SIZE; ?>; /* Dynamische Schriftgröße */
            text-decoration: none;
            color: #007bff;
        }
        .nav-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <?php if ($activeButton !== null || $isError): ?>
            <div class="center-container">
                <?php if ($activeButton !== null && !$isError): ?>
                    <?php
                        $activeButtonClasses = "touch-button touch-button" . (($activeButton % 10) + 1) . " centered-button";
                    ?>
                    <button type="button" class="<?= $activeButtonClasses ?>" disabled>
                        <?= htmlspecialchars($activeKeyLabel) ?>
                    </button>
                <?php endif; ?>

                <?php if ($feedbackMessage): ?>
                    <p class="feedback-message <?= $isError ? 'error' : 'success' ?>">
                        <?= $feedbackMessage ?>
                    </p>
                <?php endif; ?>
                
                <p class="nav-link">
                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Zurück zur Auswahl</a>
                </p>
            </div>
        <?php else: ?>
            <div class="button-grid">
                <?php
                $key_numbers_to_display = array_merge(range(30, 37), [39], range(41, 43));

                foreach ($key_numbers_to_display as $i) {
                    $constName = "KEY" . $i;
                    if (defined($constName)) {
                        $keyData = constant($constName);
                        $label = htmlspecialchars($keyData[0]);
                        $button_class_index = ($i % 10) + 1;
                        $gridButtonClasses = "touch-button touch-button{$button_class_index} button-wide";
                        echo "<button type='submit' class='{$gridButtonClasses}' name='button{$i}'>{$label}</button>";
                    }
                }
                ?>
            </div>
        <?php endif; ?>
    </form>
</body>
</html>