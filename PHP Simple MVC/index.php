<?php 

spl_autoload_register(function($classname) {
	// Bestandsnaam bepalen
	$filename= 'classes/'.str_replace('\\', '/', $classname).'.php';
	
	// Als het bestand bestaat laadt het in
	if(file_exists($filename)) {
		include_once $filename;
	}
});

session_start();

$controller = new Controller();
$controller->processRequest();
$view = $controller->getView();
$view->getHTML();