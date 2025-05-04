
<?php
 if(array_key_exists('button1', $_POST)) {
        $exec= "echo '" . KEY1[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }

 if(array_key_exists('button40', $_POST)) {
        $exec= "echo '" . KEY40[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button43', $_POST)) {
        $exec= "echo '" . KEY43[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }

if (SHOWPTT=="TRUE") {

 if(array_key_exists('button9', $_POST)) {
        $exec="".KEY9[1]."";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button10', $_POST)) {
        $exec="".KEY10[1]."";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 }
?>    

<form method="post">
    <p>
        <center>
        <button class=<?php echo KEY40[2] ?> style="height: 60px; width: 125px;font-size:16px;" button name="button40"><?php echo KEY40[0] ?></button>
        <button class=<?php echo KEY43[2] ?> style="height: 60px; width: 125px;font-size:16px;" button name="button43"><?php echo KEY43[0] ?></button><br>
   </center>
</form>
</div>
<?php
?>
