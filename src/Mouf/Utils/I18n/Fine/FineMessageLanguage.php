<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
namespace Mouf\Utils\I18n\Fine;

use Mouf\MoufException;
/**
 * The FineMessageLanguage class represents a PHP resource file that can be loaded / saved / modified.
 * There are many files for on language. Files are write with the start information of the key. Function used the separator ., - or _. 
 */
class FineMessageLanguage {

	/**
	 * The path to the folder to be loaded
	 * @var string
	 */
	private $folder;

	/**
	 * The array of messages in the folder loaded.
	 */
	private $msg = array();

	
	private $language = null;
	
	/**
	 * Loads all translation files
	 * @var $folder The path to the folder to be loaded
	 */
	public function loadAllFile($folder, $language = "default") {
        $this->folder = $folder;
        $this->language = $language;
		
        $msg = array();
        if($language == "default")
            @include($folder."message.php");
        else
        @include($folder."message_".$language.".php");

        /*
         * Some OSes don't distinguish between empty array and FALSE (they may return FALSE in case of no match),
         * therefore the foreach may trigger a Warning even if there is no error
         */
        $ressourceFiles = glob($folder."message_custom_".$language."_*.php");
        if ($ressourceFiles){
            foreach ($ressourceFiles as $filename) {
                @include($filename);
            }
        }

        $this->msg = $msg;
	}

	/**
	 * Loads all translation files
	 * @var $folder The path to the folder to be loaded
	 */
	public function loadOneFile($file, $language = "default") {
        //$folder is not set ... even remove this line should or initiate $folder
		$this->folder = $folder;
		
		$msg = array();
		@include($file);

		$this->msg = $msg;
	}
	
	/**
	 * Saves the file.
	 */
	public function save() {
		ksort($this->msg);

		$this->deleteFile($this->language);
		
		if($this->language == "default") {
			$file_default = $this->folder."message.php";
		} else {
			$file_default = $this->folder."message_".$this->language.".php";
		}

		$this->createFile($file_default);
			
		$msg = array();
		foreach ($this->msg as $key => $value) {
			$strs = preg_split('/[\.\-\_]/', $key);
			$str = strtolower($strs[0]);
			if($str && count($strs) != 1 && preg_match('/[a-z0-9\.\-\_]*/', $str)) {
				$msg[$str][$key] = $value;
			} else {
				$msg["default"][$key] = $value;
			}
		}
		
		$old = umask(00002);
		foreach ($msg as $custom => $list) {
			if($custom == "default") {
				$file = $file_default;
			} else {
				$file = $this->folder."message_custom_".$this->language."_".$custom.".php";
				$this->createFile($file);
			}
			$fp = fopen($file, "w");
			fwrite($fp, "<?php\n");
			foreach ($list as $key=>$message) {
				fwrite($fp, '$msg['.var_export($key, true).']='.var_export($message, true).';'."\n");
			}
			fwrite($fp, "?>\n");
			fclose($fp);
		}
		umask($old);
	}

	private function deleteFile($language) {
		foreach (glob($this->folder."message_custom_".$language."_*.php") as $file)
			unlink($file);
	}
	
	private function createFile($file) {
		if (!is_writable($file)) {
			if (!file_exists($file)) {
				// Does the directory exist?
				$dir = dirname($file);
				if (!file_exists($dir)) {
					$old = umask(0);
					$result = mkdir($dir, 0775, true);
					umask($old);
					
					if ($result == false) {
						throw new MoufException("Unable to create directory ".$dir);
					}
				}
			} else {
				throw new MoufException("Unable to write file ".$file);
			}
		} else {
			// Empties the file
			$fp = fopen($file, "w");
			fclose($fp);
		}
	}
	
	/**
	 * Sets the message
	 */
	public function setMessage($key, $message) {
		$this->msg[$key] = $message;
	}

	/**
	 * Sets messages
	 */
	public function setMessages($translations) {
		
		foreach ($translations as $key => $message) {
			if($message)
				$this->msg[$key] = $message;
		}
	}
	
	/**
	 * Returns a message for the key $key.
	 */
	public function getMessage($key) {
		if (isset($this->msg[$key])) {
			return $this->msg[$key];
		} else {
			return null;
		}
	}

	/**
	 * Sets the message
	 */
	public function deleteMessage($key) {
		if(isset($this->msg[$key]))
			unset($this->msg[$key]);
	}
	
	/**
	 * Returns all messages for this file.
	 */
	public function getAllMessages() {
		return $this->msg;
	}
}

?>
