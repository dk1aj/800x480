<?php
// 2025-05-17 21:22:40
// Sicherheitskonstante setzen, um Logikänderungen zu unterbinden
define('ALLOW_LOGIC_CHANGES', false);

// Notwendige Konfigurations- und Datenquellen einbinden
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/tools.php';
include_once __DIR__ . '/functions.php';
include_once __DIR__ . '/tgdb.dat';
include_once __DIR__ . '/userdb.dat';

$logFilePath = '/var/log/svxlink';
date_default_timezone_set('Europe/Berlin');

$activeTG = null;
$lastCallsign = null;
$lastTGNumber = null;
$transmitting = false;
$receiving = false;
$lastKnownCallsign = null;
$cssClass = 'node-talk';

$file = fopen($logFilePath, 'r');
if ($file) {
    $lines = [];
    while (($line = fgets($file)) !== false) {
        $lines[] = $line;
    }
    fclose($file);

    foreach ($lines as $line) {
        if (strpos($line, 'ReflectorLogic: Selecting TG #') !== false &&
            preg_match('/Selecting TG #(\d+)/', $line, $matches)) {
            $activeTG = $matches[1];
        }

        if (strpos($line, 'ReflectorLogic: Talker start') !== false &&
            preg_match('/Talker start on TG #(\d+): (\S+)/', $line, $matches)) {
            $lastTGNumber = $matches[1];
            $lastCallsign = $matches[2];
            $lastKnownCallsign = $lastCallsign;
        }

        if (strpos($line, 'ReflectorLogic: Talker stop') !== false &&
            preg_match('/Talker stop on TG #(\d+): (\S+)/', $line, $matches)) {
            if ($matches[2] === $lastCallsign) {
                $lastCallsign = null;
                $lastTGNumber = null;
            }
        }

        if (strpos($line, 'Tx1: Turning the transmitter ON') !== false) {
            $transmitting = true;
            $receiving = false;
        }

        if (strpos($line, 'Tx1: Turning the transmitter OFF') !== false) {
            $transmitting = false;
        }

        if (strpos($line, 'Rx1: The squelch is OPEN') !== false) {
            $receiving = true;
            $transmitting = false;
        }

        if (strpos($line, 'Rx1: The squelch is CLOSED') !== false) {
            $receiving = false;
            $transmitting = false;
        }
    }

    $svxConfigFile = '/etc/svxlink/svxlink.conf';
    $svxconfig = fopen($svxConfigFile, 'r') ? parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW) : null;

    if (!$svxconfig) {
        echo "Error opening configuration file.\n";
        return;
    }

    $tgmon = isset($svxconfig['ReflectorLogic']['MONITOR_TGS']) ? explode(",", $svxconfig['ReflectorLogic']['MONITOR_TGS']) : [];
    $fmnet = $svxconfig['ReflectorLogic']['FMNET'] ?? 'UnknownNet';

    if ($transmitting) {
        $cssClass = 'node-talk';
    } elseif ($receiving) {
        $cssClass = 'node-talk1';
    }

    if ($lastCallsign !== null && $lastTGNumber !== null) {
        $tgselect = trim(getSVXTGSelect());
        $tgname = $tgdb_array[$tgselect] ?? 'No_TG_Name';
        $userInfo = $userdb_array[$lastCallsign] ?? ' ';
        $userDetails = explode(',', $userInfo);

        echo "<span class=\"$cssClass\" style=\"color:white; font-size: 68px;\">";
        echo "<span style=\"color: white; vertical-align: text-top;\">&nbsp;&nbsp;" . htmlspecialchars($lastCallsign) . "</br> ";
        echo "<span style=\"color:yellow; font-size: 36px;\">TG#  " . htmlspecialchars($lastTGNumber) . " </br></span>";
        echo "<span style=\"color:red; font-size: 56px;\">" . htmlspecialchars($tgname) . "</span></br>";

        if (!empty($userInfo) && count($userDetails) >= 3 &&
            (!empty($userDetails[0]) || !empty($userDetails[1]) || !empty($userDetails[2]))) {
            echo "<div style=\"position: absolute; top: 63%; left: 20%; transform: translate(-50%, -50%);\">";
            echo "<div style=\"font-size: 20px; line-height: 1.6; text-align: left; color: white;\">";
            echo "<div style=\"margin-bottom: 10px;\">Name: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[1])) . "</span></div>";
            echo "<div style=\"margin-bottom: 10px;\">QTH: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[2])) . "</span></div>";
            echo "<div>Info: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[0])) . "</span></div>";
            echo "</div></div>";
        } else {
            echo '<div style="position: absolute; top: 55%; left: 18%; font-size: 36px; color: grey; text-align: center;">';
            echo 'User not found in the database';
            echo '</div>';
        }
    } else {
        $tgselect = trim(getSVXTGSelect());
        if ($tgselect === "0") {
            $tgselect = "";
        }

        if (!empty($tgmon)) {
            echo '<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; padding: 10px;">';
            foreach ($tgmon as $tg) {
                $tg = trim($tg);
                $isActive = ((int)$tg === (int)$tgselect);
                $color = $isActive ? '#ff4444' : '#ffff00';
                $bgColor = $isActive ? '#222' : '#444';
                $fontSize = '36px';

                echo '<form method="post" action="set_tg.php" style="margin: 0;">';
                echo '<input type="hidden" name="tg" value="' . htmlspecialchars($tg) . '">';
                echo '<button type="submit" style="min-width: 60px; height: 40px; font-size: ' . $fontSize . '; background-color: ' . $bgColor . '; color: ' . $color . '; border: none; border-radius: 12px;">';
                echo htmlspecialchars($tg);
                echo '</button>';
                echo '</form>';
            }
            echo '</div>';
        } else {
            echo "<span style=\"color:red; font-size: 48px;\">No TG</span>";
        }

        if (!empty($tgselect)) {
            $tgname = $tgdb_array[$tgselect] ?? 'NoNameTG';
            $currentTime = date('H:i:s');

            $fontSizeTGName = '48px';
            $fontSizeNet = '38px';
            $fontSizeTGNR = '38px';
            $fontSizeFMNET = '48px';

            $nameFontSize = (strlen($tgname) > 16) ? '36px' : $fontSizeTGName;

            echo '<div style="color: white; font-size: 50px;">' . $currentTime . "</div>\n";

            echo '<div style="white-space: nowrap;">';
            echo '<span style="color: green; font-size: 48px;">Last Talker: ' . htmlspecialchars($lastKnownCallsign) . '</span><br />';
            echo '</div>';

echo '<span style="color: blue; font-size: ' . $fontSizeTGNR . ';">Active TG# ' . htmlspecialchars($tgselect) . '</span>';
echo '<form method="post" action="set_tg.php" style="display:inline;">';
echo '<input type="hidden" name="tg" value="0">';
echo '<button type="submit" style="font-size: ' . $fontSizeTGNR . '; background-color: #444; color: yellow; border: none; border-radius: 12px; padding: 5px 12px; margin-left: 8px;">Reset TG</button>';
echo '</form><br />';


            echo '<span style="color: white; font-size: ' . $fontSizeNet . ';">' . htmlspecialchars($tgname) . '</span><br />';
            echo '<span style="color: cyan; font-size: ' . $fontSizeFMNET . ';">' . htmlspecialchars($fmnet) . '</span>';
        } else {
            $currentTime = date('H:i:s');
            $fontSizeNet   = '100px';
            $fontSizeClock = '100px';

            echo '<div style="color: white; font-size: ' . $fontSizeClock . '; line-height: ' . $fontSizeClock . '; margin: 0; text-align: center;">' . $currentTime . '</div>';
            echo '<span style="color: cyan; font-size: ' . $fontSizeNet . '; line-height: ' . $fontSizeNet . '; margin: 0; display: block; text-align: center;">' . htmlspecialchars($fmnet) . '</span>';
        }
    }
} else {
    echo "Error opening log file.\n";
}
?>
