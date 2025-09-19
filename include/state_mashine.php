<?php
// SVXLink FSM Minimal - Nur aktueller Status mit Farbcodierung

// Session starten, um Daten zwischen Anfragen zu speichern
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Konfiguration ---
define('SVXLINK_LOG_FILE', '/var/log/svxlink'); // Pfad zur SVXLink Logdatei (ggf. anpassen)
define('REFRESH_INTERVAL', 1);                 // Aktualisierungsintervall in Sekunden für den Browser
define('DEFAULT_TIMEZONE', 'Europe/Berlin');   // Zeitzone

date_default_timezone_set(DEFAULT_TIMEZONE);

// HTML Kopf (wird immer gesendet)
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'>";
echo "<meta http-equiv='refresh' content='" . REFRESH_INTERVAL . "'>";
echo "<title>SVXLink Status</title>"; // Titel leicht angepasst
echo "<style>
body { font-family: monospace; background: #000; color: #fff; text-align: center; margin-top: 50px; font-size: 48px; }
.state-idle { color: #ffff00; }      /* GELB */
.state-rx { color: #00ff00; }        /* GRÜN */
.state-tx { color: #ff0000; }        /* ROT */
.error-message { color: #ff6666; font-size: 24px; margin-top: 20px; }
.debug-info { font-size: 12px; color: #888; margin-top: 15px; }
.cache-info { font-size: 10px; color: #555; position: fixed; bottom: 5px; left: 5px; }
</style>";
echo "</head><body>";

class SvxLinkFSM {
    private const STATE_IDLE = 'IDLE';
    private const STATE_RX_ACTIVE = 'RX';
    private const STATE_TX_ACTIVE = 'TX';

    private $logFilePath;
    public $state = self::STATE_IDLE;
    public $prevState = self::STATE_IDLE; // Behalten wir intern für die Logik, geben es aber nicht mehr aus
    public $lastRelevantLogLine = '';

    public function __construct($logFilePath) {
        $this->logFilePath = $logFilePath;
        // Beim Initialisieren ist der prevState gleich dem state
        $this->prevState = $this->state;
    }

    public function loadStateFromCache($cachedData) {
        if (isset($cachedData['state'], $cachedData['prevState'], $cachedData['lastRelevantLogLine'])) {
            $this->state = $cachedData['state'];
            $this->prevState = $cachedData['prevState'];
            $this->lastRelevantLogLine = $cachedData['lastRelevantLogLine'];
            return true;
        }
        return false;
    }

    public function getStateForCache() {
        return [
            'state' => $this->state,
            'prevState' => $this->prevState,
            'lastRelevantLogLine' => $this->lastRelevantLogLine,
        ];
    }

    public function processLog() {
        if (!file_exists($this->logFilePath)) {
            echo "<div class='error-message'><b>Logfile nicht gefunden:</b> " . htmlspecialchars($this->logFilePath) . "</div>";
            $this->prevState = $this->state;
            $this->state = self::STATE_IDLE;
            $this->lastRelevantLogLine = "Error: Logfile not found.";
            return;
        }
        if (!is_readable($this->logFilePath)) {
            echo "<div class='error-message'><b>Logfile nicht lesbar:</b> " . htmlspecialchars($this->logFilePath) . " (Berechtigungen prüfen)</div>";
            $this->prevState = $this->state;
            $this->state = self::STATE_IDLE;
            $this->lastRelevantLogLine = "Error: Logfile not readable.";
            return;
        }

        $lines = file($this->logFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            echo "<div class='error-message'><b>Fehler beim Lesen der Logdatei:</b> " . htmlspecialchars($this->logFilePath) . "</div>";
            $this->prevState = $this->state;
            $this->state = self::STATE_IDLE;
            $this->lastRelevantLogLine = "Error: Could not read logfile.";
            return;
        }
        
        $initialObjectState = $this->state; // Zustand des Objekts *vor* dem Parsen dieser Logdatei
        $loopDeterminedState = self::STATE_IDLE; // Startannahme für diesen Log-Parse-Durchlauf
        $loopDeterminedLine = "";

        if (empty($lines)) {
            if ($this->state !== self::STATE_IDLE) {
                $this->prevState = $this->state;
                $this->state = self::STATE_IDLE;
                $this->lastRelevantLogLine = "Log empty, resetting to IDLE";
            }
            return;
        }
        
        foreach ($lines as $line) {
            $newStateCandidate = $this->determineStateFromLine($line, $loopDeterminedState);
            if ($newStateCandidate !== $loopDeterminedState) {
                // Wir brauchen hier keinen $loopDeterminedPrevState, da wir den globalen $this->prevState nutzen.
                $loopDeterminedState = $newStateCandidate;
                $loopDeterminedLine = $line;
            }
        }
        
        // Aktualisiere den Zustand des Objekts, wenn sich der aus dem Log ermittelte Zustand unterscheidet.
        if ($initialObjectState !== $loopDeterminedState) {
            $this->prevState = $initialObjectState; // Der vorherige Zustand des Objekts
            $this->state = $loopDeterminedState;
            $this->lastRelevantLogLine = $loopDeterminedLine;
        } elseif (empty($this->lastRelevantLogLine) && !empty($loopDeterminedLine) && $this->state === $loopDeterminedState) {
            // Fall: Zustand hat sich nicht geändert, aber es gab eine relevante Zeile (z.B. bei erstem Durchlauf oder wenn Log gelöscht und neu mit gleichem Endzustand befüllt)
            $this->lastRelevantLogLine = $loopDeterminedLine;
        }
    }

    private function determineStateFromLine($line, $currentState) {
        if (stripos($line, 'squelch is OPEN') !== false) {
            return self::STATE_RX_ACTIVE;
        }
        elseif (stripos($line, 'squelch is CLOSED') !== false) {
            return self::STATE_IDLE;
        }
        elseif (strpos($line, 'MultiTx: Turning the transmitter ON') !== false) {
            return self::STATE_TX_ACTIVE;
        }
        elseif (strpos($line, 'MultiTx: Turning the transmitter OFF') !== false) {
            return self::STATE_IDLE;
        }
        return $currentState;
    }

    public function printCurrentState() { // Name geändert zu printCurrentState für Klarheit
        $colorClasses = [
            self::STATE_IDLE      => 'state-idle',
            self::STATE_RX_ACTIVE => 'state-rx',
            self::STATE_TX_ACTIVE => 'state-tx'
        ];

        $currentClassKey = $this->state ?: self::STATE_IDLE;
        $currentClass = $colorClasses[$currentClassKey] ?? 'state-idle';

        // Nur den aktuellen Zustand ausgeben
        echo "<span class='{$currentClass}'>" . htmlspecialchars($this->state ?: 'N/A') . "</span>";

        // Optionale Anzeige der auslösenden Logzeile (kann für Debugging nützlich bleiben)
        // if (!empty($this->lastRelevantLogLine)) {
        //     echo "<div class='debug-info'>Trigger: " . htmlspecialchars($this->lastRelevantLogLine) . "</div>";
        // }
    }
}

// --- Anwendung ---

$logFilePath = SVXLINK_LOG_FILE;
$fsm = new SvxLinkFSM($logFilePath);
$cacheInfo = "";

if (!file_exists($logFilePath)) {
    echo "<div class='error-message'><b>Logfile nicht gefunden:</b> " . htmlspecialchars($logFilePath) . "</div>";
    $_SESSION['svxlink_last_mtime'] = 0;
    $cacheInfo = "Log file not found, cache invalidated.";
} elseif (!is_readable($logFilePath)) {
    echo "<div class='error-message'><b>Logfile nicht lesbar:</b> " . htmlspecialchars($logFilePath) . " (Berechtigungen prüfen)</div>";
    $_SESSION['svxlink_last_mtime'] = 0;
    $cacheInfo = "Log file not readable, cache invalidated.";
} else {
    $currentMtime = filemtime($logFilePath);
    $processLogFile = true;

    if (isset($_SESSION['svxlink_last_mtime']) && $_SESSION['svxlink_last_mtime'] == $currentMtime && isset($_SESSION['svxlink_fsm_data'])) {
        if ($fsm->loadStateFromCache($_SESSION['svxlink_fsm_data'])) {
            $processLogFile = false;
            $cacheInfo = "Using cached data (log unchanged).";
        } else {
            $cacheInfo = "Cache data invalid, reprocessing.";
        }
    } else {
         $cacheInfo = "Log changed or no cache, processing file.";
    }

    if ($processLogFile) {
        $fsm->processLog();
        $_SESSION['svxlink_last_mtime'] = $currentMtime;
        $_SESSION['svxlink_fsm_data'] = $fsm->getStateForCache();
        if (empty($cacheInfo)) $cacheInfo = "Log processed and cached.";
    }
}

// Den aktuellen Zustand ausgeben
$fsm->printCurrentState(); // Aufruf der geänderten Methode

// Cache-Info ausgeben
echo "<div class='cache-info'>" . htmlspecialchars($cacheInfo) . "</div>";

echo "</body></html>";
?>