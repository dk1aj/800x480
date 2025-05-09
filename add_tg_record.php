<?php
// Path to the database file
$file_path = '/var/www/html/800x480/include/tgdb.dat';

// Load the database if it exists
if (file_exists($file_path)) {
    require_once $file_path;
} else {
    $tgdb_array = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tg_number = htmlspecialchars(trim($_POST['tg_number']));
    $tg_name = htmlspecialchars(trim($_POST['tg_name']));

    if (!is_numeric($tg_number) || empty($tg_name)) {
        $error_message = "TG Number must be a number and TG Name cannot be empty.";
    } else {
        if (array_key_exists($tg_number, $tgdb_array)) {
            $error_message = "This TG Number already exists.";
        } else {
            $tgdb_array[$tg_number] = $tg_name;

            $file_content = "<?php\n";
            $file_content .= "/* talkgroup / number alias database */\n";
            $file_content .= "\$tgdb_array = [\n";
            foreach ($tgdb_array as $key => $value) {
                $file_content .= "    '$key' => '$value',\n";
            }
            $file_content .= "];\n";
            $file_content .= "?>";

            if (file_put_contents($file_path, $file_content) === false) {
                $error_message = "An error occurred while saving the file!";
            } else {
                $success_message = "A new record has been added to the database!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=800, height=480, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Add TG Record</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 800px;
            height: 480px;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow: hidden;
            position: relative;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-top: 30px;
        }
        input[type="text"] {
            background-color: white;
            color: black;
            border: 1px solid #ddd;
            padding: 8px;
            width: 90%;
            margin: 6px 0;
            font-size: 18px;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            margin-top: 14px;
        }
        input[type="submit"], .back-button {
            background-color: #333;
            color: white;
            padding: 14px 28px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            text-decoration: none;
        }
        input[type="submit"]:hover, .back-button:hover {
            background-color: #555;
        }
        .message {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
    <script>
        let timeout;
        function resetTimer() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                window.location.href = 'index.php';
            }, 5000);
        }
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.ontouchstart = resetTimer;
        document.onclick = resetTimer;
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Add New TalkGroup Name</h2>
        <?php
        if (isset($error_message)) {
            echo "<p class='message' style='color: red;'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='message' style='color: green;'>$success_message</p>";
        }
        ?>
        <form action="add_tg_record.php" method="POST">
            <label for="tg_number"><b>TG Number</b>:</label><br>
            <input type="text" id="tg_number" name="tg_number" required><br>

            <label for="tg_name"><b>TG Name</b>:</label><br>
            <input type="text" id="tg_name" name="tg_name" required><br>

            <div class="button-group">
                <input type="submit" value="Add Record">
                <a class="back-button" href="index.php">Back</a>
            </div>
        </form>
    </div>
</body>
</html>
