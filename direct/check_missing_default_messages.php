<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// This file validates that there are no missing labels in the default labels.
// If not, an alert is raised.

if (!isset($_REQUEST["selfedit"]) || $_REQUEST["selfedit"]!="true") {
	require_once '../../../../../../Mouf.php';
} else {
	require_once '../../../../../../mouf/MoufManager.php';
	MoufManager::initMoufManager();
	require_once '../../../../../../MoufUniversalParameters.php';
	require_once '../../../../../../mouf/MoufAdmin.php';
}

// Note: checking rights is done after loading the required files because we need to open the session
// and only after can we check if it was not loaded before loading it ourselves...
require_once '../../../../../../mouf/direct/utils/check_rights.php';

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