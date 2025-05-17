<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>SvxDSI Streamdeck Touch UI</title>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      background-color: #121212;
      color: #fff;
      font-family: sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .streamdeck {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      grid-template-rows: repeat(2, 1fr);
      gap: 12px;
      width: 100%;
      max-width: 800px;
      aspect-ratio: 2 / 1;
      padding: 12px;
    }
    .button {
        background-color: #1e1e1e;
        border: 2px solid #FFFFFF;
        border-radius: 20px;
        font-size: 1.4rem;
        display: flex;
        justify-content: center;
        align-items: center;
        user-select: none;
        touch-action: manipulation;
        transition: all 0.2s ease;
    }
    .button:active {
      background-color: #444;
      transform: scale(0.98);
    }
  </style>
</head>
<body>
  <div class="streamdeck">
    <div class="button" onclick="handleClick(1)">Taste 1</div>
    <div class="button" onclick="handleClick(2)">Taste 2</div>
    <div class="button" onclick="handleClick(3)">Taste 3</div>
    <div class="button" onclick="handleClick(4)">Taste 4</div>
    <div class="button" onclick="handleClick(5)">Taste 5</div>
    <div class="button" onclick="handleClick(6)">Taste 6</div>
    <div class="button" onclick="handleClick(7)">Taste 7</div>
    <div class="button" onclick="handleClick(8)">Taste 8</div>
    <div class="button" onclick="handleClick(9)">Taste 9</div>
    <div class="button" onclick="handleClick(10)">Taste 10</div>
  </div>

  <script>
  function handleClick(buttonNumber) {
    alert(`Taste ${buttonNumber} wurde gedrückt`);
    
    setTimeout(() => {
      window.location.href = 'index.php';
    }, 3000); // 3 Sekunden warten
  }
</script>

</body>
</html>
