<?php

class SimpleViewData
{
	protected  $prompt;
	private $previousPageLink;
	private $mainMenuLink;

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
	protected function getMainMenuOptionGrammar()
	{
		if ($this->existsMainMenuLink()) {
			return '(' . $this->getMainMenuOption() . ')';
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
	 * @return String
	 */
	protected function getPreviousPageOptionGrammar()
	{
		if ($this->existsPreviousPageLink()) {
			return '(' . $this->getPreviousPageOption() . ')';
		} else {
			return '';
		}
	}

	protected function getWeakBreak() {
		return '<break strength="weak" />';
	}

	protected function getNormalBreak() {
		return '<break />';
	}
}