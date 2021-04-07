<?php
// Path Config
define('publicPath', 			realpath('./'));
define('includePath', 			realpath('./../include/'));
define('logsPath', 			    realpath('./../logs/'));

// URL Config
define('baseURL', 				'/');
define('publicLink',			'http://localhost');

// Security Config
define('useSecure', 			true);
define('unsecurePages', 		serialize(array('login','logout')));

// Admin Security Config
define('useSecureAdmin', 		true);
define('unsecureAdminPages', 	serialize(array('login','logout')));

// Defaults Config
define('defaultPage', 			'pedidos');
define('defaultFile', 			'index');
define('defaultExtension', 		'.php');
date_default_timezone_set("America/Sao_Paulo");

// Database Config
define('dbHost', 				'localhost');
define('dbUser', 				'root');
define('dbPass', 				'');
define('dbBase', 				'database');

//Page configs
define('loginPage',             implode(DIRECTORY_SEPARATOR, array(includePath,'pages','login')));
define('homePage',              implode('/', array(publicLink,'clientes')));
define('clientsPage',           implode('/', array(publicLink,'clientes')));
define('productsPage',          implode('/', array(publicLink,'produtos')));
define('pedidoPage',            implode('/', array(publicLink,'pedidos')));
define('usersPage',             implode('/', array(publicLink,'usuarios')));

define('pageTitle', 'Sistema');
?>