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
        $viewData = new MenuViewData();
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
	    $view = new MenuView();
	    $view->render($viewData);
    }

	public function getCartelera($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCartelera($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setTitle("Peliculas en cartelera.");
		$view = new MenuView();
		$view->render($viewData);
	}

	private function getFilmsViewData($filmsData, $totalPages = 0, $currentPageNumber=0, $method ='', $params=array())
	{
		$viewData = new PagedListViewData();
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
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
		}

		if ($currentPageNumber < $totalPages && !empty($method)) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, $method, $params));
		}

		return $viewData;
	}

	public function getFilm($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$viewData = new FilmBasicViewData();
		$filmDetailedLink = $this->getLink(self::CONTROLLER_NAME, 'getFilmDetailed', $data);
		$viewData->setFilm($film, $filmDetailedLink);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		$this->instantiateView('MenuView')->render($viewData);
	}

	public function getFilmDetailed($data)
	{
		$film = FilmAffinityApi::getInstance()->getFilm($data[self::FILM_ID]);
		$viewData = new FilmDetailedViewData();
		$viewData->setFilm($film);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		if (isset($data[self::BREADCRUMBS])) {
			$viewData->setPreviousPageLink(Link::createFromEncondedHref($data[self::BREADCRUMBS]));
		}

		$this->instantiateView('MenuView')->render($viewData);
	}

    public function searchTitle($data)
    {
    }

    public function searchTitleForm($data)
    {
        $results = FilmAffinityApi::getInstance()->searchTitle($data[self::QUERY_PARAM]);
        $data["films"] = $this->getFilmsViewData($results);
        $this->instantiateView('FilmsResultView')->prepare($data);
    }



    public function searchActor($data)
    {
        $data['formLink'] = $this->getLink(self::CONTROLLER_NAME, "searchActorForm");
        $this->instantiateView('FilmActorSearchFormView')->prepare($data);
    }

    public function searchActorForm($data)
    {
        $results = FilmAffinityApi::getInstance()->searchActor($data[self::QUERY_PARAM]);
        $data["films"] = $this->getFilmsViewData($results);
        $this->instantiateView('FilmsResultView')->prepare($data);
    }

    public function searchDirector($data)
    {
        $data['formLink'] = $this->getLink(self::CONTROLLER_NAME, "searchDirectorForm");
        $this->instantiateView('FilmDirectorSearchFormView')->prepare($data);
    }

    public function searchDirectorForm($data)
    {
        $results = FilmAffinityApi::getInstance()->searchDirector($data[self::QUERY_PARAM]);
        $data["films"] = $this->getFilmsViewData($results);
        $this->instantiateView('FilmsResultView')->prepare($data);
    }

	private function getMainMenuLink()
	{
		return $this->getLink(self::CONTROLLER_NAME, "index");
	}

}