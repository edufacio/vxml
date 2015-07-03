<?php
Class IndexVxmlFilmController extends Controller {
    const CONTROLLER_NAME = 'IndexVxmlFilm';
    const QUERY_PARAM = 'query';
	const PAGE_PARAM = 'page';
	const FILMS_BY_PAGE = 9;
    const FILM_ID = 'filmId';
	const BREADCRUMBS = "breadCrumb";
    public function index($data)
    {
        $viewData = MenuViewData::create();
        $viewData->addOption("busqueda por titulo", "busqueda por titulo", $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
        $viewData->addOption("busqueda por titulo", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "searchTitle"));
        $viewData->addOption("busqueda por actor", "busqueda por actor", $this->getLink(self::CONTROLLER_NAME, "searchActor"));
        $viewData->addOption("busqueda por actor", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "searchActor"));
        $viewData->addOption("busqueda por director", "busqueda por director", $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
        $viewData->addOption("busqueda por director", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "searchDirector"));
        $viewData->addOption("ver cartelera", "ver cartelera", $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
        $viewData->addOption("ver cartelera", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "getCartelera"));
	    $viewData->setPrompt(" Bienvenido al sistema de informacion de peliculas por telefono."
		    . " Para buscar una película por título pulse 1 o diga búsqueda por título."
		    . " Para buscar una película por actor pulse 2 o diga búsqueda por actor."
		    . " Para buscar una pelicula por director pulse 3 o diga búsqueda por director. "
		    . " Para oir la cartelera pulse 4 o diga ver cartelera o diga cartelera.");

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

		foreach($filmsData as $filmId => $filmTitle) {
			$link = $this->getLink(self::CONTROLLER_NAME, 'getFilm', array(self::FILM_ID => $filmId, self::BREADCRUMBS => $breadCrumbs->getHrefEncoded()));
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
	    $viewData->setPrompt("Busqueda por titulo. por favor diga el titulo a buscar");
	    $viewData->addInput(Language::esES, "La jungla de cristal", true);
	    $viewData->addInput(Language::esES, "matar a un ruiseñor", true);
	    $viewData->addInput(Language::enUS, "love story", true);
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
	    $viewData->setPrompt("Busqueda por actor. Por favor diga el actor a buscar");
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
	    $viewData->setPrompt("Busqueda por director. Por favor diga el director a buscar");
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

	private function getMainMenuLink()
	{
		return $this->getLink(self::CONTROLLER_NAME, "index");
	}
}