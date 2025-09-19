<?php
// div_links.php - final stabile Version ohne Iframe, alle Seiten funktionieren
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Linksammlung</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="refresh" content="5;url=index.php"> 
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 800px;
            height: 400px;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
        }
        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin: 5px 0 10px 0;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            padding: 10px;
        }
        .link-button {
            width: 180px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: #03DAC6;
            font-size: 18px;
            border: 2px solid #03DAC6;
            border-radius: 10px;
            background-color: #1E1E1E;
            box-sizing: border-box;
            cursor: pointer;
        }
        .link-button:active {
            background-color: #03DAC6;
            color: #000;
        }
        .back-button {
            position: fixed;
            bottom: 50px;
            right: 50px;
            z-index: 100;
            padding: 12px 20px;
            font-size: 20px;
            background-color: rgba(223, 225, 226, 0.7);
            color: #000;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            backdrop-filter: blur(4px);
        }
        .back-button:active {
            background-color: rgba(1, 135, 134, 0.7);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Externe Links</h1>
    <div class="grid">
        <a class="link-button" href="https://www.google.com" target="_blank">Google</a>
        <a class="link-button" href="https://www.wikipedia.org" target="_blank">Wikipedia</a>
        <a class="link-button" href="https://www.stackoverflow.com" target="_blank">Stack Overflow</a>
        <a class="link-button" href="https://www.github.com" target="_blank">GitHub</a>
        <a class="link-button" href="https://www.heise.de" target="_blank">Heise</a>
        <a class="link-button" href="https://www.spiegel.de" target="_blank">Spiegel</a>
        <a class="link-button" href="https://www.tagesschau.de" target="_blank">Tagesschau</a>
        <a class="link-button" href="https://dashboard.fm-funknetz.de/" target="_blank">FM-Funknetz</a>
    </div>
</div>

<button class="back-button" onclick="history.back()">Zurück</button>

</body>
</html>
