<?php
session_start();

// Logging ins Webverzeichnis
function write_log($message) {
    $logfile = __DIR__ . '/dtmf.log';
    $timestamp = date('d-m-Y H:i:s');
    file_put_contents($logfile, "[$timestamp] $message\n", FILE_APPEND);
}

// DTMF-Display initialisieren
if (!isset($_SESSION['dtmf_display'])) {
    $_SESSION['dtmf_display'] = '';
}

$redirectAfterHash = false;

// Zeichen einzeln senden (PTY-kompatibel, kein file_exists, kein sudo)
// function send_dtmf($dtmf_char) {
//     if (!in_array($dtmf_char, ['0','1','2','3','4','5','6','7','8','9','*','#'])) {
//         write_log("❌ Ungültiges DTMF-Zeichen: '$dtmf_char'");
//         return false;
//     }

//     $char = addcslashes($dtmf_char, "'\\"); // sichere Übergabe
//     $cmd = "sh -c 'echo \"$char\" > /tmp/dtmf_svx'";
//     exec($cmd, $output, $result);

//     if ($result === 0) {
//         write_log("✅ DTMF gesendet: '$dtmf_char'");
//         return true;
//     } else {
//         write_log("❌ Fehler beim Senden von '$dtmf_char' ($result)");
//         return false;
//     }
// }
function send_dtmf($dtmf_char) {
  $logfile = __DIR__ . '/dtmf.log';

  // Testlog: Wer bin ich?
  $whoami = trim(shell_exec('whoami'));
  file_put_contents($logfile, "[TEST] whoami = $whoami\n", FILE_APPEND);

  // Schreibe rein, was wir ausführen
  $cmd = "echo \"$dtmf_char\" > /tmp/dtmf_svx";
  file_put_contents($logfile, "[TEST] CMD = $cmd\n", FILE_APPEND);

  // tatsächlicher Aufruf – aber in eine temporäre Datei zur Kontrolle
  shell_exec("echo \"$dtmf_char\" > /tmp/dtmf_test_echo");

  // Versuch: direkt auf die echte Pipe
  shell_exec($cmd);

  file_put_contents($logfile, "[TEST] shell_exec beendet\n", FILE_APPEND);
  return true;
}




// Tasteneingabe verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $map = [
        'button20' => '0',
        'button21' => '1',
        'button22' => '2',
        'button23' => '3',
        'button24' => '4',
        'button25' => '5',
        'button26' => '6',
        'button27' => '7',
        'button28' => '8',
        'button29' => '9',
        'button30' => '*',
        'button31' => '#'
    ];

    foreach ($map as $key => $value) {
        if (isset($_POST[$key])) {
            write_log("Taste gedrückt: $value");
            send_dtmf($value);
            if ($value === '#') {
                $_SESSION['dtmf_display'] = '';
                $redirectAfterHash = true;
            } else {
                $_SESSION['dtmf_display'] .= $value;
            }
        }
    }

    if (isset($_POST['buttonCLR'])) {
        $_SESSION['dtmf_display'] = '';
        write_log("DTMF-Display zurückgesetzt durch CLR");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>DTMF Pad</title>
  <style>
    body {
      background-color: #000;
      color: #fff;
      font-family: Arial, sans-serif;
      text-align: center;
      max-width: 480px;
      margin: 0 auto;
      padding-top: 10px;
    }
    .lcd {
      background-color: #222;
      border: 2px solid #0f0;
      border-radius: 6px;
      height: 72px;
      line-height: 72px;
      font-size: 32px;
      font-family: monospace;
      margin: 10px auto 20px auto;
      width: 490px;
      transition: all 0.2s ease-in-out;
    }
    .dtmf-button, .clr-button {
      width: 75px;
      height: 75px;
      font-size: 22px;
      margin: 2px;
      border: none;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: background-color 0.1s ease;
    }
    .dtmf-button {
      background: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);
      color: white;
    }
    button[name="button30"], button[name="button31"] {
      background: linear-gradient(to bottom, #ff7f50, #ff4500);
    }
    .dtmf-button:active {
      background: #00ff00 !important;
      color: black !important;
    }
    .clr-button:active {
      background: #ffff00 !important;
      color: black !important;
    }
    .clr-button {
      background: linear-gradient(to bottom, #d9534f 0%, #c9302c 100%);
      color: white;
      position: absolute;
      top: 72%;
      left: calc(50% + 140px);
    }
    .keypad-grid {
      display: grid;
      grid-template-columns: repeat(3, 90px);
      grid-gap: 4px;
      justify-content: center;
      margin: 0 auto;
    }
  </style>
  <?php if ($redirectAfterHash): ?>
    <script>window.location.href = 'index.php';</script>
  <?php else: ?>
    <script>
      setTimeout(() => { window.location.href = 'index.php'; }, 5000);
    </script>
  <?php endif; ?>
</head>
<body>
  <div class="lcd">
    <?php echo str_pad(substr($_SESSION['dtmf_display'], -16), 16, ' ', STR_PAD_LEFT); ?>
  </div>

  <form method="post">
    <div class="keypad-grid">
      <button class="dtmf-button" type="submit" name="button21">1</button>
      <button class="dtmf-button" type="submit" name="button22">2</button>
      <button class="dtmf-button" type="submit" name="button23">3</button>
      <button class="dtmf-button" type="submit" name="button24">4</button>
      <button class="dtmf-button" type="submit" name="button25">5</button>
      <button class="dtmf-button" type="submit" name="button26">6</button>
      <button class="dtmf-button" type="submit" name="button27">7</button>
      <button class="dtmf-button" type="submit" name="button28">8</button>
      <button class="dtmf-button" type="submit" name="button29">9</button>
      <button class="dtmf-button" type="submit" name="button30">*</button>
      <button class="dtmf-button" type="submit" name="button20">0</button>
      <button class="dtmf-button" type="submit" name="button31">#</button>
    </div>
    <button class="clr-button" type="submit" name="buttonCLR">CLR</button>
  </form>
</body>
</html>
