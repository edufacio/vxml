<?php
class PagedListViewData extends MenuViewData
{

	const PREVIOUS_PAGE = '%d% anterior';
	const FIRST_PAGE = 'primera %d%';
	const NEXT_PAGE = '%d% siguiente';
	const LAST_PAGE = 'última %d%';
	private $currentPageNumber;
	private $totalPages;
	private $titleList;
	protected $paginationOptions = array();
	private $pageName = 'página';


	/**
	 * @return PagedListViewData
	 */
	public static function create()
	{
		return Injector::get('PagedListViewData');
	}

	public function setTitleList($titleList)
	{
		$this->titleList = $titleList;
	}

	public function getTitleList()
	{
		return $this->titleList;
	}



	public function getPrompt()
	{
		$currentOptions = $this->getOptions();

		if (count($currentOptions) > 0) {
			return $this->buildPrompt($currentOptions);
		} else {
			return $this->buildPromptForEmptyList();
		}
	}


	public function setCurrentPageNumber($currentPage)
	{
		$this->currentPageNumber = $currentPage;
	}

	/**
	 * @return return current page number
	 */
	public function getCurrentPageNumber()
	{
		return $this->currentPageNumber;
	}

	/**
	 * @param Link $nextPageLink
	 */
	public function setNextPageNumberLink(Link $nextPageLink)
	{
		$this->addPaginationOption("Ir a la {$this->pageName} siguiente", str_replace('%d%', $this->pageName, self::NEXT_PAGE), $nextPageLink);
	}

	/**
	 * @param Link $firstPageLink
	 */
	public function setFirstPageNumberLink(Link $firstPageLink)
	{
		$this->addPaginationOption("Ir a la primera {$this->pageName}", str_replace('%d%', $this->pageName, self::FIRST_PAGE), $firstPageLink);
	}

	/**
	 * @param Link $lastPageLink
	 */
	public function setLastPageNumberLink(Link $lastPageLink)
	{
		$this->addPaginationOption("Ir a la ultima {$this->pageName}", str_replace('%d%', $this->pageName, self::LAST_PAGE), $lastPageLink);
	}

	/**
	 * @param Link $previousPageNumberLink
	 */
	public function setPreviousPageNumberLink(Link $previousPageNumberLink)
	{
		$this->addPaginationOption("Ir a la {$this->pageName} anterior", str_replace('%d%', $this->pageName, self::PREVIOUS_PAGE), $previousPageNumberLink);
	}

	public function addPaginationOption($optionInfo, $option, Link $link)
	{
		$this->assertOptionIsValid($option);
		$this->paginationOptions[$option] = new MenuOption($optionInfo, $option, $link);
	}

	public function getOptions()
	{
		return array_merge($this->options, $this->paginationOptions);
	}


	/**
	 * @param $totalPages
	 */
	public function setTotalPages($totalPages)
	{
		$this->totalPages = $totalPages;
	}

	/**
	 * Return the total pages number
	 * @return mixed
	 */
	public function getTotalPages()
	{
		return $this->totalPages;
	}

	private function buildPrompt($currentOptions)
	{
		$prompt = "Estos son los resultados econtrados.";
		if ($this->getTotalPages() > 0) {
			$prompt = "Mostrando {$this->pageName} " . $this->getCurrentPageNumber() . " de " . $this->getTotalPages() . ". ";
		}
		/* @var $option MenuOption */
		foreach ($currentOptions as $option) {
			$prompt .= $this->buildPromptForOption($option);
		}

		return $prompt;
	}

	private function buildPromptForEmptyList()
	{
		return "Lo sentimos no ha habido resultados";
	}

	protected function buildPromptForOption(MenuOption $option)
	{
		$optionName = $option->getEscapedInfoOption();
		$voiceOption = $option->getVoiceOption();
		$prompt = " $optionName, para elegirlo";
		if (is_numeric($voiceOption)) {
			$prompt .= " diga o marque $voiceOption";
		} elseif (KeyPhone::isKeyPhone($voiceOption)) {
			$prompt .= " diga o marque " . KeyPhone::toDigit($voiceOption);
		} else {
			$prompt .= " diga $voiceOption";
		}

		$prompt .= '.';
		return $prompt;
	}

	public function setPageName($pageName)
	{
		$this->pageName = $pageName;
	}

}

