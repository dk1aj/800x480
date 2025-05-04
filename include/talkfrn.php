<?php
$file = '/var/log/svxlink'; // Path to the log file

// Open the file for reading
$handle = fopen($file, "r");

// Variables storing information about the currently transmitting station and their display settings
$currentStation = null;
$stationLocationColor = 'yellow'; // Default color for $stationLocation
$stationNameColor = 'white'; // Default color for $stationName
$stationLocationSize = '40pt'; // Default font size for $stationLocation
$stationNameSize = '24pt'; // Default font size for $stationName
$serverAddress = null;
$channelName = null;

// Read the file line by line
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // Look for lines with transmission start
        if (strpos($line, 'voice started') !== false) {
            // Extract needed information
            $startPos = strpos($line, '<ON>') + 4;
            $endPos = strpos($line, '</ON>', $startPos);
            $stationInfo = substr($line, $startPos, $endPos - $startPos);
            $stationData = explode(',', $stationInfo);
            $stationName = $stationData[1];
            $stationLocation = trim($stationData[0]);

            // Save information about the currently transmitting station
            $currentStation = "$stationLocation <br> $stationName";
        } elseif (strpos($line, 'Tx1: Turning the transmitter OFF') !== false) {
            // Transmission ended - reset current station information
            $currentStation = null;
        }

        // Extract server and channel information
        if (preg_match('/<BN>(.*?)<\/BN>/', $line, $matches)) {
            $serverAddress = $matches[1];
        }

        if (preg_match('/<NT>(.*?)<\/NT>/', $line, $matches)) {
            $channelName = $matches[1];
        }
    }

    fclose($handle);

    // Display server and channel information
//    if ($serverAddress !== null && $channelName !== null) {
//        echo "<div style=\"padding: 2px;text-align: center;font-size: 12pt;color: white;\">Server: $serverAddress | Channel: $channelName</div>";
//    }

    // Display information about the currently transmitting station in a window with a specific style
    if ($currentStation !== null) {
        echo '<div style="padding:0px;width:90%;height:115px;background-image: linear-gradient(to bottom, #000000 100%, #bcbaba 0%);border-radius: 5px;-moz-border-radius:5px;-webkit-border-radius:5px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:1px;margin-bottom:0px;line-height:1.6;white-space:normal;text-align: center;">';
        echo '<div style="font:9pt arial,sans-serif;margin: auto;color:white;">';
        echo "<span style=\"color: $stationLocationColor; font-size: $stationLocationSize;\">$stationLocation</span><br>";
        echo "<span style=\"color: $stationNameColor; font-size: $stationNameSize;\">$stationName</span>";
        echo '</div></div>';
    } else {
        echo '<div style="padding:0px;width:90%;height:115px;background-image: linear-gradient(to bottom, #000000 100%, #bcbaba 0%);border-radius: 5px;-moz-border-radius:5px;-webkit-border-radius:5px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:1px;margin-bottom:0px;line-height:1.6;white-space:normal;text-align: center;">';
        echo '<div style="font:9pt arial,sans-serif;margin: auto;color:white;">';
        echo " ";
        echo '</div></div>';
    }
} else {
    // Handle file read error
    echo "Cannot open the file.";
}
?>