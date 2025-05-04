$pipe = '/tmp/dtmf_svx';
$result = file_put_contents($pipe, "5\n");
if ($result === false) {
    write_log("❌ Schreiben auf $pipe fehlgeschlagen");
} else {
    write_log("✅ Schreiben auf $pipe erfolgreich ($result Bytes)");
}
