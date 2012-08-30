<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

/**
 * Returns all the translation of one key.
 */

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

$encode = "php";
if (isset($_REQUEST["encode"]) && $_REQUEST["encode"]="json") {
	$encode = "json";
}

$msginstancename = $_REQUEST["msginstancename"];
$key = $_REQUEST["key"];
if (get_magic_quotes_gpc()==1)
{
	$key = stripslashes($key);
	$msginstancename = stripslashes($msginstancename);
}

$translationService = MoufManager::getMoufManager()->getInstance($msginstancename);
/* @var $translationService FinePHPArrayTranslationService */

$translationService->loadAllMessages();

$msgs = $translationService->getMessageForAllLanguages($key);

if ($encode == "php") {
	echo serialize($msgs);
} elseif ($encode == "json") {
	echo json_encode($msgs);
} else {
	echo "invalid encode parameter";
}

?>