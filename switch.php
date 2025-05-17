<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta http-equiv="refresh" content="3;url=index.php" />
 <title>SSvxDSI SWITCH</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html, body {
      width: 800px;
      height: 480px;
      margin: 0;
      padding: 0;
      background-color: #121212;
      overflow: hidden;
      font-family: 'Roboto', sans-serif;
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
      font-size: 19px;
      padding: 15px;
      height: 114px;
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
      font-size: 30px;
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
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 84px;
    padding: 16px 32px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(109, 106, 106, 0.6);
    background-color: rgb(0, 0, 0);
    text-align: center;
  }


    .feedback.success {
      color:rgb(168, 175, 76);
    }

    .feedback.error {
      color: #f44336;
    }

    .hidden {
      display: none !important;
    }
  </style>
</head>
<body>

  <?php
    $feedback = '';
    $feedbackClass = 'success';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['licht_an'])) {
            shell_exec('sudo /home/pi/pkill-pi.sh');
            $feedback = 'Chromium reset';
            $feedbackClass = 'error';
        } elseif (isset($_POST['Reflector_1'])) {
            shell_exec('sudo /home/pi/reflector1.sh');
            $feedback = 'switch to Reflector 1';
        } elseif (isset($_POST['Reflector_2'])) {
            shell_exec('sudo /home/pi/reflector2.sh');
            $feedback = 'switch to Reflector 2';
        } elseif (isset($_POST['Reflector_3'])) {
            shell_exec('sudo /home/pi/reflector3.sh');
            $feedback = 'switch to Reflector 3';
        } elseif (isset($_POST['Reflector_4'])) {
            shell_exec('sudo /home/pi/reflector4.sh');
            $feedback = 'switch to Reflector 4';
        } elseif (isset($_POST['Reflector_5'])) {
            shell_exec('sudo /home/pi/reflector5.sh');
            $feedback = 'switch to Reflector 5';
        }

        if ($feedback !== '') {
            echo '<div class="feedback ' . $feedbackClass . '">' . htmlspecialchars($feedback) . '</div>';
            echo '<script>
              window.addEventListener("DOMContentLoaded", () => {
                document.querySelector(".grid").classList.add("hidden");
                setTimeout(() => {
                  window.location.href = "index.php";
                }, 3000);
              });
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
