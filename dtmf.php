<?php
session_start();

// Initialisiere das DTMF-Display, wenn nicht vorhanden
if (!isset($_SESSION['dtmf_display'])) {
    $_SESSION['dtmf_display'] = '';
}

// Funktion zum Senden eines DTMF-Zeichens
function send_dtmf($dtmf_char) {
    $whoami = trim(shell_exec('whoami'));
    shell_exec("echo \"$dtmf_char\" > /tmp/dtmf_test_echo");
    $cmd = "echo \"$dtmf_char\" > /tmp/dtmf_svx";
    shell_exec($cmd);
    return true;
}

$redirectAfterInput = false;
$redirectImmediate = false;
$flashLCD = false;

// Verarbeite POST-Anfragen (Formulareingaben)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $map = [
        'button20' => '0', 'button21' => '1', 'button22' => '2', 'button23' => '3',
        'button24' => '4', 'button25' => '5', 'button26' => '6', 'button27' => '7',
        'button28' => '8', 'button29' => '9', 'button30' => '*', 'button31' => '#'
    ];

    foreach ($map as $key => $value) {
        if (isset($_POST[$key])) {
            send_dtmf($value);
            if ($value === '#') {
                $_SESSION['dtmf_display'] = '';
                $redirectImmediate = true;
                $flashLCD = true;
            } else {
                $_SESSION['dtmf_display'] .= $value;
                $redirectAfterInput = true;
            }
            break;
        }
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
    html, body {
      margin: 0;
      padding: 0;
      width: 800px;
      height: 480px;
      background-color: #000;
      color: #fff;
      font-family: Arial, sans-serif;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .lcd {
      position: relative;
      background-color: #222;
      border: 2px solid #0f0;
      border-radius: 6px;
      height: 75px;
      font-size: 36px;
      font-family: monospace;
      margin: 5px auto 10px auto;
      width: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      transition: background-color 0.2s ease-in-out;
    }
    .lcd.flash {
      background-color: #0f0 !important;
      color: #000;
    }
    .lcd.flash::before {
      content: "DTMF GESENDET";
      position: absolute;
      font-size: 28px;
      color: #000;
    }
    .dtmf-button {
      width: 70px;
      height: 70px;
      font-size: 20px;
      margin: 3px;
      border: none;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(to bottom, #337ab7, #265a88);
      color: white;
    }
    button[name="button30"], button[name="button31"] {
      background: linear-gradient(to bottom, #ff7f50, #ff4500);
    }
    .keypad {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-template-rows: repeat(4, auto);
      gap: 6px;
      justify-items: center;
      align-items: center;
    }
  </style>
  <script>
    let inactivityTimeout;

    function resetInactivityTimer() {
      clearTimeout(inactivityTimeout);
      inactivityTimeout = setTimeout(() => {
        window.location.href = 'index.php';
      }, 5000);
    }

    document.addEventListener('DOMContentLoaded', () => {
      ['mousedown', 'touchstart', 'keydown'].forEach(evt =>
        document.addEventListener(evt, resetInactivityTimer)
      );
      resetInactivityTimer();

      <?php if ($redirectImmediate): ?>
      // Zeige visuelles Feedback bei #
      const lcd = document.querySelector('.lcd');
      lcd.classList.add('flash');
      setTimeout(() => {
        window.location.href = 'index.php';
      }, 300);
      <?php elseif ($redirectAfterInput): ?>
      setTimeout(() => { window.location.href = 'index.php'; }, 5000);
      <?php endif; ?>
    });
  </script>
</head>
<body>
  <form method="post">
    <div class="lcd">
      <?php echo str_pad(substr($_SESSION['dtmf_display'], -16), 16, ' ', STR_PAD_LEFT); ?>
    </div>
    <div class="keypad">
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
  </form>
</body>
</html>
