<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// This file validates that a /resources/message.php exists.
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