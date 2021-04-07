<?php
require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'system','sys.dbconnection.class.inc.php')));
require_once(implode(DIRECTORY_SEPARATOR, array(includePath,'system','sys.routing.class.inc.php')));

// Verifica caminho requisitado
if(baseURL=='/'){
    $url = substr($_SERVER['REQUEST_URI'], 1);
} else {
    $url = str_replace(baseURL, '', $_SERVER['REQUEST_URI']);
}

$routingSys = new RoutingSystem;

$routingSys->executeRoutingSystem($url);
?>