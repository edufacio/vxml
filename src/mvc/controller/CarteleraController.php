<?php
Class CarteleraController extends FilmController
{
	const CONTROLLER_NAME = 'Cartelera';

	/**
	 * @return CarteleraController
	 */
	public static function create($navigation)
	{
		return Injector::get('CarteleraController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("cartelera por fecha de estreno", "ver por fecha de estreno", $this->getLink(self::CONTROLLER_NAME, "getCarteleraByDate"));
		$viewData->addOption("cartelera por fecha de estreno", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "getCarteleraByDate"));
		$viewData->addOption("cartelera por puntuacion", "ver por puntuacion", $this->getLink(self::CONTROLLER_NAME, "getCarteleraByRating"));
		$viewData->addOption("cartelera por puntuacion", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "getCarteleraByRating"));
		$viewData->addOption("cartelera por popularidad", "ver por popularidad", $this->getLink(self::CONTROLLER_NAME, "getCarteleraByVotes"));
		$viewData->addOption("cartelera por popularidad", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "getCarteleraByVotes"));


		$viewData->setPrompt("$preprompt Menú cartelera: "
			. " Para ver la cartelera ordenada por fecha de estreno diga ver por fecha de estreno o pulse 1."
			. " Para ver la cartelera ordenada por puntuacion diga ver por puntuacion o pulse 2."
			. " Para ver la cartelera ordenada por popularidad(número de votos) diga ver por popularidad o pulse 3.");

		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getMainMenuLink());
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCarteleraByRating($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCarteleraRatingSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "index"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por puntuacion.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCarteleraByVotes($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCarteleraVotesSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "index"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por popularidad.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCarteleraByDate($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCarteleraReleaseDateSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "index"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por fecha.");
		$view = MenuView::create();
		$view->render($viewData);
	}
}