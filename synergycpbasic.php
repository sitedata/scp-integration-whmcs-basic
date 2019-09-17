<?php

if (!defined('WHMCS')) {
    die('This file cannot be accessed directly.');
}

ini_set('display_errors', 'Off');
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);

require __DIR__.'/bootstrap/autoload.php';

use Scp\WhmcsBasic\App;
use Scp\WhmcsBasic\Whmcs;

/**
 * Define WHMCS global functions
 *
 * @param string $class
 */
function _synergycpbasic_map_class($class)
{
    foreach ($class::functions() as $name => $method) {
        $fullName = 'synergycpbasic_'.$name;
        eval('function '.$fullName.' (array $params)
        {
            return '.App::class.'::get($params)
                ->make("'.$class.'")
                ->'.$method.'();
        }');
    }
}

function _synergycpbasic_map_static_class($class)
{
   foreach ($class::staticFunctions() as $name => $method) {
       $fullName = 'synergycpbasic_'.$name;
       eval('function '.$fullName.' ()
       {
           return '.$class.'::'.$method.'();
       }');
   }
}

_synergycpbasic_map_class(Whmcs\WhmcsButtons::class);
_synergycpbasic_map_class(Whmcs\WhmcsConfig::class);
_synergycpbasic_map_class(Whmcs\WhmcsTemplates::class);
_synergycpbasic_map_static_class(Whmcs\Whmcs::class);
_synergycpbasic_map_static_class(Whmcs\WhmcsButtons::class);
