<?php
// Path to the user database file
$file_path = '/var/www/html/480x320/include/userdb.dat';

// Load data from file
require_once $file_path;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $callsign = $_POST['callsign'];
    $info = $_POST['info'];
    $name = $_POST['name'];
    $qth = $_POST['qth'];

    // Check if the Callsign already exists in the array
    if (array_key_exists($callsign, $userdb_array)) {
        $error_message = "This Callsign already exists.";
    } else {
        // Add new record
        $userdb_array[$callsign] = "$info, $name, $qth";

        // Generate new file content
        $file_content = "<?php\n";
        $file_content .= "/* user alias database */\n";
        $file_content .= "\$userdb_array = [\n";
        foreach ($userdb_array as $key => $value) {
            $file_content .= "    '$key' => '$value',\n";
        }
        $file_content .= "];\n";
        $file_content .= "?>";

        // Save the updated array to the file
        if (file_put_contents($file_path, $file_content) === false) {
            $error_message = "Error saving the file!";
        } else {
            $success_message = "New record added to the database!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Record</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            text-align: center;
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
        input[type="submit"] {
            background-color: #333;
            color: white;
            padding: 14px 28px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            margin-top: 14px;
        }
        input[type="submit"]:hover {
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
            }, 15000); // 15 seconds
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
        <h2>Add New User Record</h2>
        <?php
        if (isset($error_message)) {
            echo "<p class='message' style='color: red;'>$error_message</p>";
        }
        if (isset($success_message)) {
            echo "<p class='message' style='color: green;'>$success_message</p>";
        }
        ?>
        <form action="add_user_record.php" method="POST">
            <label for="callsign"><b>Callsign</b>:</label><br>
            <input type="text" id="callsign" name="callsign" required><br>

            <label for="info"><b>Info</b>:</label><br>
            <input type="text" id="info" name="info" required><br>

            <label for="name"><b>Name</b>:</label><br>
            <input type="text" id="name" name="name" required><br>

            <label for="qth"><b>QTH</b>:</label><br>
            <input type="text" id="qth" name="qth" required><br>

            <input type="submit" value="Add Record">
        </form>
    </div>
    <div class="back-button-container">
    <?php
    $backTarget = 'index.php';
    include_once 'include/back_button.php'; 
    ?>
  </div> 

</body>
</html>
