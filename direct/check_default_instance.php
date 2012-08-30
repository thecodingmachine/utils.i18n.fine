<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

// This file validates that a "splash" instance exists.
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

$jsonObj = array();

$instanceExists = MoufManager::getMoufManager()->instanceExists('translationService');

if ($instanceExists) {
	$jsonObj['code'] = "ok";
	$jsonObj['html'] = "'translationService' instance found";
} else {
	$jsonObj['code'] = "warn";
	$jsonObj['html'] = "Unable to find the 'translationService' instance. Click here to <a href='".ROOT_URL."mouf/mouf/newInstance?instanceName=translationService&instanceClass=FinePHPArrayTranslationService'>create an instance of the FinePHPArrayTranslationService class named 'translationService'</a>.";
}

echo json_encode($jsonObj);
exit;

?>