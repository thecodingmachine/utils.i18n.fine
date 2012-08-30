<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */


/**
 * This function return the translation of a code or a sentence for a language.
 * The language is selected by the detection class instantiated. This class must by implements the LanguageDetectionInterface. By default for the application, the instance is translateService.
 * The translation is searched in the translation instance. This class must by implements the LanguageTranslationInterface. The getTranslation method return the translation for a key for a language.
 * 
 * 
 * @param $key string Code, sentence or key for the translation
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 * @return string Return the translation
 */
function iMsg($key) {
	static $translationService = null;
	if ($translationService == null) {
		/* @var $translationService LanguageTranslationInterface */
		$translationService = MoufManager::getMoufManager()->getInstance("translationService");
	}
	
	$args = func_get_args();
	return call_user_func_array(array($translationService, "getTranslation"), $args);	
}

/**
 * Do an echo for the iMsg return
 * 
 * @param string $key
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 */
function eMsg($key){
	$args = func_get_args();
	echo call_user_func_array("iMsg", $args);
}


/**
 * This function return the translation of a code or a sentence for a language. Moreover this function doesn't displayed the edit link even if the edition is active.
 * The language is selected by the detection class instantiated. This class must by implements the LanguageDetectionInterface. By default for the application, the instance is translateService.
 * The translation is searched in the translation instance. This class must by implements the LanguageTranslationInterface. The getTranslation method return the translation for a key for a language.
 * 
 * 
 * @param $key string Code, sentence or key for the translation
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 * @return string Return the translation
 */
function iMsgNoEdit($key) {
	static $translationService = null;
	if ($translationService == null) {
		/* @var $translationService LanguageTranslationInterface */
		$translationService = MoufManager::getMoufManager()->getInstance("translationService");
	}
	
	$args = func_get_args();
	return call_user_func_array(array($translationService, "getTranslationNoEditMode"), $args);	
}

/**
 * Do an echo for the iMsgNoEdit return
 * 
 * @param string $key
 * @param ... string Parameters of variable elements in the translation. These elements are wrote {0} for the first, {1} for the second ...
 */
function eMsgNoEdit($key){
	$args = func_get_args();
	echo call_user_func_array("iMsgNoEdit", $args);
}


/**
 * Return the translation of the month.  
 * 
 * @param int $month Number of the month
 * @return string
 */
function fine_get_month($month) {
	$month = (int)$month;
	
	return fine_get_common_service("month.".$month);
}


/**
 * Return the translation of the day.  
 * 
 * @param int $day Number of the day, 0 for sunday, 6 for saturday
 * @return string
 */
function fine_get_day($day) {
	$day = (int)$day;
	
	return fine_get_common_service("day.".$day);
}

/**
 * Return date format like dd/mm/yy or mm/dd/yy
 * 
 * @return string
 */
function fine_get_dateformat_php_short() {
	return fine_get_common_service("date.format.php.short");
}

/**
 * Returns the string representing a price, along its currency.
 * The price is passed as a double. The currency as a ISO 4217 code ("USD" for US Dollar, etc...)
 * @param float $price
 * @param string $currency_iso_code
 */
function fine_get_price($price, $currency_iso_code) {
	return fine_get_common_service("price.format", $price, fine_get_currency_symbol($currency_iso_code));
}

/**
 * Returns the currency symbol based on the currency ISO 4217 code.
 * 
 * @param string $isocode
 */
function fine_get_currency_symbol($isocode) {
	return FineCurrencyUtils::getCurrencySymbol($isocode);
}

/**
 * Return the translation for a key with the commonService instance. The resource is stored in the plugin
 * 
 * @return string
 */
function fine_get_common_service($key) {
	static $commonService = null;
	
	$args = func_get_args();
	
	if ($commonService == null) {
		/* @var $commonService LanguageTranslationInterface */
		$commonService = MoufManager::getMoufManager()->getInstance("fineCommonTranslationService");
	}
	
	return call_user_func_array(array($commonService, "getTranslationNoEditMode"), $args);
}