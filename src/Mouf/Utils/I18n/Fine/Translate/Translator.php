<?php
namespace Mouf\Utils\I18n\Fine\Translate;

use Mouf\Utils\Value\ValueUtils;
use Mouf\Utils\Value\ValueInterface;

class Translator implements ValueInterface {
	/**
	 * Instance find translation
	 * @var LanguageTranslationInterface
	 */
	private $languageTranslationInterface;
	
	/**
	 * Key to search translation
	 * @var string|ValueInterface
	 */
	private $key;

	/**
	 * 
	 * @param LanguageTranslationInterface $languageTranslationInterface
	 * @param string $key
	 */
	public function __construct(LanguageTranslationInterface $languageTranslationInterface, $key) {
		$this->languageTranslationInterface = $languageTranslationInterface;
		$this->key = $key;
	}
	
	/**
	 * Returns the value represented by this object.
	 *
	 * @return string
	 */
	public function val() {
		return $this->languageTranslationInterface->getTranslation(ValueUtils::val($this->key));
	}
}