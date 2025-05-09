<?php
// 2025-05-07 08:37:31 Sicherheitskonstante setzen, um Logikänderungen zu unterbinden
define('ALLOW_LOGIC_CHANGES', false);

// Notwendige Konfigurations- und Datenquellen einbinden
include_once __DIR__ . '/config.php';       // Systemkonfiguration
include_once __DIR__ . '/tools.php';        // Hilfsfunktionen für UI oder Datenverarbeitung
include_once __DIR__ . '/functions.php';    // Erweiterte Logikfunktionen
include_once __DIR__ . '/tgdb.dat';         // Talkgroup-Datenbank (TG-Nummer => Name)
include_once __DIR__ . '/userdb.dat';       // Benutzerdatenbank (Callsign => Name, QTH, Info)

// Pfad zur Logdatei und Zeitzone definieren
$logFilePath = '/var/log/svxlink';
date_default_timezone_set('Europe/Berlin'); // Deutsche Zeitzone für Zeitdarstellungen

// Statusvariablen initialisieren
$activeTG = null;            // Aktuell ausgewählte Talkgroup
$lastCallsign = null;        // Letzter aktiver Callsign (Rufzeichen)
$lastTGNumber = null;        // Letzte aktive TG-Nummer
$transmitting = false;       // Sendezustand
$receiving = false;          // Empfangszustand
$lastKnownCallsign = null;   // Letzter bekannter Nutzer für Anzeige
$cssClass = 'node-talk';    // Default-CSS-Klasse für Anzeigeelemente

// Logdatei öffnen
$file = fopen($logFilePath, 'r');
if ($file) {
    $lines = [];
    // Zeilenweise Einlesen
    while (($line = fgets($file)) !== false) {
        $lines[] = $line;
    }
    fclose($file);

    // Logzeilen parsen
    foreach ($lines as $line) {
        // Auswahl einer TG aus Log extrahieren
        if (strpos($line, 'ReflectorLogic: Selecting TG #') !== false &&
            preg_match('/Selecting TG #(\d+)/', $line, $matches)) {
            $activeTG = $matches[1];
        }

        // Talker startet zu sprechen
        if (strpos($line, 'ReflectorLogic: Talker start') !== false &&
            preg_match('/Talker start on TG #(\d+): (\S+)/', $line, $matches)) {
            $lastTGNumber = $matches[1];
            $lastCallsign = $matches[2];
            $lastKnownCallsign = $lastCallsign;
        }

        // Talker beendet Sprechen
        if (strpos($line, 'ReflectorLogic: Talker stop') !== false &&
            preg_match('/Talker stop on TG #(\d+): (\S+)/', $line, $matches)) {
            if ($matches[2] === $lastCallsign) {
                $lastCallsign = null;
                $lastTGNumber = null;
            }
        }

        // Sender aktivieren
        if (strpos($line, 'Tx1: Turning the transmitter ON') !== false) {
            $transmitting = true;
            $receiving = false;
        }

        // Sender deaktivieren
        if (strpos($line, 'Tx1: Turning the transmitter OFF') !== false) {
            $transmitting = false;
        }

        // Empfang aktiv (Squelch offen)
        if (strpos($line, 'Rx1: The squelch is OPEN') !== false) {
            $receiving = true;
            $transmitting = false;
        }

        // Empfang inaktiv (Squelch geschlossen)
        if (strpos($line, 'Rx1: The squelch is CLOSED') !== false) {
            $receiving = false;
            $transmitting = false;
        }
    }

    // Konfigurationsdatei für SVXLink laden
    $svxConfigFile = '/etc/svxlink/svxlink.conf';
    $svxconfig = fopen($svxConfigFile, 'r') ? parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW) : null;

    if (!$svxconfig) {
        echo "Error opening configuration file.\n";
        return;
    }

    // TGs, die überwacht werden sollen
    $tgmon = isset($svxconfig['ReflectorLogic']['MONITOR_TGS']) ? explode(",", $svxconfig['ReflectorLogic']['MONITOR_TGS']) : [];

    // Netzwerkname, z. B. „DL-Funknetz“
    $fmnet = $svxconfig['ReflectorLogic']['FMNET'] ?? 'UnknownNet';

    // Anzeigezustand bestimmen
    if ($transmitting) {
        $cssClass = 'node-talk';   // Senden aktiv
    } elseif ($receiving) {
        $cssClass = 'node-talk1';  // Empfang aktiv
    }

    // Falls aktiver Talker vorhanden ist, Anzeige generieren
    if ($lastCallsign !== null && $lastTGNumber !== null) {
        $tgselect = trim(getSVXTGSelect());  // Aktuell ausgewählte TG
        $tgname = $tgdb_array[$tgselect] ?? 'No_TG_Name'; // TG-Name aus Datenbank
        $userInfo = $userdb_array[$lastCallsign] ?? ' ';  // Benutzerdaten (CSV)
        $userDetails = explode(',', $userInfo); // Trennung in Einzelwerte

        // Darstellung: Callsign, TG-Nummer und TG-Name
        echo "<span class=\"$cssClass\" style=\"color:white; font-size: 68px;\">";
        echo "<span style=\"color: white; vertical-align: text-top;\">&nbsp;&nbsp;" . htmlspecialchars($lastCallsign) . "</br> ";
        echo "<span style=\"color:yellow; font-size: 36px;\">TG#  " . htmlspecialchars($lastTGNumber) . " </br></span>";
        echo "<span style=\"color:red; font-size: 56px;\">" . htmlspecialchars($tgname) . "</span></br>";

        // Benutzerinfos anzeigen, wenn vorhanden
        if (!empty($userInfo) && count($userDetails) >= 3 &&
            (!empty($userDetails[0]) || !empty($userDetails[1]) || !empty($userDetails[2]))) {
            echo "<div style=\"position: absolute; top: 63%; left: 20%; transform: translate(-50%, -50%);\">";
            echo "<div style=\"font-size: 20px; line-height: 1.6; text-align: left; color: white;\">";
            echo "<div style=\"margin-bottom: 10px;\">Name: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[1])) . "</span></div>";
            // echo "<div style=\"margin-bottom: 10px;\">QTH: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[2])) . "</span></div>";
            echo "<div>Info: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[0])) . "</span></div>";
            echo "</div></div>";
        } else {
            // Fallback, wenn Benutzer unbekannt ist
            echo '<div style="position: absolute; top: 55%; left: 18%; font-size: 36px; color: grey; text-align: center;">';
            echo 'User not found in the database';
            echo '</div>';
        }
    } else {
        // Wenn kein aktiver Nutzer spricht: TG-Auswahl anzeigen
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
            

        // Anzeige von Zeit, zuletzt ausgewählter TG und Netz
        if (!empty($tgselect)) {
            $tgname = $tgdb_array[$tgselect] ?? 'NoNameTG';
            $currentTime = date('H:i:s');

            // Einstellbare Fontgrößen
            $fontSizeTGName = '48px'; // z. B. aus Config
            $fontSizeNet = '38px';    // z. B. aus Config
            $fontSizeTGNR = '38px';    // z. B. aus Config
            $fontSizeFMNET = '48px';    // z. B. aus Config

            $nameFontSize = (strlen($tgname) > 16) ? '36px' : $fontSizeTGName;

            echo '<div style="color: white; font-size: 50px;">' . $currentTime . "</div>\n";

            echo '<div style="white-space: nowrap;">';
            echo '<span style="color: green; font-size: 48px;">Last Talker: ' . htmlspecialchars($lastKnownCallsign) . '</span><br />';
            echo '</div>';

            echo '<span style="color: blue; font-size: ' . $fontSizeTGNR . ';">Active TG# ' . htmlspecialchars($tgselect) . '</span>&nbsp;&nbsp;&nbsp;';
            echo '<span style="color: white; font-size: ' . $fontSizeNet . ';">' . htmlspecialchars($tgname) . '</span><br />';
            echo '<span style="color: cyan; font-size: ' . $fontSizeFMNET . ';">' . htmlspecialchars($fmnet) . '</span>';
        } else {
            // Nur Uhrzeit und Netzname anzeigen
            $currentTime = date('H:i:s');
        
            // Einstellbare Fontgrößen (z. B. aus Config)
            $fontSizeNet   = '100px';
            $fontSizeClock = '100px';
        
            echo '<div style="
                color: white;
                font-size: ' . $fontSizeClock . ';
                line-height: ' . $fontSizeClock . ';
                margin: 0;
                text-align: center;
            ">' . $currentTime . '</div>';
        
            echo '<span style="
                color: cyan;
                font-size: ' . $fontSizeNet . ';
                line-height: ' . $fontSizeNet . ';
                margin: 0;
                display: block;
                text-align: center;
            ">' . htmlspecialchars($fmnet) . '</span>';
        }
        

    }
} else {
    // Fehler beim Öffnen der Logdatei
    echo "Error opening log file.\n";
}
?>
