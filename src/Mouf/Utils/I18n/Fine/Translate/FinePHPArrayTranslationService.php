<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
namespace Mouf\Utils\I18n\Fine\Translate;

use Mouf\Validator\MoufValidatorResult;

use Mouf\Validator\MoufValidatorInterface;

use Mouf\MoufManager;

use Mouf\Utils\I18n\Fine\FineMessageLanguage;

use Mouf\Utils\I18n\Fine\Language\LanguageDetectionInterface;
use Mouf\Utils\I18n\Fine\Language\BrowserLanguageDetection;

/**
 * Used to save all translation in a php array.
 * 
 * @Component
 * @author Marc Teyssier
 * @ExtendedAction {"name":"Supported languages", "url":"editLabels/supportedLanguages", "default":false}
 * @ExtendedAction {"name":"Edit translations", "url":"editLabels/missinglabels", "default":false}
 */
class FinePHPArrayTranslationService implements LanguageTranslationInterface, MoufValidatorInterface  {
	
	/**
	 * Detection language object
	 * 
	 * @var LanguageDetectionInterface
	 */
	private $languageDetection = null;
	
	/**
	 * Detection language object
	 * 
	 * @var LanguageDetectionInterface
	 */
	private $msg = null;
	
	/**
	 * Store the file load to optimize performance and call only one time each translation files.
	 * @var string[]
	 */
	private $loadFile = array();
	
	/**
	 * The path to the directory storing the translations.
	 * <p>The directory path should end with a "/".</p>
	 * <p>Each file in this directory is a PHP file containing an array variable named $msg. The key is the code or message id, the value is translation.<br/>
	 * Example :
	 * </p>
	 * <pre class="brush:php">$msg["home.title"] = "Hello world";<br />
	 * $msg["home.text"] = "News 1, news 2 and news 3";</pre>
	 * 
	 * 
	 * @Property
	 * @Compulsory
	 * @var string
	 */
	public $i18nMessagePath = "resources/";
	
	/**
	 * Store the edition mode from the session
	 * 
	 * @var boolean
	 */
	private $msg_edition_mode = null;
	
	/**
	 * The languageDetection object is used to define what language to use when performing translations.
	 * <p>If no object is defined, an instance of BrowserLanguageDetection is used, which means
	 * the language used will be the default language of the browser (this is a good default choice)</p>
	 * 
	 * @Property
	 * @param LanguageDetectionInterface $languageDetection
	 */
	public function setLanguageDetection($languageDetection) {
		$this->languageDetection = $languageDetection;
	}
	
	/**
	 * The name of the Mouf component this instance is bound to.
	 * @var string
	 */
	private $myInstanceName;
	
	/**
	 * Store the language code
	 * @var string
	 */
	private $language = null;
	
	/**
	 * Runs the validation of the instance.
	 * Returns a MoufValidatorResult explaining the result.
	 *
	 * @return MoufValidatorResult
	 */
	public function validateInstance() {
		$instanceName = MoufManager::getMoufManager()->findInstanceName($this);
			
		if (!file_exists(ROOT_PATH.$this->i18nMessagePath."message.php")) {
			return new MoufValidatorResult(MoufValidatorResult::ERROR, "<b>Fine: </b>Unable to find default translation file for instance: <code>".ROOT_PATH.$this->i18nMessagePath."message.php</code>.<br/>"
															."You should create the following files:<br/>"
															.$this->i18nMessagePath."message.php <a href='".ROOT_URL."vendor/mouf/mouf/editLabels/createMessageFile?name=".$instanceName."&selfedit=false&language=default'>(create this file)</a>");
		}
		else {
			$this->loadAllMessages();
			
			// The array of messages by message, then by language:
			// array(message_key => array(language => message))
			$keys = $this->getAllKeys();
			foreach ($keys as $key) {
				$msgs = $this->getMessageForAllLanguages($key);
				if (!isset($msgs['default'])) {
					$missingDefaultKeys[$instanceName][] = $key;
				}
			}
			if (empty($missingDefaultKeys)) {
				return new MoufValidatorResult(MoufValidatorResult::SUCCESS, "<b>Fine: </b>Default translation file found in instance <code>$instanceName</code>.<br />
																				Default translation is available for all messages.");
			} else {
				$html = "";
				foreach ($missingDefaultKeys as $instanceName=>$missingKeys) {
					$html .= "<b>Fine: </b>A default translation in '".$instanceName."' is missing for these messages: ";
					foreach ($missingKeys as $missingDefaultKey) {
						$html .= "<a href='".ROOT_URL."vendor/mouf/mouf/editLabels/editLabel?key=".urlencode($missingDefaultKey)."&language=default&backto=".urlencode(ROOT_URL)."mouf/&msginstancename=".urlencode($instanceName)."'>".$missingDefaultKey."</a> ";
					}
					$html .= "<hr/>";
				}
				return new MoufValidatorResult(MoufValidatorResult::WARN, $html);
			}
			
		}
	}
	
	/**
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. This function check all the custom file if the translation is not in the main message_[language].php
	 * If this message doesn't exist, it return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.1/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslation($message) {
		if($this->language === null) {
			$this->initLanguage();
		}
		if($this->msg_edition_mode === null) {
			$this->msg_edition_mode = isset($_SESSION["FINE_MESSAGE_EDITION_MODE"])?$_SESSION["FINE_MESSAGE_EDITION_MODE"]:false;
		}

		$args = func_get_args();
	
		//Load the main file
		if($this->msg === null) {
			$this->retrieveMessages($this->language);
		}
			
		//If the translation is not in the main file, load the custom file associated to the message
		if(!isset($this->msg[$message])) {
			$this->retrieveCustomMessages($message, $this->language);
		}
			
		if (isset($this->msg[$message])) {
			$value = $this->msg[$message];
			for ($i=1, $count=count($args);$i<$count;$i++){
				$value = str_replace('{'.($i-1).'}', $args[$i], $value);
			}
		} else {
			$value = "???".$message;
			for ($i=1,$count=count($args);$i<$count;$i++){
				$value .= ", ".plainstring_to_htmlprotected($args[$i]);
			}
			$value .= "???";
		}
	
		if ($this->msg_edition_mode) {
			if ($this->myInstanceName == null) {
				$this->myInstanceName = MoufManager::getMoufManager()->findInstanceName($this);
			}
			$value = $value.' <a href="'.ROOT_URL.'vendor/mouf/mouf/editLabels/editLabel?key='.$message.'&backto='.urlencode($_SERVER['REQUEST_URI']).'&msginstancename='.urlencode($this->myInstanceName).'">edit</a>';
		}
	
		return $value;
	}
	

	/**
	 * Retrieve the translation of code or message.
	 * Check in the $msg variable if the key exist to return the value. This function check all the custom file if the translation is not in the main message_[language].php
	 * If this message doesn't exist, it not return a link to edit it.
	 * 
	 * @see plugins/utils/i18n/fine/2.1/translate/LanguageTranslationInterface::getTranslation()
	 */
	public function getTranslationNoEditMode($message) {
		if($this->language === null) {
			$this->initLanguage();
		}

		$args = func_get_args();
	
		if($this->msg === null) {
			$this->retrieveMessages($this->language);
		}
			
		if(!isset($this->msg[$message])) {
			$this->retrieveCustomMessages($message, $this->language);
		}

		if (isset($this->msg[$message])) {
			$value = $this->msg[$message];
			for ($i=1;$i<count($args);$i++){
				$value = str_replace('{'.($i-1).'}', $args[$i], $value);
			}
		} else {
			$value = "???".$message;
			for ($i=1,$count=count($args);$i<$count;$i++){
				$value .= ", ".plainstring_to_htmlprotected($args[$i]);
			}
			$value .= "???";
		}

		return $value;
	}


	/**
	 * Returns true if a translation is available for the $message key, false otherwise.
	 *
	 * @param string $message Key of the message
	 * @return bool
	 */
	public function hasTranslation($message) {
		if($this->language === null) {
			$this->initLanguage();
		}
		
		//Load the main file
		if($this->msg === null) {
			$this->retrieveMessages($this->language);
		}
		
		//If the translation is not in the main file, load the custom file associated to the message
		if(!isset($this->msg[$message])) {
			$this->retrieveCustomMessages($message, $this->language);
		}
		
		if (isset($this->msg[$message])) {
			return true;
		} else {
			return false;
		}
	}
	
	private function initLanguage() {
		if($this->languageDetection) {
			$this->language = $this->languageDetection->getLanguage();
		} elseif(MoufManager::getMoufManager()->instanceExists("translationService")) {
			$this->language = MoufManager::getMoufManager()->getInstance("translationService")->getLanguage();
			if ($this->language === null) {
				$this->languageDetection = new BrowserLanguageDetection();
				$this->language = $this->languageDetection->getLanguage();
			}
		} else {
			$this->languageDetection = new BrowserLanguageDetection();
			$this->language = $this->languageDetection->getLanguage();
		}
	}
	
	/**
	 * Retrieve array variable store in the language file.
	 * This function include the message resource by default and the language file if the language code is set.
	 * The file contain an array with translation, we retrieve it in a private array msg. 
	 * 
	 * @param string $language Language code
	 * @return boolean
	 */
	private function retrieveMessages($language = null) {
		$msg = array();
		$file = ROOT_PATH.$this->i18nMessagePath.'message.php';
		if(!isset($this->loadFile[$file]) && file_exists($file)) {
			@include $file;
			$this->loadFile[$file] = true;
		}
		if($language) {
			$file = ROOT_PATH.$this->i18nMessagePath.'message_'.$language.'.php';
			if (!isset($this->loadFile[$file]) && file_exists($file)){
				require $file;
				$this->loadFile[$file] = true;
			}
		}
		$this->msg = $msg;
	}

	/**
	 * Retrieve array variable store in the custom language file.
	 * This function include the message resource by default and the language file if the language code is set.
	 * The file contain an array with translation for the same key (element before a separator ; , or _), we retrieve it in a private array msg. 
	 * 
	 * @param string $language Language code
	 * @return boolean
	 */
	private function retrieveCustomMessages($key, $language = null) {
		$msg = array();
		
		$strs = preg_split('/[\.\-\_]/', $key);
		$str = strtolower($strs[0]);
		if($str && $str != $key && preg_match('/[a-z0-9\.\-\_]*/', $str)) {
			$file = ROOT_PATH.$this->i18nMessagePath.'message_custom_default_'.$str.'.php';
			if (!isset($this->loadFile[$file]) && file_exists($file)) {
				require $file;
				$this->loadFile[$file] = true;
			}

			$file = ROOT_PATH.$this->i18nMessagePath.'message_custom_'.$language.'_'.$str.'.php';
			if (!isset($this->loadFile[$file]) && file_exists($file)) {
				require $file;
				$this->loadFile[$file] = true;
			}
			if(isset($msg))
				$this->msg = array_merge($msg, $this->msg);
		}
	}
	
	/**
	 * Use this function to force the language.
	 * Don't forget to call this function with null to restore default parameters. 
	 * Return true if the language change, else the language is the same,
	 * this function return false
	 * 
	 * @param string $language
	 * @return bool
	 */
	public function forceLanguage($language) {
		if($this->language != $language) {
			$this->language = $language;
			$this->loadFile = array();
			$this->msg = null;
			return true;
		}
		return false;
	}
	
	/***************************/
	/****** Edition mode *******/
	/***************************/
	
	/**
	 * The list of all messages in all languages
	 * @var array<string, FineMessageLanguage>
	 */
	private $messages = array();
	
	
	/**
	 * Returns the language of the browser, or "default" if the language is not supported (no messages_$language.php).
	 */
	public function getLanguage() {
		if ($this->languageDetection === null) {
			$this->languageDetection = new BrowserLanguageDetection();
		}
		$language = $this->languageDetection->getLanguage();
		if (file_exists(ROOT_PATH.$this->i18nMessagePath.'message_'.$language.'.php')) {
			return $language;
		} else {
			return "default";
		}
	}

	/**
	 * @return FineMessageLanguage The message file for the current user.
	 */
	public function getLanguageFileForCurrentUser() {
		$messageLanguage = new FineMessageLanguage();

		$language = $this->getLanguage();
		$messageLanguage->load(ROOT_PATH.$this->i18nMessagePath, $language);
		return $messageLanguage;
	}

	/**
	 * @return FineMessageLanguage The message language for the current user.
	 */
	public function getMessageLanguageForLanguage($language) {
		if (isset($this->messages[$language])) {
			return $this->messages[$language];
		}
		
		$messageLanguage = new FineMessageLanguage();
		$messageLanguage->loadAllFile(ROOT_PATH.$this->i18nMessagePath, $language);

		$this->messages[$language] = $messageLanguage;
		return $messageLanguage;
	}

	/**
	 * Load all messages
	 */
	public function loadAllMessages() {
		$files = glob(ROOT_PATH.$this->i18nMessagePath.'message*.php');

		$defaultFound = false;
		foreach ($files as $file) {
			$base = basename($file);
			if ($base == "message.php") {
				$messageFile = new FineMessageLanguage();
				$messageFile->loadAllFile(ROOT_PATH.$this->i18nMessagePath);
				$this->messages['default'] = $messageFile;
				$defaultFound = true;
			} else {
				if(strpos($base, '_custom') === false) {
					$phpPos = strpos($base, '.php');
					$language = substr($base, 8, $phpPos-8);
					$messageLanguage = new FineMessageLanguage();
					$messageLanguage->loadAllFile(ROOT_PATH.$this->i18nMessagePath, $language);
					$this->messages[$language] = $messageLanguage;
				}
			}
		}
		if (!$defaultFound) {
			$messageFile = new FineMessageLanguage();
			$messageFile->loadAllFile(ROOT_PATH.$this->i18nMessagePath);
			$this->messages['default'] = $messageFile;
		}
	}

	/**
	 * Loads and returns all the messages with languages, in a big array.
	 */
	public function getAllMessages($language = null) {
	
		$this->loadAllMessages();

		// The array of messages by message, then by language:
		// array(message_key => array(language => message))
		$msgs = array();
	
		$keys = $this->getAllKeys();
	
		$languages = $this->getSupportedLanguages();
	
		foreach ($keys as $key) {
			$msgs[$key] = $this->getMessageForAllLanguages($key, $language);
		}
	
		ksort($msgs);
		$response = array("languages"=>$languages, "msgs"=>$msgs);
		return $response;
	}
	
	/**
	 * Get the message for language.
	 * 
	 * @param string $key
	 * @return array<string, string>
	 */
	public function getMessageForAllLanguages($key, $lang = null) {
		
		if (!$this->messages) {
			$this->loadAllMessages();
		}
		
		$messageArray = array();
		foreach ($this->messages as $language=>$messageLanguage) {
			if(is_null($lang) || $lang == "")
				$messageArray[$language] = $messageLanguage->getMessage($key);
			elseif($lang == $language)
				$messageArray[$language] = $messageLanguage->getMessage($key);
		}
		return $messageArray;
	}

	/**
	 * Returns the list of all keys that have been defined in all language files.
	 * loadAllMessages must have been called first.
	 */
	public function getAllKeys() {
		$all_messages = array();

		// First, let's merge all the arrays in order to get all the keys:
		foreach ($this->messages as $language=>$message) {
			$all_messages = array_merge($all_messages, $message->getAllMessages());
		}

		return array_keys($all_messages);
	}

	/**
	 * Returns the list of languages loaded.
	 */
	public function getSupportedLanguages() {
		return array_keys($this->messages);
	}
	
	/**
	 * Creates the file for specified language.
	 * If the file already exists, the function does nothing.
	 *
	 * @param string $language
	 */
	public function createLanguageFile($language) {
		if ($language=="default") {
			$file = ROOT_PATH.$this->i18nMessagePath."message.php";
		} else {
			$file = ROOT_PATH.$this->i18nMessagePath."message_".$language.".php";
		}
		
		if (!is_writable($file)) {
			if (!file_exists($file)) {
				// Does the directory exist?
				$dir = dirname($file);
				if (!file_exists($dir)) {
					$old = umask(0);
					$result = mkdir($dir, 0775, true);
					umask($old);
					
					if ($result == false) {
						$exception = new \Exception("Unable to create directory ".$dir);
						throw $exception;
					}
				}
			} else {			
				$exception = new \Exception("Unable to write file ".$file);
				throw $exception;
			}
		}
		
		if (!file_exists($file)) {
			$fp = fopen($file, "w");
			fclose($fp);
			chmod($file, 0664);
		}
	}
	
	/**
	 * Sets and saves a new message translation.
	 * 
	 * @param string $key
	 * @param string $value
	 * @param string $language
	 */
	public function setMessage($key, $value, $language) {
		$messageFile = $this->getMessageLanguageForLanguage($language);
		$messageFile->setMessage($key, $value);
		$messageFile->save();
	}
	
	/**
	 * Sets and saves many messages at once for a given language.
	 *
	 * @param array $messages
	 * @param string $language
	 */
	public function setMessages(array $messages, $language) {
		$messageFile = $this->getMessageLanguageForLanguage($language);
		$messageFile->setMessages($messages);
		$messageFile->save();
	}
	
	/**
	 * Deletes a message translation.
	 *
	 * @param string $key
	 * @param string $language
	 */
	public function deleteMessage($key, $language) {
		$messageFile = $this->getMessageLanguageForLanguage($language);
		$messageFile->deleteMessage($key);
		$messageFile->save();
	}
}