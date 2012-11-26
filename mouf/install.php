<?php
use Mouf\MoufManager;
use Mouf\Actions\InstallUtils;

// First, let's request the install utilities


// Let's init Mouf

InstallUtils::init(InstallUtils::$INIT_APP);

// Let's create the instance
$moufManager = MoufManager::getMoufManager();

//language detection service
if ($moufManager->instanceExists("defaultLanguageDetection")) {
	$defaultLanguageDetection = $moufManager->getInstanceDescriptor("defaultLanguageDetection");
} else {
	$defaultLanguageDetection = $moufManager->createInstance("BrowserLanguageDetection");
	$defaultLanguageDetection->setName("defaultLanguageDetection");
}

//common translation service
if ($moufManager->instanceExists("fineCommonTranslationService")) {
	$fineCommonTranslationService = $moufManager->getInstanceDescriptor("fineCommonTranslationService");
} else {
	$fineCommonTranslationService = $moufManager->createInstance("FinePHPArrayTranslationService");
	$fineCommonTranslationService->setName("fineCommonTranslationService");
	$fineCommonTranslationService->getProperty("languageDetection")->setValue($defaultLanguageDetection);
}
$fineCommonTranslationService->getProperty("i18nMessagePath")->setValue("vendor/mouf/utils.i18n.fine/resources/");

//defaultTranslationService
if ($moufManager->instanceExists("defaultTranslationService")) {
	$defaultTranslationService = $moufManager->getInstanceDescriptor("defaultTranslationService");
} else {
	$defaultTranslationService = $moufManager->createInstance("FinePHPArrayTranslationService");
	$defaultTranslationService->setName("defaultTranslationService");
	$defaultTranslationService->getProperty("i18nMessagePath")->setValue("resources/");
	$defaultTranslationService->getProperty("languageDetection")->setValue($defaultLanguageDetection);
}

// Let's rewrite the MoufComponents.php file to save the component
$moufManager->rewriteMouf();

// Finally, let's continue the install
InstallUtils::continueInstall();