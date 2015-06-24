<?php
class FormInput
{
	private $language;
	private $voiceInput;

	function __construct($language, $voiceInput)
	{
		$this->language = $language;
		$this->voiceInput = $voiceInput;
	}

	public function setLanguage($language)
	{
		$this->language = $language;
	}

	public function getLanguage()
	{
		return $this->language;
	}

	public function setVoiceInput($voiceInput)
	{
		$this->voiceInput = $voiceInput;
	}

	public function getVoiceInput()
	{
		return $this->voiceInput;
	}

	/**
	 * @return String
	 */
	public function getEscapedVoiceInput()
	{
		$escapeSearch = array('/á/', '/é/', '/í/', '/ó/', '/ú/', '/[^a-zA-Z 0-9ñÑ\-]+\s*/');
		$escapeReplacement = array('a', 'e', 'i', 'o', 'u', ' ');

		return trim(preg_replace($escapeSearch, $escapeReplacement, strtolower($this->voiceInput)));
	}
}