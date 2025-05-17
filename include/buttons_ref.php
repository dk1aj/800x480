<?php
// Fehleranzeige aktivieren
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['button100'])) {
        shell_exec('sudo /home/pi/./reflector1.sh');
    }
    if (isset($_POST['button101'])) {
        shell_exec('sudo /home/pi/./reflector2.sh');
    }
    if (isset($_POST['button102'])) {
        shell_exec('sudo /home/pi/./reflector3.sh');
    }
    if (isset($_POST['button103'])) {
        shell_exec('sudo /home/pi/./reflector4.sh');
    }
    if (isset($_POST['button104'])) {
        shell_exec('sudo /home/pi/./reflector5.sh');
    }
 // Nach Buttondruck zu status.php springen
    header("Location: index.php");
    exit;
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
 <title>SvxDSI </title>
    <link rel="stylesheet" href="css/button_style_svx.css">
</head>
<body>
    <form method="post">
        <div class="reflector-buttons" style="margin-top: 20px; text-align:center;">
            <button class="touch-button reflector"  style="height:50px; width:450px; font-size:24px;" name="button100">Reflector #1</button><br>
            <button class="touch-button reflector"  style="height:50px; width:450px; font-size:24px;" name="button101">Reflector #2</button><br>
            <button class="touch-button reflector"  style="height:50px; width:450px; font-size:24px;" name="button102">Reflector #3</button><br>
            <button class="touch-button reflector"  style="height:50px; width:450px; font-size:24px;" name="button103">Reflector #4</button><br>
            <button class="touch-button reflector"  style="height:50px; width:450px; font-size:24px;" name="button104">Reflector #5</button><br>
        </div>
    </form>
</body> 
</html>
