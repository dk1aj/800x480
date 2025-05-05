<?php
// DTMF-Ausführung
foreach ($_POST as $key => $val) {
  if (preg_match('/^button(\d+)$/', $key, $matches)) {
    $btnNum = $matches[1];
    $constName = 'KEY' . $btnNum;
    if (defined($constName)) {
      $cmd = constant($constName)[1];
      if (!empty($cmd)) {
        // Sonderfall: direkter Shell-Exec (z. B. bei KEY8)
        if ($btnNum == 8) {
          exec($cmd, $output);
        } else {
          exec("echo '$cmd' > /tmp/dtmf_svx", $output);
        }
      }
    }
    echo "<meta http-equiv='refresh' content='0'>";
    exit;
  } 
}
?>

<form method="post">
  <div class="left-container">
    <?php
    // Liste aller Keys, die du nutzen möchtest
    $buttonKeys = [
      111, 112, 113, 1, 2, 3, 4, 5, 6, 7,
      11, 12, 13, 14, 15, 16
    ];

    foreach ($buttonKeys as $i) {
      $keyVar = 'KEY' . $i;
      if (defined($keyVar)) {
        $data = constant($keyVar);
        $label = $data[0] ?? "KEY$i";
        $cssClass = $data[2] ?? "touch-button";
        echo '<button class="touch-button ' . htmlspecialchars($cssClass) . '" name="button' . $i . '">' . htmlspecialchars($label) . '</button>' . PHP_EOL;
      }
    }
    ?>
  </div>
</form>
