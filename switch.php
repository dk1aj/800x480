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
  background: linear-gradient(145deg,rgb(122, 121, 121), #1a1a1a);
  border: none;
  border-radius: 16px;
  font-size: 19px;
  padding: 15px;
  height: 114px;
  color: #f1f1f1;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
  transition: transform 0.1s ease-in-out, background 0.3s;
  touch-action: manipulation;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  user-select: none;
}

.tile:hover {
  background: linear-gradient(145deg, #3a3a3a, #2a2a2a);
}

.tile:active {
  transform: scale(0.97);
  background: linear-gradient(145deg, #444, #2b2b2b);
}


    .tile i {
      font-size: 30px;
      margin-bottom: 6px;
    }

    .feedback {
      position: fixed;
      width: 800px;
      height: 480px;
      top: 0;
      left: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 48px;
      padding: 16px 32px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(109, 106, 106, 0.6);
      background-color: rgb(0, 0, 0);
      text-align: center;
      color: #fff;
      word-wrap: break-word;
    }

    .feedback.success {
      color: rgb(168, 175, 76);
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
  include_once __DIR__ . '/include/get_fmnet.php';
  $fmnet = getFMNetName();
  $feedback = '';
  $feedbackClass = 'success';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['licht_an'])) {
          shell_exec('sudo /home/pi/pkill-pi.sh');
          $feedback = "Chromium on DSI reset";
          $feedbackClass = 'error';
      } elseif (isset($_POST['Reflector_1'])) {
          shell_exec('sudo /home/pi/reflector1.sh');
          $fmnet = getFMNetName();
          $feedback = "switch to 1 $fmnet";
      } elseif (isset($_POST['Reflector_2'])) {
          shell_exec('sudo /home/pi/reflector2.sh');
          $fmnet = getFMNetName();
          $feedback = "switch to 2 $fmnet";
      } elseif (isset($_POST['Reflector_3'])) {
          shell_exec('sudo /home/pi/reflector3.sh');
          $fmnet = getFMNetName();          
          $feedback = "switch to 3 $fmnet";
      } elseif (isset($_POST['Reflector_4'])) {
          shell_exec('sudo /home/pi/reflector4.sh');
          $fmnet = getFMNetName();
          $feedback = "switch to 4 $fmnet";
      } elseif (isset($_POST['Reflector_5'])) {
          shell_exec('sudo /home/pi/reflector5.sh');
          $fmnet = getFMNetName();
          $feedback = "switch to 5 $fmnet";
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
          $fmnet = '';
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
