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



// The array of messages by message, then by language:
// array(message_key => array(language => message))
$msgs = array();

$keys = $translationService->getAllKeys();

$languages = $translationService->getSupportedLanguages();

foreach ($keys as $key) {
	$msgs[$key] = $translationService->getMessageForAllLanguages($key, $language);
}

ksort($msgs);
$response = array("languages"=>$languages, "msgs"=>$msgs);

if ($encode == "php") {
	echo serialize($response);
} elseif ($encode == "json") {
	echo json_encode($response);
} else {
	echo "invalid encode parameter";
}

?>