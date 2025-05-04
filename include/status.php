<?php
include_once __DIR__.'/config.php';         
include_once __DIR__.'/tools.php';        
include_once __DIR__.'/functions.php';
?>

<!-- <div style="790px;"><span style="font-weight: bold;font-size:38px;"></span></div> -->

<?php

if (isProcessRunning('svxlink')) 
{
    echo "<h2 style=\"font-size:36px; color:#333; margin-bottom:10px;\">STATUS PAGE</h2>\n";
    echo "<table style=\"margin-top:0px;margin-bottom:0px;\">\n";

    $svxConfigFile = '/etc/svxlink/svxlink.conf';
    if (fopen($svxConfigFile, 'r')) 
    {
        $svxconfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
    }

    //  Zeige die Logiken an
    foreach ($logics as $key) 
    {
        // echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold;\">".$key."</span></td></tr>";
        echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold; font-size:36px;\">".$key."</span></td></tr>";

    }

    echo "</table>\n";

    //  Zeige Informationen zum Reflektor an
    $svxrstatus = getSVXRstatus();
    $netdefault = $svxconfig['ReflectorLogic']['FMNET'];

    // echo "<table style=\"margin-top:0px;margin-bottom:0px;\">\n";
    // echo "<tr><th width=25% style=\"color: white;\">Reflector</th><td style=\"background: #000000;color: white;font-weight: bold;\">".$netdefault." - <span style=\"color: green;\">".$svxrstatus."</span></td></tr>\n";
    // echo "</table>";
    echo "<table style=\"margin-top:0px;margin-bottom:0px; font-size:36px;\">\n";
    echo "<tr><th width=25% style=\"color: white;\">Reflector</th><td style=\"background:rgb(0, 0, 0);color: white;font-weight: bold;\"><span style='font-size:36px;'>".$netdefault." - <span style=\"color: green;\">".$svxrstatus."</span></span></td></tr>\n";
    echo "</table>";


    // Zeige die Module an
    echo "<table style=\"margin-top:0px;margin-bottom:0px; display: inline-table;\"><tr><th width=25%><span style=\"font-size:34px;\">Modules</span></th>";
    $modules = explode(",", str_replace('Module', '', $svxconfig['SimplexLogic']['MODULES']));
    $modecho = "False";

    if ($modules != "") 
    {
        define("SVXMODULES", $modules);
        $admodules = getActiveModules();
        
        foreach ($modules as $key) 
        {
            if ($admodules[$key] == "On") 
            {
                $activemod = "<td style=\"background:MediumSeaGreen;color:#464646;font-weight:bold;font-size:20px;padding:5px 10px;\">";
            } 
            else 
            {
                $activemod = "<td style=\"background:#000000;color:#b5651d;font-weight:bold;font-size:28px;padding:5px 10px;\">";
            }
        
            echo $activemod . htmlspecialchars($key) . "</td>";  // geschlossenes <td>, HTML-Escaping
        
            if ($key == "EchoLink") 
            {
                $modecho = "True";
            }
        }
              
        echo "</tr>\n";
    } 
    else 
    {
        echo "<tr><td style=\"background: #ffffed;\"><span style=\"color:#b0b0b0; font-size:36px;\"><b>No Modules</b></span></td></tr>";
    }

    echo "</table>\n";
} 
else 
{
    echo "<span style=\"color:red;font-size:80px;font-weight: bold;\">SvxLink<br>is<br> not <br>running</span>";
}
?>
