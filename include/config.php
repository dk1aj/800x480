<?php
// Report all errors except E_NOTICE
// error_reporting(E_ALL & ~E_NOTICE);
// disable all

error_reporting(0);

// Your callsign
define("CALLSIGN", "DK1AJ");

require_once 'parse_svxconf.php';
// set SVXReflector API URL from svxlink.conf
define("URLSVXRAPI", $refApi);
//
define("SVXLOGPATH", "/var/log");
define("SVXLOGPREFIX", "svxlink");
define("TGNAMEPATH", "/etc/svxlink");
//
//
// define where is located menu wit buttons TOP or BOTTOM
define("MENUBUTTON", "BOTTOM");
//
// Button keys define: description button, DTMF command or command, color of button
//
// DTMF keys
// syntax: 'KEY number,'Description','DTMF code','color button' 
//
//////////  MEMO  ///////////////////////////////////////////////////////////////////////
define("KEY111", array('TG262','91262#','green'));
define("KEY112", array('Celle local','9126235#','green'));
define("KEY113", array('TG 226','91226#','green'));
define("KEY1", array('TG 20','9120#','blue'));
define("KEY2", array('TG 1337','911337#','blue'));
define("KEY3", array('TG 1338','911338#','blue'));
define("KEY4", array('TG3 ','917#','orange'));
define("KEY5", array('TG 4','918#','orange'));
define("KEY6", array('TG5','919#','orange'));
define("KEY7", array('TG10','9110#','purple'));
define("KEY11", array('TG 3','913#','purple'));
define("KEY12", array('Blitz 0','910#','purple'));
define("KEY13", array('BLITZ 20','9120#','red')); 
define("KEY14", array('YSF','9131019#','red'));
define("KEY15", array('No TG','*910#','red'));

///////////SvxLink menu //////////////////////////////////////////////////////////////////////
define("KEY30", array('Parrot ON 1#','1#','orange'));
define("KEY31", array('Parrot OFF ##','##','orange'));
define("KEY32", array('TG 20','*9120#','orange'));
define("KEY33", array('ECHO ON','##','purple'));
define("KEY34", array('ECHO OFF','*#','purple'));
define("KEY35", array('1337','*911337#','purple'));
define("KEY36", array('1338','*911338#','purple'));
define("KEY37", array('TG226','*91226#','purple'));
define("KEY38", array('Ident','*#','purple'));
define("KEY39", array('Unlink TG','*91#','purple'));
define("KEY41", array('TG 262','*91262#','red'));
define("KEY42", array('TG 0','910#','red'));
define("KEY43", array('Ident','*#','red'));

/////////////////////////////////////////////////   Free Radio Network //////////////////
define("KEY40", array('Connect','7#','green'));

//
// command "shutdown now" 
//define("KEY8", array('POWER OFF','sudo poweroff','red'));
//
// Set SHOWPTT to TRUE if you want use microphone connected
// to sound card and use buttons on dashboard PTT ON & PTT OFF
// Set SHOWPTT to FALSE to disable display PTT buttons
// In most cases you can switch to FALSE
define("SHOWPTT","TRUE");
//
define("KEY90", array('PTT ON','echo "O" >/tmp/SQL','orange'));
define("KEY91", array('PTT OFF','echo "Z" >/tmp/SQL','orange'));
//
?>
