<?php
use Mouf\MoufUtils;

use Mouf\MoufManager;

// This file purges all the caches of any instance implementing the CacheInterface interface.

// Disable output buffering
while (ob_get_level() != 0) {
	ob_end_clean();
}

ini_set('display_errors', 1);
// Add E_ERROR to error reporting if it is not already set
error_reporting(E_ERROR | error_reporting());

/*if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../mouf/Mouf.php';
	$mouf_base_path = ROOT_PATH;
	$selfEdit = false;
} else {
	require_once '../../mouf/Mouf.php';
	$mouf_base_path = ROOT_PATH."mouf/";
	$selfEdit = true;
}*/
require_once '../../../../../mouf/Mouf.php';

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
MoufUtils::checkRights();

$moufManager = MoufManager::getMoufManager();
$instances = $moufManager->findInstances("FinePHPArrayTranslationService");

$notFounds = array();

foreach ($instances as $instanceName) {
	$translationService = $moufManager->getInstance($instanceName);
	/* @var $translationService FinePHPArrayTranslationService */
	
	$path = $translationService->i18nMessagePath;
	
	if (!file_exists(ROOT_PATH.$path."message.php")) {
		$notFounds[$instanceName] = $path."message.php";
	}
}


$jsonObj = array();

if (!$instances) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "No instance of FinePHPArrayTranslationService. No validation required.";
} elseif (!$notFounds) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "Default translation file found in all implementations of FinePHPArrayTranslationService.";
} else {
        $jsonObj['code'] = "warn";
        $jsonObj['html'] = "Unable to find default translation file for instances: ".implode(", ", array_keys($notFounds)).".<br/>
        	You should create the following files:<br/><ul>";
        foreach ($notFounds as $instanceName => $filePath) {
        	$jsonObj['html'] .= "<li>".$filePath." <a href='".ROOT_URL."mouf/editLabels/addSupportedLanguage?name=".urlencode($instanceName)."&selfedit=false&language=default'>(create this file)</a></li>";
        }
        $jsonObj['html'] .= "</ul>";
}

echo json_encode($jsonObj);
exit;

?>