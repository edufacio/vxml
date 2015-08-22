<?php
Class FilmController extends Controller
{
	const CONTROLLER_NAME = 'Film';
	const REVIEW_COMPLETE = 'review_complete';
	const PAGE_PARAM = 'page';
	const FILMS_BY_PAGE = 9;
	const FILM_ID = 'filmId';
	const BREADCRUMBS = "breadCrumb";

	/**
	 * @return FilmController
	 */
	public static function create($navigation)
	{
		return Injector::get('FilmController', $navigation);
	}

	public function index($data)
	{
		IndexVxmlFilmController::create($this->navigation)->index($data);
	}

	protected function getFilmsPagedListViewData($filmsData, $totalPages = 0, $currentPageNumber = 0, $controllerName, $method = '', $params = array())
	{
		$viewData = PagedListViewData::create();
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setTotalPages($totalPages);
		$optionNumber = 1;
		$breadCrumbs = $this->getLink($controllerName, $method, $params);
		$viewData->addHiddenParam(self::BREADCRUMBS, $breadCrumbs->getHrefEncoded());
		foreach ($filmsData as $filmId => $filmTitle) {
			$link = $this->getLink(FilmController::CONTROLLER_NAME, 'getFilm', array(self::FILM_ID => $filmId));
			$viewData->addOption($filmTitle, KeyPhone::fromDigit($optionNumber), $link);
			$optionNumber++;
		}

		if ($currentPageNumber > 0 && !empty($method)) {
			$params[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink($controllerName, $method, $params));
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink($controllerName, $method, $params));
		}

		if ($currentPageNumber < $totalPages && !empty($method)) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink($controllerName, $method, $params));
			$params[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink($controllerName, $method, $params));
		}

		return $viewData;
	}

	public function getFilm($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$viewData = FilmBasicViewData::create();
		$filmDetailedLink = $this->getLink(FilmController::CONTROLLER_NAME, 'getFilmDetailed', $data);
		$reviewLink = $this->getLink(FilmController::CONTROLLER_NAME, 'getReview', $data);
		$viewData->setFilm($film, $filmDetailedLink, $reviewLink);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		MenuView::create()->render($viewData);
	}

	public function getFilmDetailed($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$reviewLink = $this->getLink(FilmController::CONTROLLER_NAME, 'getReview', $data);
		$viewData = FilmDetailedViewData::create();
		$viewData->setFilm($film, $reviewLink);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		MenuView::create()->render($viewData);
	}

	public function getReview($data)
	{
		$currentPageNumber = $data[self::PAGE_PARAM];
		$showReviewComplete = $data[self::REVIEW_COMPLETE] != 0;
		list($totalPages, $review) = FilmAffinityApi::getInstance()->getReview($data[self::FILM_ID], $data[self::PAGE_PARAM]);
		
		$viewData = ReviewViewData::create();
		$viewData->setTotalPages($totalPages);
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'getFilm', $data));
		$viewData->setMainMenuLink($this->getMainMenuLink());
		/* @var $review Review */
		if ($showReviewComplete && $review->hasSpoilerReview()) {
			$viewData->setReviewComplete($review);
		} elseif($review->hasSpoilerReview()) {
			$data[self::REVIEW_COMPLETE] = 1;
			$viewData->setReviewWithoutSpoiler($review, $this->getLink(self::CONTROLLER_NAME, 'getReview', $data));
		} else {
			$viewData->setReview($review);
		}

		$data[self::REVIEW_COMPLETE] = 0;
		if ($currentPageNumber > 0) {
			$data[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'getReview', $data));
			$data[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'getReview', $data));
		}

		if ($currentPageNumber < $totalPages) {
			$data[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'getReview', $data));
			$data[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'getReview', $data));
		}

		MenuView::create()->render($viewData);
	}
}