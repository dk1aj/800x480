<?php
function getFMNetName(string $configFilePath = '/etc/svxlink/svxlink.conf'): string
{
    if (!file_exists($configFilePath)) {
        return 'UnknownNet';
    }

    $config = parse_ini_file($configFilePath, true, INI_SCANNER_RAW);
    if (!$config || !isset($config['ReflectorLogic']['FMNET'])) {
        return 'UnknownNet';
    }

    return trim($config['ReflectorLogic']['FMNET']); 
}
?>
