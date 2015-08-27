<?php
Class RecommendationController extends FilmController
{
	const CONTROLLER_NAME = 'Recommendation';

	/**
	 * @return RecommendationController
	 */
	public static function create($navigation)
	{
		return Injector::get('RecommendationController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		if (!CurrentSession::getInstance()->isLogged()) {
			IndexVxmlFilmController::create($this->navigation)->index($data);
			return;
		}

		$user = UserBackend::getInstance()->getUser(CurrentSession::getInstance()->getCurrentPhone());
		if (!$user->hasProvinceId()) {
			ProfileController::create($this->navigation)->modifyProvince($data, "Para poder recomendarte sesiones necesitamos saber tu provincia y tus cines favoritos");
			return;
		}
		$preferences = UserBackend::getInstance()->getPreferences($user->getPhone());
		if (!$preferences->hasCinemas()) {
			ProfileController::create($this->navigation)->menuCinema($data, "Para poder recomendarte sesiones necesitamos saber tus cines favoritos");
			return;
		}

		$showTimes = UserBackend::getInstance()->getShowTimes($user);
		if (empty($showTimes)) {
			ProfileController::create($this->navigation)->index($data, "No hemos encontrado ninguna recomendación por favor modifica tu perfil.");
		} else {
			$page = $data[self::PAGE_PARAM];
			$totalPages = count($showTimes) - 1;
			if ($page > $totalPages) {
				$page = $totalPages;
			}
			$viewData = $this->getShowTimeView($showTimes, $page, $totalPages);
			$viewData->setMainMenuLink($this->getMainMenuLink());
			$viewData->setPreviousPageLink($this->getMainMenuLink());
			MenuView::create()->render($viewData);
		}
	}

	private function getShowTimeView($showTimes, $currentPageNumber, $totalPages)
	{
		$showTime = array_shift(array_slice($showTimes, $currentPageNumber, 1));
		$filmLink = $this->getLink(FilmController::CONTROLLER_NAME, 'getFilm', array(self::FILM_ID => $showTime->getFilm()->getFilmId()));
		$viewData = ShowTimeViewData::create();
		$viewData->setTotalPages($totalPages);
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setShowTime($showTime, $filmLink);
		$params = array(self::PAGE_PARAM => $currentPageNumber);
		$viewData->addHiddenParam(self::BREADCRUMBS, $this->getLink(self::CONTROLLER_NAME, 'index', $params)->getHrefEncoded());

		if ($currentPageNumber > 0) {
			$params[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'index', $params));
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'index', $params));
		}

		if ($currentPageNumber < $totalPages) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'index', $params));
			$params[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'index', $params));
		}
		return $viewData;
	}
}