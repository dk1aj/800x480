<?php
define('ALLOW_LOGIC_CHANGES', false);
include_once __DIR__.'/config.php';         
include_once __DIR__.'/tools.php';        
include_once __DIR__.'/functions.php';    
include_once __DIR__.'/tgdb.dat';    
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>SvxLink Übersicht</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #0f172a;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1e293b;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            margin: 0;
        }
        th, td {
            padding: 8px 12px; /* Wieder sichtbarer Abstand */
            text-align: left;
            font-size: 15px;   /* Etwas größer wieder */
        }
        th {
            background-color: #334155;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:nth-child(even) {
            background-color: #1e293b;
        }
        tr:hover {
            background-color: #475569;
        }
        a {
            color: #38bdf8;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .tg-number {
            color: #f97316;
            font-weight: bold;
        }
        .tg-name {
            font-weight: bold;
            color: #94a3b8;
        }
        img {
            vertical-align: middle;
            height: 18px;
        }
    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Time (<?php echo date('T')?>)</th>
            <th>Callsign</th>
            <th>TG #</th>
            <th>Name TG</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i = 0; $i <= 9; $i++) { 
            if (isset($lastHeard[$i])) {
                $listElem = $lastHeard[$i];
                if ($listElem[1]) {
                    $local_time = strftime("%H:%M:%S %d %b", strtotime($listElem[0]));
                    echo "<tr>";
                    echo "<td>$local_time</td>";

                    if ($listElem[3] == "ON") {
                        $tximg = "<img src='images/tx.gif' alt='TX'>";
                    } else {
                        $tximg = "";
                    }

                    $call = $listElem[1];
                    $calls = substr($call, -2);
                    $calls2 = substr($call, 0, 3);
                    $calls3 = substr($call, -3);

                    if ((!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $call) || $calls2 == "XLX" || $calls2 == "YSF") && ($calls != "-L" && $calls != "-P" && $calls != "-M" && $calls3 != "-L2" && $calls3 != "-L3" && $calls3 != "-ND" && $calls3 != "-GW")) {
                        echo "<td><b>$call</b> $tximg</td>";
                    } else {
                        if ($calls == "-L" || $calls == "-P" || $calls == "-M") {
                            $call = substr($call, 0, -2);
                        }
                        if ($calls3 == "-ND" || $calls3 == "-GW" || $calls3 == "-L2" || $calls3 == "-L3") {
                            $call = substr($call, 0, -3);
                        }
                        echo "<td><a href='https://www.qrz.com/db/$call' target='_blank'><b>$listElem[1]</b></a> $tximg</td>";
                    }

                    echo "<td class='tg-number'>$listElem[2]</td>";

                    $tgname = substr($listElem[2], 3);
                    $name = $tgdb_array[$tgname] ?? "------";
                    echo "<td class='tg-name'>$name</td>";

                    echo "</tr>";
                }
            }
        }
        ?>
    </tbody>
</table>

</body>
</html>
