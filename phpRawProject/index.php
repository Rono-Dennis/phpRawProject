<?php
	session_unset();
	require_once  'controller/addressBookController.php';		
    $controller = new addressBookController();	 
    $controller->mvcHandler();
?>