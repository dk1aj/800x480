<?php
define("KEY30", ["Taste 30", "DTMF30", ""]);
define("KEY31", ["Taste 31", "DTMF31", ""]);
define("KEY32", ["Taste 32", "DTMF32", ""]);
define("KEY33", ["Taste 33", "DTMF33", ""]);
define("KEY34", ["Taste 34", "DTMF34", ""]);
define("KEY35", ["Taste 35", "DTMF35", ""]);
define("KEY36", ["Taste 36", "DTMF36", ""]);
define("KEY37", ["Taste 37", "DTMF37", ""]);
define("KEY39", ["Taste 39", "DTMF39", ""]);
define("KEY41", ["Taste 41", "DTMF41", ""]);
define("KEY42", ["Taste 42", "DTMF42", ""]);
define("KEY43", ["Taste 43", "DTMF43", ""]);

$activeButton = null;
$activeKeyLabel = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 foreach ($_POST as $key => $val) {
  if (preg_match('/^button(\d+)$/', $key, $matches)) {
   $btnNum = $matches[1];
   $const = "KEY$btnNum";
   if (defined($const)) {
    $dtmf = constant($const)[1];
    file_put_contents("/tmp/dtmf_svx", $dtmf);
    $activeButton = $btnNum;
    $activeKeyLabel = constant($const)[0];
    header("Refresh:2"); // Zurück zur Übersicht nach 2 Sekunden
    break;
   }
  }
 }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>SvxDSI </title>
 <link rel="stylesheet" href="css/button_style_svx.css">
 <style>
   /* body {
     display: flex;
     align-items: center;
     justify-content: center;
     height: 100vh;
     margin: 0;
   } */

   body {
  display: flex;
  justify-content: center; /* nur horizontale Zentrierung */
  padding-top: 20px;        /* Abstand zum oberen Rand */
  margin: 0;
}
   .center-container {
     display: flex;
     flex-direction: column;
     align-items: center;
     justify-content: center;
     text-align: center;
   }
   .button-grid {
     display: flex;
     flex-wrap: wrap;
     gap: 10px;
     justify-content: center;
     align-items: center;
   }
 </style>
</head>
<body>
 <form method="post">
 <?php if ($activeButton !== null): ?>
   <div class="center-container">
    <button class="touch-button touch-button<?= (($activeButton % 10) + 1) ?> centered-button" name="button<?= $activeButton ?>">
     <?= htmlspecialchars($activeKeyLabel) ?>
    </button>
    <p style="margin-top: 20px; font-size: 40px; font-weight: bold; color: green;">
     Taste <?= htmlspecialchars($activeKeyLabel) ?> wurde gesendet
    </p>
  </div>
 <?php else: ?>
  <div class="button-grid">
   <?php for ($i = 30; $i <= 43; $i++) {
    if ($i == 38 || $i == 40) continue;
    $key = constant("KEY$i")[0];
    echo "<button class='touch-button touch-button" . (($i % 10) + 1) . " button-wide' name='button$i'>$key</button>";
   } ?>
  </div>
 <?php endif; ?>
 </form>
</body>
</html>
