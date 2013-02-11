<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
require('Mouf/Utils/I18n/Fine/Controllers/EditLabelController.php');

use Mouf\MoufManager;
use Mouf\MoufUtils;

MoufUtils::registerMainMenu('htmlMainMenu', 'HTML', null, 'mainMenu', 40);
MoufUtils::registerMenuItem('htmlFineMainMenu', 'Fine', null, 'htmlMainMenu', 10);
MoufUtils::registerChooseInstanceMenuItem('htmlFineSuppotedLanguageMenuItem', 'Supported languages', 'editLabels/supportedLanguages', "Mouf\\Utils\\I18n\\Fine\\Translate\\FinePHPArrayTranslationService", 'htmlFineMainMenu', 10);
MoufUtils::registerChooseInstanceMenuItem('htmlFineEditTranslationMenuItem', 'Edit translations', 'editLabels/missinglabels', "Mouf\\Utils\\I18n\\Fine\\Translate\\FinePHPArrayTranslationService", 'htmlFineMainMenu', 20);
MoufUtils::registerChooseInstanceMenuItem('htmlFineEditionTranslationMenuItem', 'Enable/Disable Edition', 'editLabels/editionMode', "Mouf\\Utils\\I18n\\Fine\\Translate\\FinePHPArrayTranslationService", 'htmlFineMainMenu', 25);


// Controller declaration
$moufManager = MoufManager::getMoufManager();
$moufManager->declareComponent('editLabels', 'Mouf\\Utils\\I18n\\Fine\\Controllers\\EditLabelController', true);
$moufManager->bindComponents('editLabels', 'template', 'moufTemplate');
$moufManager->bindComponents('editLabels', 'content', 'block.content');

?>