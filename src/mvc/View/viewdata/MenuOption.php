<?php
class MenuOption
{
	private $infoOption;
	private $voiceOption;
	private $link;

	/**
	 * @param $infoOption,  info about the option, could be used to detail the option to the user
	 * @param $voiceOption, Words needed to choose the option
	 * @param Link $link, link for the option
	 */
	function __construct($infoOption, $voiceOption, Link $link)
	{
		$this->infoOption = $infoOption;
		$this->voiceOption = $voiceOption;
		$this->link = $link;
	}

	/**
	 * @param Link $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
	}

	/**
	 * @return Link
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param String $textOption
	 */
	public function setVoiceOption($textOption)
	{
		$this->voiceOption = $textOption;
	}

	/**
	 * @return String
	 */
	public function getVoiceOption()
	{
		return $this->voiceOption;
	}

	/**
	 * @return String
	 */
	public function getEscapedVoiceOption()
	{
		$escapeSearch = array('/á/', '/é/', '/í/', '/ó/', '/ú/', '/[^a-zA-Z 0-9ñÑ\-]+\s*/');
		$escapeReplacement = array('a', 'e', 'i', 'o', 'u', ' ');

		return trim(preg_replace($escapeSearch, $escapeReplacement, $this->voiceOption));
	}

	/**
	 * @return String
	 */
	public function getEqualCondition($varName)
	{
		$escapedTestOption = $this->getEscapedVoiceOption();
		if (KeyPhone::isKeyPhone($escapedTestOption)) {
			$keyPhoneName = KeyPhone::toNumberName($escapedTestOption);
			$keyIntValue = KeyPhone::toDigit($escapedTestOption);
			$conditions = array("$varName == '$escapedTestOption'", "$varName == '$keyPhoneName'", "$varName == $keyIntValue");
		} elseif (!is_numeric($escapedTestOption)) {
			$conditions = array("$varName == '$escapedTestOption'");
		} else {
			$conditions = array("$varName == $escapedTestOption");
		}

		return implode('||', $conditions);
	}

	/**
	 * @return mixed
	 */
	public function getInfoOption()
	{
		return $this->infoOption;
	}

	public function getOptionForGrammar()
	{
		$escapedTestOption = $this->getEscapedVoiceOption();
		if (KeyPhone::isKeyPhone($escapedTestOption)) {
			$keyPhoneName = KeyPhone::toNumberName($escapedTestOption);
			$keyIntValue = KeyPhone::toDigit($escapedTestOption);
			return "($escapedTestOption)($keyPhoneName)($keyIntValue)";
		} else {
			return "($escapedTestOption)";
		}
	}


}