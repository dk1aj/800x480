<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=400">
  <title>Touch UI 800x400</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      width: 800px;
      height: 400px;
      font-family: sans-serif;
      background-color: #f8f9fa;
      color: #212529;
    }

    .topbar {
      height: 40px;
      background-color: #343a40;
      color: white;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
    }

    .topbar h1 {
      font-size: 24px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      padding: 15px;
    }

    .tile {
      background-color: #ffffff;
      border: 2px solid #ced4da;
      border-radius: 12px;
      padding: 20px;
      font-size: 20px;
      text-align: center;
      touch-action: manipulation;
      transition: background 0.2s, box-shadow 0.2s;
      cursor: pointer;
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .tile:active {
      background-color: #e9ecef;
      box-shadow: inset 0 0 5px #adb5bd;
    }

    .feedback {
      text-align: center;
      font-size: 24px;
      margin-top: 20px;
      color: green;
    }
  </style>
</head>
<body>
  <header class="topbar">
    <h1>Steuerung der Kontrole ohne  </h1>
    <button title="Menü öffnen">☰</button>
  </header>

  <?php
    $feedback = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['licht_an'])) {
        shell_exec('sudo /home/pi/pkill-pi.sh');
        $feedback = 'Licht wurde eingeschaltet';
      }

      if (isset($_POST['licht_aus'])) {
        shell_exec('sudo /home/pi/licht-aus.sh');
        $feedback = 'Licht wurde ausgeschaltet';
      }

      if (isset($_POST['ventilator'])) {
        shell_exec('sudo /home/pi/ventilator.sh');
        $feedback = 'Ventilator aktiviert';
      }

      if (isset($_POST['einstellungen'])) {
        shell_exec('sudo /home/pi/settings.sh');
        $feedback = 'Einstellungen geöffnet';
      }

      // Weitere Skripte
      if (isset($_POST['backup'])) {
        shell_exec('sudo /home/pi/backup.sh');
        $feedback = 'Backup gestartet';
      }

      if (isset($_POST['sync'])) {
        shell_exec('sudo /home/pi/sync.sh');
        $feedback = 'Synchronisation läuft';
      }
    }

    if ($feedback !== '') {
      echo '<div class="feedback">' . htmlspecialchars($feedback) . '</div>';
    }
  ?>

  <form method="post">
    <main class="grid">
      <button class="tile" name="licht_an">Licht An</button>
      <button class="tile" name="licht_aus">Licht Aus</button>
      <button class="tile" name="ventilator">Ventilator</button>
      <button class="tile" name="einstellungen">Einstellungen</button>
      <button class="tile" name="backup">Backup</button>
      <button class="tile" name="sync">Sync</button>
    </main>
  </form>
</body>
</html>
