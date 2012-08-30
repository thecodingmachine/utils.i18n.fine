<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */

/**
 * Adds a new translation language.
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
$language = $_REQUEST["language"];
if (get_magic_quotes_gpc()==1)
{
	$msginstancename = stripslashes($msginstancename);
	$language = stripslashes($language);
}

$translationService = MoufManager::getMoufManager()->getInstance($msginstancename);
/* @var $translationService FinePHPArrayTranslationService */

$translationService->loadAllMessages();

$languages = $translationService->getSupportedLanguages();

if (array_search($language, $languages) === false) {
	$translationService->createLanguageFile($language);
}

if ($encode == "php") {
	echo serialize(true);
} elseif ($encode == "json") {
	echo json_encode(true);
} else {
	echo "invalid encode parameter";
}

?>