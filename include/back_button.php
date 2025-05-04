<!-- BACK Button (wiederverwendbar) -->
<?php
if (!isset($backTarget)) $backTarget = 'index.php';
?>
<link rel="stylesheet" href="css/button_style_svx.css">
<a href="<?= htmlspecialchars($backTarget) ?>" style="text-decoration: none;">
    <button name="button0" class="touch-button">← BACK</button>
</a>
