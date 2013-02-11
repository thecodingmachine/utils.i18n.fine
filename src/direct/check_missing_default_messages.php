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

$missingDefaultKeys = array();

foreach ($instances as $instanceName) {
	$translationService = $moufManager->getInstance($instanceName);
	/* @var $translationService FinePHPArrayTranslationService */
	
	$translationService->loadAllMessages();
	
	// The array of messages by message, then by language:
	// array(message_key => array(language => message))
	
	$keys = $translationService->getAllKeys();
	
	foreach ($keys as $key) {
		$msgs = $translationService->getMessageForAllLanguages($key);
		if (!isset($msgs['default'])) {
			$missingDefaultKeys[$instanceName][] = $key; 
		}
	}
}

$jsonObj = array();

if (empty($missingDefaultKeys)) {
        $jsonObj['code'] = "ok";
        $jsonObj['html'] = "Default translation is available for all messages.";
} else {
        $jsonObj['code'] = "warn";
        $html = "";
        foreach ($missingDefaultKeys as $instanceName=>$missingKeys) {
	        $html .= "A default translation in '".$instanceName."' is missing for these messages: ";
	        foreach ($missingKeys as $missingDefaultKey) {
	        	$html .= "<a href='".ROOT_URL."mouf/editLabels/editLabel?key=".urlencode($missingDefaultKey)."&language=default&backto=".urlencode(ROOT_URL)."mouf/&msginstancename=".urlencode($instanceName)."'>".$missingDefaultKey."</a> ";
	        }
	        $html .= "<hr/>";
        }
        $jsonObj['html'] = $html;
}

echo json_encode($jsonObj);
exit;

?>