<?php
Class IndexVxmlFilmController extends Controller {
    const CONTROLLER_NAME = 'IndexVxmlFilm';
    const QUERY_PARAM = 'query';
	const PAGE_PARAM = 'page';
	const FILMS_BY_PAGE = 9;
    const FILM_ID = 'filmId';
	const BREADCRUMBS = "breadCrumb";

	/**
	 * @return IndexVxmlFilmController
	 */
	public static function create($navigation) {
		return Injector::get('IndexVxmlFilmController', $navigation);
	}

    public function index($data, $preprompt = '')
    {
	    if (CurrentSession::getInstance()->isLogged()) {
		   $this->loggedMainMenu($data, $preprompt);
	    } else {
		   $this->unloggedMainMenu($data, $preprompt);
	    }
    }

	private function loggedMainMenu($data, $preprompt = '') {
		$viewData = MenuViewData::create();
		$viewData->addOption("búsqueda por titulo", "búsqueda por titulo", $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por titulo", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por actor", "búsqueda por actor", $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por actor", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por director", "búsqueda por director", $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
		$viewData->addOption("búsqueda por director", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
		$viewData->addOption("ver cartelera", "ver cartelera", $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
		$viewData->addOption("ver cartelera", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
		$viewData->addOption("salir de la cuenta", "salir", $this->getLink(LoginController::CONTROLLER_NAME, "logout"));
		$viewData->addOption("salir de la cuenta", KeyPhone::KEY_5, $this->getLink(LoginController::CONTROLLER_NAME, "logout"));

		$viewData->setPrompt("$preprompt Niño eres el puto amo, Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película por título pulse 1 o diga búsqueda por título."
			. " Para buscar una película por actor pulse 2 o diga búsqueda por actor."
			. " Para buscar una pelicula por director pulse 3 o diga búsqueda por director. "
			. " Para oir la cartelera pulse 4 o diga ver cartelera o diga cartelera."
			. " Para salir de su cuenta pulse 5 o diga salir");

		$view = MenuView::create();
		$view->render($viewData);
	}

	private function unloggedMainMenu($data, $preprompt = '') {
		$viewData = MenuViewData::create();
		$viewData->addOption("búsqueda por titulo", "búsqueda por titulo", $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por titulo", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
		$viewData->addOption("búsqueda por actor", "búsqueda por actor", $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por actor", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "searchActor"));
		$viewData->addOption("búsqueda por director", "búsqueda por director", $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
		$viewData->addOption("búsqueda por director", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
		$viewData->addOption("ver cartelera", "ver cartelera", $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
		$viewData->addOption("ver cartelera", "cartelera", $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
		$viewData->addOption("ver cartelera", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
		$viewData->addOption("hacer lóguin", "lóguin", $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("hacer lóguin", KeyPhone::KEY_5, $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("registrar", "registrar", $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->addOption("registrar", KeyPhone::KEY_6, $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->setPrompt("$preprompt Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película por título pulse 1 o diga búsqueda por título."
			. " Para buscar una película por actor pulse 2 o diga búsqueda por actor."
			. " Para buscar una pelicula por director pulse 3 o diga búsqueda por director. "
			. " Para oir la cartelera pulse 4 o diga ver cartelera o diga cartelera."
			. " Para hacer lóguin pulse 5 o diga lóguin"
			. " Para registrarse pulse 6 o diga registrar");

		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCartelera($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCartelera($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($mainMenuLink);
		$viewData->setTitle("Peliculas en cartelera.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	private function getFilmsPagedListViewData($filmsData, $totalPages = 0, $currentPageNumber=0, $method ='', $params=array())
	{
		$viewData = PagedListViewData::create();
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setTotalPages($totalPages);
		$optionNumber = 1;
		$breadCrumbs = $this->getLink(self::CONTROLLER_NAME, $method, $params);
		$viewData->addHiddenParam(self::BREADCRUMBS, $breadCrumbs->getHrefEncoded());
		foreach($filmsData as $filmId => $filmTitle) {
			$link = $this->getLink(self::CONTROLLER_NAME, 'getFilm', array(self::FILM_ID => $filmId));
			$viewData->addOption($filmTitle, KeyPhone::fromDigit($optionNumber), $link);
			$optionNumber++;
		}

		if ($currentPageNumber > 0 && !empty($method)) {
			$params[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
		}

		if ($currentPageNumber < $totalPages && !empty($method)) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
			$params[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
		}

		return $viewData;
	}

	public function getFilm($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$viewData = FilmBasicViewData::create();
		$filmDetailedLink = $this->getLink(self::CONTROLLER_NAME, 'getFilmDetailed', $data);
		$viewData->setFilm($film, $filmDetailedLink);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		MenuView::create()->render($viewData);
	}

	public function getFilmDetailed($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$viewData = FilmDetailedViewData::create();
		$viewData->setFilm($film);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		MenuView::create()->render($viewData);
	}

    public function searchTitle($data)
    {
	    $viewData = FormViewData::create();
	    $viewData->setMainMenuLink($this->getMainMenuLink());
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
	    $viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
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
	    $viewData->setVarReturnedName(self::QUERY_PARAM);
	    $viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'searchActorForm'));
	    $viewData->setPrompt("búsqueda por actor. Por favor diga el actor a buscar");
	    $viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/actors2.csv");
	    FormView::create()->render($viewData);
    }

    public function searchActorForm($data)
    {
	    list($totalPages, $films) = FilmAffinityApi::getInstance()->searchActor($data[self::QUERY_PARAM], $data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
	    $viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
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
	    $viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'searchDirectorForm'));
	    $viewData->setPrompt("búsqueda por director. Por favor diga el director a buscar");
	    $viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/directors.csv");
	    FormView::create()->render($viewData);
    }

    public function searchDirectorForm($data)
    {
	    list($totalPages, $films) = FilmAffinityApi::getInstance()->searchDirector($data[self::QUERY_PARAM], $data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
	    $viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
	    $viewData->setMainMenuLink($this->getMainMenuLink());
	    $viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'searchDirector'));
	    $viewData->setTitle("Peliculas encontradas para el director " . $data[self::QUERY_PARAM]);
	    MenuView::create()->render($viewData);
    }
}