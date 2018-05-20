<?php

$app->bind("/",function(){
	$data = [];

	return $this->render($_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Home.php with " . $_SERVER["DOCUMENT_ROOT"] . "/library/Router/Views/Layout.php");
});