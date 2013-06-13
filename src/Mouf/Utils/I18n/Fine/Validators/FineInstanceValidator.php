<?php 

namespace Mouf\Utils\i18n\Fine\Validators;

use Mouf\MoufManager;

use Mouf\Validator\MoufValidatorResult;

use Mouf\Validator\MoufStaticValidatorInterface;

class FineInstanceValidator implements MoufStaticValidatorInterface {
	
	/**
	 * @return \Mouf\Validator\MoufValidatorResult
	 */
	public static function validateClass() {
		
		$instanceExists = MoufManager::getMoufManager()->instanceExists('defaultTranslationService');
		
		if ($instanceExists) {
			return new MoufValidatorResult(MoufValidatorResult::SUCCESS, "<b>Fine:</b> 'defaultTranslationService' instance found");
		} else {
			return new MoufValidatorResult(MoufValidatorResult::WARN, "<b>Fine:</b> Unable to find the 'defaultTranslationService' instance. Click here to <a href='".ROOT_URL."mouf/mouf/newInstance?instanceName=defaultTranslationService&instanceClass=FinePHPArrayTranslationService'>create an instance of the FinePHPArrayTranslationService class named 'defaultTranslationService'</a>.");
		}
	}

}