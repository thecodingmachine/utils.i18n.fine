<?php 
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
namespace Mouf\Utils\I18n\Fine\Language;

/**
 * Used to detect the website language
 * 
 * @author Marc Teyssier
 *
 */
interface LanguageDetectionInterface {
	
	/**
	 * Function return code language. 2 letters like 'en', 'fr' ...
	 * 
	 * @return string
	 */
	public function getLanguage();
}
