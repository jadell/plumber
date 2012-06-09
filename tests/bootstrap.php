<?php
chdir(__DIR__.'/..');
error_reporting(-1);
ini_set('display_errors', 1);

require('PipeTestCase.php');

spl_autoload_register(function ($sClass) {
	$sLibPath = __DIR__.'/../lib/';
	$sClassFile = str_replace('\\',DIRECTORY_SEPARATOR,$sClass).'.php';
	$sClassPath = $sLibPath.$sClassFile;

	if (file_exists($sClassPath)) {
		require($sClassPath);
	}
});

