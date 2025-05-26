<?php
// Konfiguration für die DSI-Anzeige (800x400px), Version und Logikumschaltung
$dsi_version = "v0.1.1";
define('ALLOW_LOGIC_CHANGES', false);

// Auslesen der SvxLink-Version aus der Logdatei, sofern lesbar
$logFilePath = '/var/log/svxlink';
$svxVersion = 'n/a';
if (is_readable($logFilePath)) {
  $lines = file($logFilePath);
  foreach ($lines as $line) {
    // Extrahieren der Versionsnummer mittels Regex
    if (preg_match('/SvxLink\s+v([\d\.@]+)/', $line, $match)) {
      $svxVersion = $match[1];
      break;
    }
  }
}

// Prüfen, ob SvxLink aktuell aktiv ist
$svxlinkActive = trim(shell_exec('systemctl is-active svxlink')) === 'active';

// CPU-Temperatur abrufen, falls möglich
$cpuTempHTML = "<td style=\"background: white\">---</td>\n";
$thermalFile = '/sys/class/thermal/thermal_zone0/temp';
if (is_readable($thermalFile)) {
  $raw = (int) @file_get_contents($thermalFile); // Temperatur in Milligrad Celsius
  if ($raw > 0) {
    $temp = round($raw / 1000); // Umwandlung in Grad Celsius
    // Farbzuordnung zur visuellen Warnung auf Touch-UI
    $color = $temp >= 70 ? "#f00" : ($temp >= 57 ? "#fa0" : "#1d1");
    $cpuTempHTML = "<td style=\"background: $color\">$temp&deg;C</td>\n";
  }
}

// Funktion zur CPU-Auslastungsberechnung mit kurzem Intervall
function getCpuUsage() {
  $s1 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
  usleep(500000); // 0,5 Sekunden warten
  $s2 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
  $idle1 = $s1[3] ?? 0;
  $idle2 = $s2[3] ?? 0;
  $total1 = array_sum($s1);
  $total2 = array_sum($s2);
  $delta = $total2 - $total1;
  // CPU-Auslastung in Prozent berechnen
  return $delta > 0 ? round(100 * (($delta - ($idle2 - $idle1)) / $delta)) : 0;
}

$cpu = getCpuUsage();
// Wiederverwendung des Farbwerts für einheitliche Darstellung
$cpuTempHTML = "<td style=\"background: $color; width: 60px; font-size: 16px; text-align: center;\">$temp&deg;C</td>\n";
$bgColor = $cpu >= 80 ? "#f00" : ($cpu >= 50 ? "#fa0" : "#1d1");
$cpuUsageHTML = sprintf(
  '<td style="background: %s; width: 60px; font-size: 16px; text-align: center;">%d%%</td>' . "\n",
  $bgColor,
  $cpu
);

// Funktion zum Abruf der WLAN-Signalstärke
function getSignalLevel($interface = 'wlan0') {
  $output = shell_exec("/sbin/iwconfig $interface 2>/dev/null");
  // Extraktion des Signalpegels in dBm
  if (preg_match('/Signal level=(-?[0-9]+) dBm/', $output, $match)) {
    return (int)$match[1];
  }
  return null; // kein WLAN erkannt
}

$signal = getSignalLevel();
if ($signal === null) {
  // LAN-Fallback, falls WLAN nicht verfügbar ist
  $bars = -1;
  $host = trim(shell_exec("hostname"));
  $domain = trim(shell_exec("dnsdomainname"));
  $ip = $host . (!empty($domain) ? "." . $domain : "");
  $netType = "LAN 🖧";
} else {
  // Einordnung der WLAN-Signalstärke in Balkenanzeige
  if ($signal >= -50) $bars = 4;
  elseif ($signal >= -60) $bars = 3;
  elseif ($signal >= -70) $bars = 2;
  elseif ($signal >= -80) $bars = 1;
  else $bars = 0;
  $ip = trim(shell_exec("hostname -I | awk '{print \$1}'"));
  $netType = "WLAN 📶";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
 <title>SvxDSI</title>
 <style>
  /* Touch-UI-optimiertes Layout */
  body {
   max-width: 790px;
   margin: 0 auto;
   font-family: sans-serif;
   font-size: 12px;
  }
  table {
   margin: 0 auto;
  }
  .signal {
   display: flex;
   align-items: center;
   height: 20px;
   width: 40px;
   margin-right: 5px;
   justify-content: center;
   flex-direction: column;
  }
  .bar {
   width: 5px;
   margin-right: 2px;
   background: black;
  }
  .bar.active {
   background: white;
  }
  .net-type {
   font-size: 10px;
   color: white;
   margin-top: 2px;
  }
  .ip {
   font-weight: bold;
   color: yellow;
   font-size: 12px;
  }
  .blinking {
   animation: blink 1s step-start 0s infinite;
  }
  @keyframes blink {
   50% {
    opacity: 0;
   }
  }
 </style>
</head>
<body>
 <table style="margin-top:0;">
  <tr>
   <th style="width: 20%; background: blue;">
    <div style="display: flex; align-items: center;">
     <div class="signal">
      <?php
       // WLAN-Signalvisualisierung als Balkenanzeige
       $heights = [5, 10, 15, 20];
       if ($bars >= 0) {
         for ($i = 0; $i < 4; $i++) {
           $active = $i < $bars ? 'active' : '';
           echo "<div class='bar $active' style='height: {$heights[$i]}px;'></div>";
         }
       }
      ?>
      <div class="net-type"><?= $netType ?></div>
     </div>
     <span class="ip"><?= str_replace(' ', '<br>', $ip) ?></span>
    </div>
   </th>
   <th colspan="2" style="width: 1%; background: red;">
    <span style="font-size: 10px; color: white;">
     <span style="color: white; font-weight: bold;">&nbsp;&nbsp;SvxDSI</span> by dk1aj from M0IQF · <?= $dsi_version ?> · SvxLink <?= $svxVersion ?>
    </span>
    <?php
     // Statusanzeige SvxLink (aktiv/inaktiv)
     $svxText = $svxlinkActive ? 'ACTIVE' : 'INACTIVE';
     $svxColor = $svxlinkActive ? 'white' : 'cyan';
     $svxClass = $svxlinkActive ? '' : 'blinking';
    ?>
    &nbsp;&nbsp;&nbsp;Svx:<span class="<?= $svxClass ?>" style="color: <?= $svxColor ?>;"> <?= $svxText ?>&nbsp;</span>
   </th>
   <?= $cpuTempHTML ?>
   <?= $cpuUsageHTML ?>
  </tr>
  <tr>
   <!-- Systemstatusanzeige unten -->
   <td colspan="3" style="width: 100%; background: #333; color: #0f0; font-size: 12px; text-align: left;">
    &nbsp;&nbsp;System OK ·  SVXLINK <?= $svxlinkActive ? 'läuft' : 'steht' ?> · CPU <?= $cpu ?>%
   </td>
  </tr>
 </table>
</body>
</html>
