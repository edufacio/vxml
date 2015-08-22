<?php
Class SearchController extends FilmController
{
	const CONTROLLER_NAME = 'Search';
	const QUERY_PARAM = 'query';

	/**
	 * @return SearchController
	 */
	public static function create($navigation)
	{
		return Injector::get('SearchController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("búsqueda por titulo", "búsqueda por titulo", $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por titulo", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por actor", "búsqueda por actor", $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por actor", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por director", "búsqueda por director", $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
		$viewData->addOption("búsqueda por director", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "searchDirector"));


		$viewData->setPrompt("$preprompt Busqueda de películas: "
			. " Para buscar una película por título pulse 1 o diga búsqueda por título."
			. " Para buscar una película por actor pulse 2 o diga búsqueda por actor."
			. " Para buscar una pelicula por director pulse 3 o diga búsqueda por director. ");

		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getMainMenuLink());
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function searchTitle($data)
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->setVarReturnedName(self::QUERY_PARAM);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'searchTitleForm'));
		$viewData->setPrompt("búsqueda por titulo. por favor diga el titulo a buscar");
		$viewData->addVoiceInput(Language::esES, "La jungla de cristal", true);
		$viewData->addVoiceInput(Language::esES, "matar a un ruiseñor", true);
		$viewData->addVoiceInput(Language::esES, "la isla minima", true);
		$viewData->addVoiceInput(Language::enUS, "love story", true);
		$viewData->addVoiceInput(Language::enUS, "toy story", true);
		FormView::create()->render($viewData);
	}

	public function searchTitleForm($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->searchTitle($data[self::QUERY_PARAM], $data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'searchTitle'));
		$viewData->setTitle("Peliculas encontradas para el titulo." . $data[self::QUERY_PARAM]);
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function searchActor($data)
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->setVarReturnedName(self::QUERY_PARAM);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'searchActorForm'));
		$viewData->setPrompt("búsqueda por actor. Por favor diga el actor a buscar");
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/actors2.csv");
		FormView::create()->render($viewData);
	}

	public function searchActorForm($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->searchActor($data[self::QUERY_PARAM], $data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'searchActor'));
		$viewData->setTitle("Peliculas encontradas para el actor " . $data[self::QUERY_PARAM]);
		MenuView::create()->render($viewData);
	}

	public function searchDirector($data)
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setVarReturnedName(self::QUERY_PARAM);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'searchDirectorForm'));
		$viewData->setPrompt("búsqueda por director. Por favor diga el director a buscar");
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/directors.csv");
		FormView::create()->render($viewData);
	}

	public function searchDirectorForm($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->searchDirector($data[self::QUERY_PARAM], $data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], self::CONTROLLER_NAME, __FUNCTION__, $data);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'searchDirector'));
		$viewData->setTitle("Peliculas encontradas para el director " . $data[self::QUERY_PARAM]);
		MenuView::create()->render($viewData);
	}
}