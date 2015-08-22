<?php
Class NextReleaseController extends FilmController
{
	const CONTROLLER_NAME = 'NextRelease';

	/**
	 * @return NextReleaseController
	 */
	public static function create($navigation)
	{
		return Injector::get('NextReleaseController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("Próximos Estrenos por fecha de estreno", "ver por fecha de estreno", $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByDate"));
		$viewData->addOption("Próximos Estrenos por fecha de estreno", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByDate"));
		$viewData->addOption("Próximos Estrenos por puntuacion", "ver por puntuacion", $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByRating"));
		$viewData->addOption("Próximos Estrenos por puntuacion", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByRating"));
		$viewData->addOption("Próximos Estrenos por popularidad", "ver por popularidad", $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByVotes"));
		$viewData->addOption("Próximos Estrenos por popularidad", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "getNextReleaseByVotes"));


		$viewData->setPrompt("$preprompt Menú Próximos Estrenos: "
			. " Para ver los Próximos Estrenos ordenada por fecha de estreno diga ver por fecha de estreno o pulse 1."
			. " Para ver los Próximos Estrenos ordenada por puntuacion diga ver por puntuacion o pulse 2."
			. " Para ver los Próximos Estrenos ordenada por popularidad(número de votos) diga ver por popularidad o pulse 3.");


		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getMainMenuLink());
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getNextReleaseByRating($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseRatingSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME));
		$viewData->setTitle("Próximos Estrenos ordenadas por puntuacion.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getNextReleaseByVotes($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseVotesSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME));
		$viewData->setTitle("Próximos Estrenos ordenadas por popularidad.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getNextReleaseByDate($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseReleaseDateSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME));
		$viewData->setTitle("Próximos Estrenos ordenadas por fecha.");
		$view = MenuView::create();
		$view->render($viewData);
	}
}