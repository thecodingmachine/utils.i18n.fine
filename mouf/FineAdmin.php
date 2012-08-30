<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


MoufUtils::registerMainMenu('htmlMainMenu', 'HTML', null, 'mainMenu', 40);
MoufUtils::registerMenuItem('htmlFineMainMenu', 'Fine', null, 'htmlMainMenu', 10);
MoufUtils::registerMenuItem('htmlFineEditTranslationMenuItem', 'Edit translations', 'mouf/editLabels/missinglabels?name=translationService', 'htmlFineMainMenu', 10);
MoufUtils::registerMenuItem('htmlFineEditTranslationAnyInstanceMenuItem', 'Edit translations (any instance)', 'javascript:chooseInstancePopup("FinePHPArrayTranslationService", "'.ROOT_URL.'mouf/editLabels/missinglabels?name=", "'.ROOT_URL.'")', 'htmlFineMainMenu', 20);
MoufUtils::registerMenuItem('htmlFineSupportedLanguagesMenuItem', 'Supported languages', 'javascript:chooseInstancePopup("FinePHPArrayTranslationService", "'.ROOT_URL.'mouf/editLabels/supportedLanguages?name=", "'.ROOT_URL.'")', 'htmlFineMainMenu', 30);
MoufUtils::registerMenuItem('htmlFineEnableDisable2MenuItem', 'Enable/Disable translation', 'mouf/editLabels/', 'htmlFineMainMenu', 40);
MoufUtils::registerMenuItem('htmlFineImportCSV2MenuItem', 'Import/Export', 'javascript:chooseInstancePopup("FinePHPArrayTranslationService", "'.ROOT_URL.'mouf/editLabels/excelimport?name=", "'.ROOT_URL.'")', 'htmlFineMainMenu', 50);

// Controller declaration
MoufManager::getMoufManager()->declareComponent('editLabels', 'EditLabelController', true);
MoufManager::getMoufManager()->bindComponents('editLabels', 'template', 'moufTemplate');
?>