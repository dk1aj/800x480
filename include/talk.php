<?php
// 2025-05-30 09:25:54 Sicherheitskonstante setzen, um Logikänderungen zu unterbinden
define('ALLOW_LOGIC_CHANGES', false);


// Notwendige Konfigurations- und Datenquellen einbinden
include_once __DIR__ . '/config.php'; // Stelle sicher, dass CALLSIGN_HISTORY_POSITION hier definiert ist oder wird
include_once __DIR__ . '/tools.php';
include_once __DIR__ . '/functions.php';

@include_once __DIR__ . '/tgdb.dat';
@include_once __DIR__ . '/userdb.dat';

if (!isset($tgdb_array)) {
    $tgdb_array = [];
}
if (!isset($userdb_array)) {
    $userdb_array = [];
}

// --- Vorbereitung für dynamische CSS-Stile für #callsign-history ---
$callsignHistoryCSS = "";
$callsignHistoryDisplay = true; // Standardmäßig anzeigen

// Standardkonfiguration, falls in config.php nicht gesetzt (obwohl es dort sein sollte)
$currentCallsignHistoryPosition = defined('CALLSIGN_HISTORY_POSITION') ? CALLSIGN_HISTORY_POSITION : 'bottom-center';

switch ($currentCallsignHistoryPosition) {
    case 'top-center':
        $callsignHistoryCSS = "
            position: absolute;
            top: 65px; /* Platz für TG-Buttons lassen, falls angezeigt */
            left: 50%;
            transform: translateX(-50%);
            color: #ccc;
            font-size: 30px;
            text-align: center;
            width: 90%;
            max-width: 780px;
            z-index: 5;
            line-height: 1.3;
        ";
        break;
    case 'none':
        $callsignHistoryCSS = "display: none;";
        $callsignHistoryDisplay = false;
        break;
    case 'bottom-center':
        // Dies war der ursprüngliche Stil
        $callsignHistoryCSS = "
            color: #ccc;
            font-size: 30px;
            text-align: center;
            position: absolute;
            bottom: 110px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 780px;
            z-index: 5;
            line-height: 1.3;
        ";
        break;
    default:
        // Annahme: $currentCallsignHistoryPosition ist ein benutzerdefinierter CSS-String
        // Der Benutzer ist für alle Eigenschaften verantwortlich, einschließlich 'position', falls erforderlich.
        if (!empty(trim($currentCallsignHistoryPosition))) {
            $callsignHistoryCSS = $currentCallsignHistoryPosition;
        } else {
            // Fallback, falls der benutzerdefinierte String leer ist, um Fehler zu vermeiden
            $callsignHistoryCSS = "display: none; /* Benutzerdefinierter CSS-String war leer */";
            $callsignHistoryDisplay = false;
        }
        break;
}


// --- CSS Styles ---
echo <<<CSS
<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #333;
    color: white;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
}

.tg-button-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    padding: 10px;
    width: 100%;
    max-width: 780px;
    box-sizing: border-box;
    margin-top: 1px;
    margin-bottom: 5px;
}

.tg-button-form {
    margin: 0;
}

.tg-button {
    min-width: 60px;
    height: 40px;
    font-size: 36px;
    background-color:rgb(112, 112, 112);
    color: #ffff00;
    border: none;
    border-radius: 18px;
    cursor: pointer;
    padding: 0 10px;
    line-height: 40px;
    box-sizing: border-box;
}

.tg-button.active {
    background-color: #222;
    color: #ff4444;
}

.error-message {
    color: red;
    font-size: 48px;
    text-align: center;
    margin-top: 20px;
}

.status-display-base {
    display: inline-block;
    width: 740px;
    height: 300px;
    padding: 2px;
    border-radius: 12px;
    margin: 3px 5px;
    text-align: center;
    font-weight: 700;
    font-size: 1.5rem;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #ffffff;
    touch-action: manipulation;
    box-sizing: border-box;
    margin-top: 20px;
    /* Optional: Flexbox für bessere vertikale Zentrierung des Inhalts */
    /* display: flex; */
    /* flex-direction: column; */
    /* justify-content: center; */
    /* align-items: center; */
}

.node-talk {
  border: 10px solid #08a103;
  background: linear-gradient(to bottom,rgb(0, 0, 0),rgb(59, 59, 59));
  box-shadow: 0 4px 10px rgba(47, 247, 74, 0.65);
}

.node-talk1 {
  border: 10px solid #ff5733 ;
  background: linear-gradient(to bottom,rgb(0, 0, 0),rgb(136, 2, 2));
  box-shadow: 0 4px 10px rgba(255, 87, 51, 0.65);
}

.callsign-large { color: white; vertical-align: text-top; font-size: 2.5em; line-height: 1.2; }
.tg-number-medium { color: yellow; font-size: 1.5em; line-height: 1.2;}
.tg-name-large-red { color: red; font-size: 2em; line-height: 1.2;}

.user-info-panel {
    position: absolute;
    top: calc(50% + 110px); /* Etwas tiefer für "ON AIR" Indikator */
    left: 50%;
    transform: translate(-50%, 0);
    font-size: 16px;
    line-height: 1.5;
    text-align: left;
    color: white;
    background-color: rgba(0,0,0,0.5);
    padding: 10px;
    border-radius: 8px;
    min-width: 300px;
    max-width: 90%;
    z-index: 10;
    box-sizing: border-box;
}
.user-info-text { margin-bottom: 5px; }
.user-info-label { font-weight: bold; }
.user-info-value { color: cyan; }

.fallback-user-info {
    position: absolute;
    top: calc(50% + 110px); /* Etwas tiefer für "ON AIR" Indikator */
    left: 50%;
    transform: translate(-50%, 0);
    font-size: 20px;
    color: grey;
    text-align: center;
    z-index: 10;
    background-color: rgba(0,0,0,0.3);
    padding: 8px;
    border-radius: 5px;
}

.on-air-indicator { /* NEUE KLASSE */
    position: absolute;
    top: 220px; /* Angepasst, um unter dem Status-Block zu sein */
    left: 50%;
    transform: translate(-50%, 0);
    font-size: 50px;
    font-weight: bold;
    color:rgb(255, 255, 255);
    text-shadow: 0 0 5px #ffffff, 0 0 10px #ff0000;
    text-align: center;
    z-index: 10;
    padding: 10px 20px;
    background-color: rgba(0,0,0,0.7);
    border-radius: 10px;
    border: 2px solid #ff0000;
}


.idle-display-container {
    width: 100%;
    max-width: 800px;
    text-align: center;
    color: white;
    padding: 20px;
    box-sizing: border-box;
    margin-top: 20px;
}

.idle-time { font-size: 50px; margin-bottom: 15px; }
.idle-info-line { margin-bottom: 10px; }
.idle-last-talker { color: limegreen; font-size: 38px; }
.idle-active-tg-number { color: lightblue; font-size: 38px; }
.idle-active-tg-name { color: white; font-size: 38px; }
.idle-fm-net {
    color: cyan;
    font-size: 48px;
    margin-top: 10px;
}

/* Dynamisch generierte Stile für #callsign-history */
#callsign-history {
    {$callsignHistoryCSS}
}

.large-clock {
    font-size: 150px;
    line-height: 1;
    color: white;
    text-align: center;
    position: absolute;
    top: 45%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    z-index: 5;
}

.large-net-name {
    font-size: 100px;
    color: cyan;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: absolute;
    bottom: 90px;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 780px;
    z-index: 5;
}

</style>
CSS;
// --- Ende CSS Styles ---

function renderTgButtons(array $tgmon, $tgselect_str): void
{
    if (!empty($tgmon)) {
        echo '<div class="tg-button-container">';
        $tgselect_int = (int)$tgselect_str;

        foreach ($tgmon as $tg) {
            $tg = trim($tg);
            $isActive = ((int)$tg === $tgselect_int);
            $buttonClass = 'tg-button' . ($isActive ? ' active' : '');

            echo '<form method="post" action="set_tg.php" class="tg-button-form">';
            echo '<input type="hidden" name="tg" value="' . htmlspecialchars($tg, ENT_QUOTES, 'UTF-8') . '">';
            echo '<button type="submit" class="' . $buttonClass . '">';
            echo htmlspecialchars($tg, ENT_QUOTES, 'UTF-8');
            echo '</button>';
            echo '</form>';
        }
        echo '</div>';
    } else {
        echo '<span class="error-message">No TG (monitored)</span>';
    }
}

function getCurrentTimeLocalized() {
    $pattern = 'EEEE, HH:mm:ss';
    setlocale(LC_TIME, 'de_DE.utf8', 'de_DE.UTF-8', 'de_DE', 'german');
    if (class_exists('IntlDateFormatter')) {
        $formatter = new IntlDateFormatter(
            'de_DE.UTF-8',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            $pattern
        );
        if ($formatter && !intl_is_failure($formatter->getErrorCode())) {
            return $formatter->format(new DateTime());
        }
    }
    return strftime('%H:%M:%S');
}

$logFilePath = '/var/log/svxlink';
date_default_timezone_set('Europe/Berlin');

$lastCallsign = null;
$lastTGNumber = null;
$transmitting = false;
$receiving = false;
$lastKnownCallsign = null;
$displayCssClass = 'status-display-base';

$lastCallsignHistory = [];

$file = @fopen($logFilePath, 'r');
if ($file) {
    $lines = [];
    while (($line = fgets($file)) !== false) {
        $lines[] = $line;
    }
    fclose($file);

    foreach ($lines as $line) {
        if (strpos($line, 'ReflectorLogic: Talker start') !== false &&
            preg_match('/Talker start on TG #(\d+): (\S+)/', $line, $matches)) {
            $lastTGNumber = $matches[1];
            $lastCallsign = $matches[2];
            $lastKnownCallsign = $lastCallsign;
            if (!in_array($lastCallsign, $lastCallsignHistory)) {
                 array_unshift($lastCallsignHistory, $lastCallsign);
            }
        }
        if (strpos($line, 'ReflectorLogic: Talker stop') !== false &&
            preg_match('/Talker stop on TG #(\d+): (\S+)/', $line, $matches)) {
            if ($matches[2] === $lastCallsign) {
                $lastCallsign = null;
                $lastTGNumber = null;
            }
        }
        if (strpos($line, 'Tx1: Turning the transmitter ON') !== false) {
            $transmitting = true; $receiving = false;
        }
        if (strpos($line, 'Tx1: Turning the transmitter OFF') !== false) {
            $transmitting = false;
        }
        if (strpos($line, 'Rx1: The squelch is OPEN') !== false) {
            if (!$transmitting) { $receiving = true; }
        }
        if (strpos($line, 'Rx1: The squelch is CLOSED') !== false) {
            $receiving = false;
        }
    }
    $lastCallsignHistory = array_slice(array_values(array_unique($lastCallsignHistory)), 0, 5);

    $svxConfigFile = '/etc/svxlink/svxlink.conf';
    $svxconfig = @parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);

    if (!$svxconfig) {
        echo "<div class='error-message'>Error opening SVXLink configuration file.</div>\n";
    }

    $tgmon = isset($svxconfig['ReflectorLogic']['MONITOR_TGS']) ? explode(",", $svxconfig['ReflectorLogic']['MONITOR_TGS']) : [];
    $fmnet = $svxconfig['ReflectorLogic']['FMNET'] ?? 'UnknownNet';

    $specificStateClass = '';
    if ($transmitting) {
        $specificStateClass = 'node-talk';
    } elseif ($receiving && $lastCallsign !== null) {
        $specificStateClass = 'node-talk1';
    }
    $displayCssClass = 'status-display-base ' . $specificStateClass;

    $tgselect_str = function_exists('getSVXTGSelect') ? trim(getSVXTGSelect()) : "0";

    if ($lastCallsign !== null && $lastTGNumber !== null) {
        $actualTalkerTgName = $tgdb_array[$lastTGNumber] ?? "TG $lastTGNumber";

        echo "<div class=\"$displayCssClass\">";
        echo "  <div>";
        echo "    <span class=\"callsign-large\">" . htmlspecialchars($lastCallsign, ENT_QUOTES, 'UTF-8') . "</span></br>";
        echo "    <span class=\"tg-number-medium\">TG# " . htmlspecialchars($lastTGNumber, ENT_QUOTES, 'UTF-8') . "</span>&nbsp;&nbsp;";
        echo "    <span class=\"tg-name-large-red\">" . htmlspecialchars($actualTalkerTgName, ENT_QUOTES, 'UTF-8') . "</span>";
        echo "  </div>";
        echo "</div>"; // Ende status-display-base

        if (!$transmitting) {
            echo '<div class="on-air-indicator">ON AIR</div>';
        } else {
            $userInfoString = $userdb_array[$lastCallsign] ?? '';
            $userDetails = !empty($userInfoString) ? explode(',', $userInfoString, 3) : [];
            $userName = trim($userDetails[1] ?? '');
            $userQTH  = trim($userDetails[2] ?? '');
            $userInfo = trim($userDetails[0] ?? '');

            /* if (!empty($userName) || !empty($userQTH) || !empty($userInfo)) {
                echo "<div class=\"user-info-panel\">";
                if (!empty($userName)) echo "<div class=\"user-info-text\"><span class=\"user-info-label\">Name: </span><span class=\"user-info-value\">" . htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') . "</span></div>";
                if (!empty($userQTH))  echo "<div class=\"user-info-text\"><span class=\"user-info-label\">QTH: </span><span class=\"user-info-value\">" . htmlspecialchars($userQTH, ENT_QUOTES, 'UTF-8') . "</span></div>";
                if (!empty($userInfo)) echo "<div><span class=\"user-info-label\">Info: </span><span class=\"user-info-value\">" . htmlspecialchars($userInfo, ENT_QUOTES, 'UTF-8') . "</span></div>";
                echo "</div>";
            } else {
                echo '<div class="fallback-user-info">User not found in database</div>';
            } */
        }
        // ANPASSUNG: #callsign-history auch hier anzeigen, wenn konfiguriert und Daten vorhanden
        // Die Positionierung erfolgt durch CSS. Wir müssen es nur hier ausgeben, wenn es nicht 'none' ist.
        if (!empty($lastCallsignHistory) && $callsignHistoryDisplay) {
            echo '<div id="callsign-history">';
            echo 'Last Calls: ';
            foreach ($lastCallsignHistory as $cs) {
                echo htmlspecialchars($cs, ENT_QUOTES, 'UTF-8') . '  ';
            }
            echo '</div>';
        }


    } else { // Kein aktiver Sprecher
        $tgselect_for_display = ($tgselect_str === "0" || $tgselect_str === "") ? "" : $tgselect_str;

        if (empty($tgselect_for_display)) {
             renderTgButtons($tgmon, $tgselect_for_display);
        }

        if (!empty($tgselect_for_display)) {
            $selectedTgName = $tgdb_array[$tgselect_for_display] ?? 'NoNameTG';
            $currentTime = getCurrentTimeLocalized();

            echo '<div class="idle-display-container">';
            echo '  <div class="idle-time">' . $currentTime . '</div>';
            if ($lastKnownCallsign) {
                 echo '<div class="idle-info-line"><span class="idle-last-talker">Last Talker: ' . htmlspecialchars($lastKnownCallsign, ENT_QUOTES, 'UTF-8') . '</span></div>';
            }
            echo '  <div class="idle-info-line">';
            echo '    <span class="idle-active-tg-number">Active TG# ' . htmlspecialchars($tgselect_for_display, ENT_QUOTES, 'UTF-8') . '</span>   '; //   für Leerzeichen
            echo '    <span class="idle-active-tg-name">' . htmlspecialchars($selectedTgName, ENT_QUOTES, 'UTF-8') . '</span>';
            echo '  </div>';
            echo '  <div class="idle-fm-net">' . htmlspecialchars($fmnet, ENT_QUOTES, 'UTF-8') . '</div>';
            echo '</div>';

            // Der Block für #callsign-history wird jetzt hier UND im "Sprecher aktiv" Block gerendert,
            // gesteuert durch $callsignHistoryDisplay.
            // Die ursprüngliche Positionierung ist hier.
            if (!empty($lastCallsignHistory) && $callsignHistoryDisplay) {
                echo '<div id="callsign-history">';
                echo 'Last Calls: ';
                foreach ($lastCallsignHistory as $cs) {
                    echo htmlspecialchars($cs, ENT_QUOTES, 'UTF-8') . '  ';
                }
                echo '</div>';
            }
        } else { // Keine TG ausgewählt UND kein Sprecher aktiv
            $currentTimeLarge = date('H:i:s');
            echo '<div class="large-clock">' . $currentTimeLarge . '</div>';
            echo '<div class="large-net-name">' . htmlspecialchars($fmnet, ENT_QUOTES, 'UTF-8') . '</div>';

            // Auch hier die #callsign-history anzeigen, falls gewünscht
            /* if (!empty($lastCallsignHistory) && $callsignHistoryDisplay) {
                echo '<div id="callsign-history">';
                echo 'Last Calls: ';
                foreach ($lastCallsignHistory as $cs) {
                    echo htmlspecialchars($cs, ENT_QUOTES, 'UTF-8') . '  ';
                }
                echo '</div>';
            } */
        }
    }
} else {
    echo "<div class='error-message'>Error opening log file: " . htmlspecialchars($logFilePath, ENT_QUOTES, 'UTF-8') . "</div>\n";
}
?>