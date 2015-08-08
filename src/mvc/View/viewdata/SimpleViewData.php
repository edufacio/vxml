<?php

Abstract class SimpleViewData
{
	protected $prompt;
	private $language = Language::esES;
	private $previousPageLink;
	private $mainMenuLink;
	private $hiddenParams = array();

	function __construct()
	{
		$currentSession = CurrentSession::getInstance();
		$this->hiddenParams[NavigationMap::SESSION_PARAM] = $currentSession->getSession();
		$this->hiddenParams[NavigationMap::CALLER_PARAM] = $currentSession->getCallerId();
	}


	abstract function getGrammar();

	/**
	 * @param String $prompt
	 */
	public function setPrompt($prompt)
	{
		$this->prompt = $prompt;
	}

	/**
	 * @return String
	 */
	public function getPrompt()
	{
		return $this->prompt;
	}

	/**
	 * @param Link $mainMenuLink
	 */
	public function setMainMenuLink(Link $mainMenuLink)
	{
		$this->mainMenuLink = $mainMenuLink;
	}

	/**
	 * @return Link
	 */
	public function getMainMenuLink()
	{
		return $this->mainMenuLink;
	}

	/**
	 * @return boolean
	 */
	public function existsMainMenuLink()
	{
		return $this->mainMenuLink !== null;
	}

	/**
	 * @return String
	 */
	public function getMainMenuOption()
	{
		return KeyPhone::KEY_0;
	}

	/**
	 * @return String
	 */
	public function getMainMenuPrompt()
	{
		return "Para volver al menu principal pulse 0";
	}

	/**
	 * @return String
	 */
	protected function getMainMenuOptionGslItem()
	{
		if ($this->existsMainMenuLink()) {
			return '(' . $this->getMainMenuOption() . ')';
		} else {
			return '';
		}
	}

	protected function getMainMenuOptionItem()
	{
		if ($this->existsMainMenuLink()) {
			return '<item>' . KeyPhone::toDigit($this->getMainMenuOption()) . '</item>' . PHP_EOL;
		} else {
			return '';
		}
	}

	/**
	 * @param Link $previousPageLink
	 */
	public function setPreviousPageLink(Link $previousPageLink)
	{
		$this->previousPageLink = $previousPageLink;
	}

	/**
	 * @return Link
	 */
	public function getPreviousPageLink()
	{
		return $this->previousPageLink;
	}

	/**
	 * @return boolean
	 */
	public function existsPreviousPageLink()
	{
		return $this->previousPageLink !== null;
	}

	/**
	 * @return String
	 */
	public function getPreviousPagePrompt()
	{
		return "Para volver atras pulse asterisco";
	}

	/**
	 * @return String
	 */
	public function getPreviousPageOption()
	{
		return KeyPhone::KEY_STAR;
	}

	/**
	 * @see Language::XX
	 *
	 * @param $language
	 */
	public function setLanguage($language)
	{
		$this->language = $language;
	}

	/**
	 * @see Language:XX
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	public function addHiddenParam($paramName, $value) {
		$this->hiddenParams[$paramName] = $value;
	}

	public function getHiddenParams() {
		return $this->hiddenParams;
	}

	public function getNameList() {
		return implode(' ', array_keys($this->hiddenParams));
	}

	/**
	 * @return String
	 */
	protected function getPreviousPageOptionGslItem()
	{
		if ($this->existsPreviousPageLink()) {
			return '(' . $this->getPreviousPageOption() . ')';
		} else {
			return '';
		}
	}

	protected function getPreviousPageOptionItem()
	{
		if ($this->existsMainMenuLink()) {
			return '<item>' . KeyPhone::toDigit($this->getPreviousPageOption()) . '</item>' . PHP_EOL;
		} else {
			return '';
		}
	}

	protected function getWeakBreak()
	{
		return '<break strength="weak" />';
	}

	protected function getNormalBreak()
	{
		return '<break />';
	}
}