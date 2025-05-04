<?php
$logfile = __DIR__ . '/dtmf.log';
$timestamp = date('d-m-Y H:i:s');
$message = "[$timestamp] Test: Logeintrag\n";

$result = file_put_contents($logfile, $message, FILE_APPEND);

echo "<h1>Log-Test</h1>";
echo "<p>Versuche zu schreiben nach:</p>";
echo "<pre>$logfile</pre>";

if ($result === false) {
    echo "<p style='color: red;'>❌ Schreiben fehlgeschlagen!</p>";
} else {
    echo "<p style='color: lime;'>✅ Schreiben erfolgreich!</p>";
}
?>
