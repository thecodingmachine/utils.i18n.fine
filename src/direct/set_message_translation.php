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
$key = $_REQUEST["key"];
$label = $_REQUEST["label"];
$language = $_REQUEST["language"];
$delete = $_REQUEST["delete"];
if (get_magic_quotes_gpc()==1)
{
	$key = stripslashes($key);
	$msginstancename = stripslashes($msginstancename);
	$label = stripslashes($label);
	$language = stripslashes($language);
}

$translationService = MoufManager::getMoufManager()->getInstance($msginstancename);
/* @var $translationService FinePHPArrayTranslationService */

$messageFile = $translationService->getMessageLanguageForLanguage($language);
if($delete)
	$messageFile->deleteMessage($key);
else
	$messageFile->setMessage($key, $label);
$messageFile->save();


if ($encode == "php") {
	echo serialize(true);
} elseif ($encode == "json") {
	echo json_encode(true);
} else {
	echo "invalid encode parameter";
}

?>