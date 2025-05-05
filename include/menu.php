<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
  html, body {
   margin: 0;
   padding: 0;
   height: 100%;
   font-family: sans-serif;
  }



  .button-row {
   display: flex;
   gap: 6px; /* Abstand zwischen Buttons */
  }

  .purple, .red {
   height: 65px;
   width: 180px;
   font-size: 36px;
   font-weight: bold;
   border: none;
   border-radius: 10px;
   cursor: pointer;
   transition: transform 0.1s ease, box-shadow 0.2s ease;
  }

  .purple {
   background-color: #6c5ce7;
   color: white;
  }

  .red {
   background-color: #d63031;
   color: yellow;
  }

  .purple:hover, .red:hover {
   transform: scale(1.03);
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  a {
   text-decoration: none;
  }
 </style>
</head>
<body>

<div class="button-row">
 <a href="dtmf.php"><button class="purple" name="button0">DTMF</button></a>
 <a href="tg.php"><button class="purple" name="button0">MEMO</button></a>
 <a href="svx.php"><button class="purple" name="button0">SvxLink</button></a>
 <a href="config.php"><button class="red" name="button0">Config</button></a>
</div>

</body>
</html>
