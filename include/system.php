<?php
define('ALLOW_LOGIC_CHANGES', false);
// $mmdvmActive = trim(shell_exec('systemctl is-active mmdvm')) === 'active';
$svxlinkActive = trim(shell_exec('systemctl is-active svxlink')) === 'active';
$version = "v0.0.6";

$cpuTempHTML = "<td style=\"background: white\">---</td>\n";
if (file_exists('/sys/class/thermal/thermal_zone0/temp')) {
    $raw = (int) @file_get_contents('/sys/class/thermal/thermal_zone0/temp');
    if ($raw > 0) {
        $temp = round($raw / 1000);
        $color = $temp >= 70 ? "#f00" : ($temp >= 57 ? "#fa0" : "#1d1");
        $cpuTempHTML = "<td style=\"background: $color\">$temp&deg;C</td>\n";
    }
}

function getCpuUsage() {
    $s1 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
    usleep(500000);
    $s2 = explode(" ", preg_replace("/^cpu\s+/", "", file('/proc/stat')[0]));
    $idle1 = $s1[3]; $idle2 = $s2[3];
    $total1 = array_sum($s1); $total2 = array_sum($s2);
    $delta = $total2 - $total1;
    return $delta ? round(100 * (($delta - ($idle2 - $idle1)) / $delta)) : 0;
}
$cpu = getCpuUsage();
$cpuUsageHTML = "<td style=\"background: " . ($cpu >= 80 ? "#f00" : ($cpu >= 50 ? "#fa0" : "#1d1")) . "\">" . $cpu . "%</td>\n";

$signal = exec("/sbin/iwconfig wlan0 | grep -i 'signal level' | awk '{print \$4}' | cut -d'=' -f2");
function getSignalBars($dbm) {
    if ($dbm >= -50) return 4;
    if ($dbm >= -60) return 3;
    if ($dbm >= -70) return 2;
    if ($dbm >= -80) return 1;
    return 0;
}
$bars = getSignalBars($signal);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <title>Pixel Vista 25</title>
  <style>
    body {
      max-width: 790px;
      margin: 0 auto;
      font-family: sans-serif;
      font-size: 12px;
    }
    table {
      margin: 0 auto; /* HIER hinzugefügt: Tabelle horizontal zentrieren */
    }
    .signal {
      display: flex;
      align-items: flex-end;
      height: 20px;
      width: 30px;
      margin-right: 5px;
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
    .ip {
      font-weight: bold;
      color: yellow;
      font-size: 12px;
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
              $heights = [5, 10, 15, 20];
              for ($i = 0; $i < 4; $i++) {
                  $active = $i < $bars ? 'active' : '';
                  echo "<div class='bar $active' style='height: {$heights[$i]}px;'></div>";
              }
            ?>
          </div>
          <span class="ip"><?= str_replace(' ', '<br>', exec("hostname -I | awk '{print \$1}'")) ?></span>
        </div>
      </th>
      <th colspan="2" style="width: 250px; background: red;">
        <span style="font-weight: bold; color: white;">Pixel Vista 25</span>
        <!-- <span style="color: white; font-size: 10px;">by dk1aj from M0IQF · <?= $version ?></span>-->
        <span style="color: white; font-size: 10px;">by dk1aj from M0IQF · <?= $version ?></span>
        SvxLink: <span style="color: <?= $svxlinkActive ? 'white' : 'black' ?>;">  <?= $svxlinkActive ? 'ACTIVE' : 'INACTIVE' ?> </span>
      </th>
      <?= $cpuTempHTML ?>
      <?= $cpuUsageHTML ?>
    </tr>
  </table>
</body>
</html>
