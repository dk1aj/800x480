<?php
// Load all required configuration and data files for SVXLink status parsing
define('ALLOW_LOGIC_CHANGES', false);
include_once __DIR__.'/config.php';
include_once __DIR__.'/tools.php';
include_once __DIR__.'/functions.php';
include_once __DIR__.'/tgdb.dat';
include_once __DIR__.'/userdb.dat';

$logFilePath = '/var/log/svxlink';
date_default_timezone_set('Europe/Berlin');

$activeTG = null;
$lastCallsign = null;
$lastTGNumber = null;
$transmitting = false;
$receiving = false;
$previousTG = $activeTG;
$lastKnownCallsign = null;
$cssClass = 'node-talk2';

$file = fopen($logFilePath, 'r');
if ($file) {
  $lines = [];
  while (($line = fgets($file)) !== false) {
    array_push($lines, $line);
  }
  fclose($file);

  foreach ($lines as $line) {
    if (strpos($line, 'ReflectorLogic: Selecting TG #') !== false) {
      preg_match('/Selecting TG #(\d+)/', $line, $matches);
      if (count($matches) === 2) {
        $activeTG = $matches[1];
      }
    }
    if (strpos($line, 'ReflectorLogic: Talker start') !== false) {
      preg_match('/Talker start on TG #(\d+): (\S+)/', $line, $matches);
      if (count($matches) === 3) {
        $lastCallsign = $matches[2];
        $lastTGNumber = $matches[1];
        $lastKnownCallsign = $lastCallsign;
      }
    }
    if (strpos($line, 'ReflectorLogic: Talker stop') !== false) {
      preg_match('/Talker stop on TG #(\d+): (\S+)/', $line, $matches);
      if (count($matches) === 3 && $matches[2] === $lastCallsign) {
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
    }
  }

  $svxConfigFile = '/etc/svxlink/svxlink.conf';
  if (fopen($svxConfigFile, 'r')) {
    $svxconfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
  } else {
    echo "Error opening configuration file.\n";
  }

  $tgmon = isset($svxconfig['ReflectorLogic']['MONITOR_TGS']) ? explode(",", $svxconfig['ReflectorLogic']['MONITOR_TGS']) : [];
  $fmnet = isset($svxconfig['ReflectorLogic']['FMNET']) ? $svxconfig['ReflectorLogic']['FMNET'] : 'UnknownNet';

  if ($transmitting) {
    $cssClass = 'node-talk';
  } elseif ($receiving) {
    $cssClass = 'node-talk1';
  }

  if ($lastCallsign !== null && $lastTGNumber !== null) {
    $tgselect = trim(getSVXTGSelect());
    $tgname = isset($tgdb_array[$tgselect]) ? $tgdb_array[$tgselect] : 'No_TG_Name';
    $userInfo = isset($userdb_array[$lastCallsign]) ? $userdb_array[$lastCallsign] : ' ';
    $userDetails = explode(',', $userInfo);

    echo "<span class=\"$cssClass\" style=\"color:white; font-size: 68px;\">";
    echo "<span style=\"color: blue; vertical-align: text-top;\">&nbsp;&nbsp;" . htmlspecialchars($lastCallsign) . "</br> ";
    echo "<span style=\"color:red; font-size: 36px;\">TG#  " . htmlspecialchars($lastTGNumber) . " </br></span>";
    echo "<span style=\"color:green; font-size: 56px;\">" . htmlspecialchars($tgname) . "</span></br>";

    
    if (!empty($userInfo) && is_array($userDetails) && count($userDetails) >= 3 && (!empty($userDetails[0]) || !empty($userDetails[1]) || !empty($userDetails[2]))) {
      echo "<div style=\"position: absolute; top: 63%; left: 50%; transform: translate(-50%, -50%);\">";
      echo "<div style=\"font-size: 20px; line-height: 1.6; text-align: left; color: white;\">";
      echo "<div style=\"margin-bottom: 10px;\">Name: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[1])) . "</span></div>";
      echo "<div style=\"margin-bottom: 10px;\">QTH: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[2])) . "</span></div>";
      echo "<div>Info: <span style=\"color: cyan;\">" . htmlspecialchars(trim($userDetails[0])) . "</span></div>";
      echo "</div>";
      echo "</div>";
  } else {
      echo "<div style=\"position: absolute; top: 65%; left: 50%; transform: translate(-50%, -50%); font-size: 36px; color: white;\">";
      echo "No data in Database #1";
      echo "</div>";
  }
  } else {
    $tgselect = trim(getSVXTGSelect());
    if ($tgselect == "0") {
      $tgselect = "";
    }
    if (!empty($tgmon)) {
      $colored = [];
      foreach ($tgmon as $tg) {
          $color = ((int)$tg == (int)$tgselect) ? 'red' : 'yellow';
          $colored[] = "<span style=\"color:$color;\">" . htmlspecialchars($tg) . "</span>";
      }
      $text = implode(", ", $colored);
      $fontSize = (strlen(strip_tags($text)) > 25) ? 28 : 48; // Schriftgröße halbieren bei >25 Zeichen
      echo "<span style=\"font-size: {$fontSize}px;\">$text</span>";
    } else {
      echo "<span style=\"color:red; font-size: 48px;\">No TG</span>";
    }
    
    if (!empty($tgselect)) {
      $tgselect = trim(getSVXTGSelect());
      $tgname = isset($tgdb_array[$tgselect]) ? $tgdb_array[$tgselect] : 'NoNameTG';
      echo '<div style="color: white; font-size: 90px;">' . date('H:i:s') . "</div>\n";
      $tgFontSize = "48px";
      $nameFontSize = (strlen($tgname) > 16) ? "36px" : "48px";
      echo '<div style="white-space: nowrap;">';
      echo "<span style=\"color:green; font-size: 48px;\">Last Talker: " . htmlspecialchars($lastKnownCallsign) . "</span><br />";
      echo '</div>';
      echo '<span style="color: blue; font-size: ' . $tgFontSize . ';">last TG#' . htmlspecialchars($tgselect) . '</span>&nbsp;';
      echo '<span style="color: white; font-size: ' . $nameFontSize . ';">' . htmlspecialchars($tgname) . '</span><br />';
      echo "<span style=\"color: cyan; font-size: 58px;\">" . htmlspecialchars($fmnet) . "</span>";
    } else {
      echo '<br><br><div style="color: white; font-size: 160px;">' . date('H:i:s') . "</div>\n";
      // echo "<span style=\"color:blue; font-size: 48px;\">" . (!empty($tgselect) ? "active TG#  " . htmlspecialchars($tgselect) : " ") . "</span><br />";
      echo "<span style=\"color: cyan; font-size: 88px;\">" . htmlspecialchars($fmnet) . "</span>";
    }
  }
} else {
  echo "Error opening log file.\n";
}
?>
