<?php
/*
 * Copyright (c) 2012 David Negrier
 * 
 * See the file LICENSE.txt for copying permission.
 */
namespace Mouf\Utils\I18n\Fine;

/**
 * The FineMessageFile class represents a PHP resource file that can be loaded / saved / modified.
 */
class FineMessageFile {

	/**
	 * The path to the file to be loaded
	 * @var string
	 */
	private $file;

	/**
	 * The array of messages in the file loaded.
	 */
	private $msg;

	/**
	 * Loads the php file
	 * @var $file The path to the file to be loaded
	 */
	public function load($file) {
		$this->file = $file;

		$msg = array();
		@include($file);

		$this->msg = $msg;
	}

	/**
	 * Saves the file.
	 */
	public function save() {
		ksort($this->msg);

		if (!is_writable($this->file)) {
			if (!file_exists($this->file)) {
				// Does the directory exist?
				$dir = dirname($this->file);
				if (!file_exists($dir)) {
					$result = mkdir($dir, 0755, true);
					
					if ($result == false) {
						$exception = new ApplicationException();
						$exception->setTitle("unable.to.create.directory.title", $dir);
						$exception->setMessage("unable.to.create.directory.text", $dir);
						throw $exception;
					}
				}
			} else {			
				$exception = new ApplicationException();
				$exception->setTitle("unable.to.write.file.title", $this->file);
				$exception->setMessage("unable.to.write.file.text", $this->file);
				throw $exception;
			}
		}

		$fp = fopen($this->file, "w");
		fwrite($fp, "<?php\n");
		foreach ($this->msg as $key=>$message) {
			fwrite($fp, '$msg[\''.str_replace("'","\\'", $key).'\']="'.str_replace('"','\\"', $message).'";'."\n");
		}
		fwrite($fp, "?>\n");
		fclose($fp);
	}

	/**
	 * Sets the message
	 */
	public function setMessage($key, $message) {
		$this->msg[$key] = $message;
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
	 * Returns all messages for this file.
	 */
	public function getAllMessages() {
		return $this->msg;
	}
}

?>
