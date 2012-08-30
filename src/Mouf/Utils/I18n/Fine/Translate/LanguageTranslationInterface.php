<?php 
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
namespace Mouf\Utils\I18n\Fine\Translate;

/**
 * Used to translation message
 *  
 * @author Marc Teyssier
 *
 */
interface LanguageTranslationInterface {
	/**
	 * Get the translation of a message or a code
	 * 
	 * @param string $message Message or code to translate
	 * @return string translation
	 */
	public function getTranslation($message);
}