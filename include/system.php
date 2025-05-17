<?php
// Konfiguration
$dsi_version = "v0.1.1";
define('ALLOW_LOGIC_CHANGES', false);

// SvxLink-Version aus Logdatei extrahieren
$logFilePath = '/var/log/svxlink';
$svxVersion = 'n/a';
if (is_readable($logFilePath)) {
    $lines = file($logFilePath);
    foreach ($lines as $line) {
        if (preg_match('/SvxLink\s+v([\d\.@]+)/', $line, $match)) {
            $svxVersion = $match[1];
            break;
        }
    }
}

// Status: SvxLink aktiv?
$svxlinkActive = trim(shell_exec('systemctl is-active svxlink')) === 'active';

// CPU-Temperatur ermitteln
$cpuTempHTML = "<td style=\"background: white\">---</td>\n";
$thermalFile = '/sys/class/thermal/thermal_zone0/temp';
if (is_readable($thermalFile)) {
    $raw = (int) @file_get_contents($thermalFile);
    if ($raw > 0) {
        $temp = round($raw / 1000);
        $color = $temp >= 70 ? "#f00" : ($temp >= 57 ? "#fa0" : "#1d1");
        $cpuTempHTML = "<td style=\"background: $color\">$temp&deg;C</td>\n";
    }
}

// CPU-Auslastung berechnen
function getCpuUsage() {
    $s1 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
    usleep(500000);
    $s2 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
    $idle1 = $s1[3] ?? 0;
    $idle2 = $s2[3] ?? 0;
    $total1 = array_sum($s1);
    $total2 = array_sum($s2);
    $delta = $total2 - $total1;
    return $delta > 0 ? round(100 * (($delta - ($idle2 - $idle1)) / $delta)) : 0;
}
$cpu = getCpuUsage();
$cpuUsageHTML = "<td style=\"background: " . ($cpu >= 80 ? "#f00" : ($cpu >= 50 ? "#fa0" : "#1d1")) . "\">" . $cpu . "%</td>\n";

// WLAN-Signalstärke abrufen
function getSignalLevel($interface = 'wlan0') {
    $output = shell_exec("/sbin/iwconfig $interface 2>/dev/null");
    if (preg_match('/Signal level=(-?[0-9]+) dBm/', $output, $match)) {
        return (int)$match[1];
    }
    return null;
}

$signal = getSignalLevel();
if ($signal === null) {
    $bars = -1;
    $host = trim(shell_exec("hostname"));
    $domain = trim(shell_exec("dnsdomainname"));
    $ip = $host . (!empty($domain) ? "." . $domain : "");
    $netType = "LAN 🖧";
} else {
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
      border-radius: 2px;
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
      <th style="width: 22%; background: blue;">
        <div style="display: flex; align-items: center;">
          <div class="signal">
            <?php
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
      <th colspan="2" style="width: 250px; background: red;">
        <span style="font-size: 10px; color: white;">
          <span style="color: white; font-weight: bold;">SvxDSI</span> by dk1aj from M0IQF · <?= $dsi_version ?> · SvxLink <?= $svxVersion ?>
        </span>
        <?php
          $svxText = $svxlinkActive ? 'ACTIVE' : 'INACTIVE';
          $svxColor = $svxlinkActive ? 'white' : 'cyan';
          $svxClass = $svxlinkActive ? '' : 'blinking';
        ?>
        &nbsp;&nbsp;&nbsp;Svx:<span class="<?= $svxClass ?>" style="color: <?= $svxColor ?>;"> <?= $svxText ?>&nbsp;</span>
      </th>

      <?= $cpuTempHTML ?>
      <?= $cpuUsageHTML ?>
    </tr>
  </table>
</body>
</html>
