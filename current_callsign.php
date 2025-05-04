<?php
/**
 * Copyright (c) 2025 dk1aj
 */

const LOG_FILE_PATH = '/var/log/svxlink';
const TALKER_START_IDENTIFIER = 'ReflectorLogic: Talker start';
const CALLSIGN_REGEX = '/Talker start on TG #(\\d+): ([A-Z0-9]+)/i';

$logFilePath = LOG_FILE_PATH;
$lastKnownCallsign = '';

if (file_exists($logFilePath)) {
    $lines = @file($logFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        error_log("Fehler beim Lesen der Logdatei: " . $logFilePath);
        return '';
    }
    $lines = array_reverse($lines);

    foreach ($lines as $line) {
        if (strpos($line, TALKER_START_IDENTIFIER) !== false) {
            if (preg_match(CALLSIGN_REGEX, $line, $matches)) {
                $callsign = trim($matches[2]);
                if (strlen($callsign) >= 3) {
                    $lastKnownCallsign = $callsign;
                    break;
                }
            }
        }
    }
} else {
    error_log("Logdatei nicht gefunden: " . $logFilePath);
    return '';
}

echo htmlspecialchars($lastKnownCallsign);
?>