<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */

final class iniParser {

	var $iniFilename    = '';
	var $iniParsedArray = array();

	function iniParser($filename)
	{
		if (!file_exists($filename)) {
			$file = fopen($filename, 'w');
			fclose($file);
		}

		$this->iniFilename = $filename;

		if ($this->iniParsedArray = parse_ini_file($filename, true)) {
			$this->iniParsedArray = Common::arrayChangeCaseUpper($this->iniParsedArray);
			return true;
		} else {
			return false;
		}
	}

	function getSection ($key)
	{
		return $this->iniParsedArray[$key];
	}

	function getValue ($section, $key)
	{
		if (!isset($this->iniParsedArray[$section])) {
			return false;
		}
		if (isset($this->iniParsedArray[$section][$key])) {
			return $this->iniParsedArray[$section][$key];
		} else {
			return false;
		}
	}

	function get ($section, $key = null)
	{
		if (is_null($key)) {
			return $this->getSection($section);
		}
		return $this->getValue($section, $key);
	}

	function setSection ($section, $array)
	{
		if (!is_array($array)) return false;

		return $this->iniParsedArray[mb_strtoupper($section)] = $array;
	}

	function setValue ($section, $key, $value)
	{
		if ($this->iniParsedArray[$section][$key] = $value) return true;
	}

	function set ($section, $key, $value = null)
	{
		if (is_array($key) && is_null($value)) {
			return $this->setSection($section, $key);
		}
		return $this->setValue($section, $key, $value);
	}

	function save ($filename = null)
	{
		if ($filename == null) {
			$filename = $this->iniFilename;
		}
		if (is_writeable ($filename)) {
			$desc = fopen ($filename, "w");

			$tmp_insert = '';
			$tmp = array();

			foreach ($this->iniParsedArray as $section => $v) {
				$tmp_insert .= PHP_EOL."[".mb_strtoupper($section)."]". PHP_EOL;
				foreach ($v as $name => $value) {
					if (is_array($value) && !empty($value)) {
						$tmp_array = array();
						foreach ($value as $nameArray => $valueArray) {
							$tmp_insert .= str_pad($name.'['.$nameArray.']', 25, ' ', STR_PAD_RIGHT) . '= ' .$valueArray. PHP_EOL;
						}
					} else {
						if (empty($value)) {
							$value = null;
						}
						$tmp_insert .= str_pad($name, 25, ' ', STR_PAD_RIGHT) . '= ' .$value. PHP_EOL;
					}
				}
			}
			fwrite ($desc, $tmp_insert);
			fclose ($desc);
			return true;
		} else {
			return false;
		}
	}

	function getFile ($filename)
	{
		if (is_file($filename)) {
			$return = true;
		} else {
			$return = false;
		}
		return $return;
	}
}
