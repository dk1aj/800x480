<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="refresh" content="3;url=index.php" />
  <title>Touch UI 800x400</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
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
    background-color: #121212;
    color: #f1f1f1;
  }

  .topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 25px; /* reduzierte Höhe */
    background-color: #1e1e1e;
    color: #fff;
    padding: 0 10px;
    font-size: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.4);
  }

  .topbar h1 {
    font-size: 1rem; /* verkleinert für 25px Höhe */
    margin: 0;
  }

  .topbar button {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    padding: 20px;
  }

  .tile {
    background-color: #1f1f1f;
    border: none;
    border-radius: 16px;
    font-size: 20px;
    padding: 16px;
    height: 120px;
    color: #f1f1f1;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    transition: transform 0.1s ease-in-out, background-color 0.3s;
    touch-action: manipulation;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    user-select: none;
  }

  .tile i {
    font-size: 32px;
    margin-bottom: 6px;
  }

  .tile:hover {
    background-color: #2a2a2a;
  }

  .tile:active {
    transform: scale(0.97);
    background-color: #333;
  }

  .feedback {
    text-align: center;
    font-size: 24px;
    margin-top: 20px;
    color: #4caf50;
  }
</style>

</head>
<body>
  <header class="topbar">
    <h1>Steuerung</h1>
    <button title="Menü öffnen"><i class="material-icons-outlined">menu</i></button>
  </header>

  <?php
    $feedback = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['licht_an'])) {
            shell_exec('sudo /home/pi/pkill-pi.sh');
            $feedback = 'Chromium reset';
        } 
        elseif (isset($_POST['Reflector_1'])) {
            shell_exec('sudo /home/pi/reflector1.sh');
            $feedback = 'switch to Reflector 1';
        } 
        elseif (isset($_POST['Reflector_2'])) {
            shell_exec('sudo /home/pi/reflector2.sh');
            $feedback = 'switch to Reflector 2';
        } 
        elseif (isset($_POST['Reflector_3'])) {
            shell_exec('sudo /home/pi/reflector3.sh');
            $feedback = 'switch to Reflector 3';
        } 
        elseif (isset($_POST['Reflector_4'])) {
            shell_exec('sudo /home/pi/reflector4.sh');
            $feedback = 'switch to Reflector 4';
        } 
        elseif (isset($_POST['Reflector_5'])) {
            shell_exec('sudo /home/pi/reflector5.sh');
            $feedback = 'switch to Reflector5';
        }

        if ($feedback !== '') {
          echo '<div class="feedback">' . htmlspecialchars($feedback) . '</div>';
          echo '<script>
            setTimeout(() => {
            window.location.href = "index.php";
              }, 3000);
          </script>';
        }
          
    }
  ?>

  <form method="post">
    <main class="grid">
      <button class="tile" type="submit" name="licht_an">
        <i class="material-icons-outlined">sync</i>
        Chromium reset
      </button>
      <button class="tile" type="submit" name="Reflector_1">
        <i class="material-icons-outlined">settings</i>
        Reflector 1
      </button>
      <button class="tile" type="submit" name="Reflector_2">
        <i class="material-icons-outlined">settings</i>
        Reflector 2
      </button>
      <button class="tile" type="submit" name="Reflector_3">
        <i class="material-icons-outlined">settings</i>
        Reflector 3
      </button>
      <button class="tile" type="submit" name="Reflector_4">
        <i class="material-icons-outlined">settings</i>
        Reflector 4
      </button>
      <button class="tile" type="submit" name="Reflector_5">
        <i class="material-icons-outlined">sync</i>
        Reflector 5
      </button>
    </main>
  </form>
</body>
</html>
