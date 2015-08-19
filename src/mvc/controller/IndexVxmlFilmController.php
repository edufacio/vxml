<?php
Class IndexVxmlFilmController extends Controller
{
	const CONTROLLER_NAME = 'IndexVxmlFilm';
	const QUERY_PARAM = 'query';
	const PAGE_PARAM = 'page';
	const FILMS_BY_PAGE = 9;
	const FILM_ID = 'filmId';
	const BREADCRUMBS = "breadCrumb";

	/**
	 * @return IndexVxmlFilmController
	 */
	public static function create($navigation)
	{
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

	private function loggedMainMenu($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("buscar películas", "buscar películas", $this->getLink(self::CONTROLLER_NAME, "menuSearch"));
		$viewData->addOption("buscar películas", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "menuSearch"));
		$viewData->addOption("cartelera", "cartelera", $this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->addOption("cartelera", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->addOption("proximos estrenos", "proximos estrenos", $this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->addOption("proximos estrenos", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->addOption("que ver", "que ver", $this->getLink(self::CONTROLLER_NAME, "viewRecomendations"));
		$viewData->addOption("que ver", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "viewRecomendations"));
		$viewData->addOption("tu perfil", "perfil", $this->getLink(ProfileController::CONTROLLER_NAME, "index"));
		$viewData->addOption("tu perfil", KeyPhone::KEY_5, $this->getLink(ProfileController::CONTROLLER_NAME, "index"));
		$viewData->addOption("salir de la cuenta", "salir", $this->getLink(LoginController::CONTROLLER_NAME, "logout"));
		$viewData->addOption("salir de la cuenta", KeyPhone::KEY_6, $this->getLink(LoginController::CONTROLLER_NAME, "logout"));

		$viewData->setPrompt("$preprompt Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película pulse 1 o diga buscar peliculas."
			. " Para ir a la cartelera pulse 2 o diga cartelera."
			. " Para ir a la Próximos estrenos pulse 3 o diga Próximos estrenos."
			. " Para oir que le recomendamos ver pulse 4 o diga que ver"
			. " Para ver y modificar su perfil pulse 5 o diga perfil."
			. " Para salir de su cuenta pulse 6 o diga salir");

		$view = MenuView::create();
		$view->render($viewData);
	}

	private function unloggedMainMenu($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("buscar películas", "buscar películas", $this->getLink(self::CONTROLLER_NAME, "menuSearch"));
		$viewData->addOption("buscar películas", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "menuSearch"));
		$viewData->addOption("cartelera", "cartelera", $this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->addOption("cartelera", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->addOption("proximos estrenos", "proximos estrenos", $this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->addOption("proximos estrenos", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->addOption("hacer lóguin", "lóguin", $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("hacer lóguin", KeyPhone::KEY_4, $this->getLink(LoginController::CONTROLLER_NAME, "login"));
		$viewData->addOption("registrar", "registrar", $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->addOption("registrar", KeyPhone::KEY_5, $this->getLink(LoginController::CONTROLLER_NAME, "register"));
		$viewData->setPrompt("$preprompt Bienvenido al sistema de informacion de peliculas por telefono."
			. " Para buscar una película pulse 1 o diga buscar peliculas."
			. " Para ir a la cartelera pulse 2 o diga cartelera."
			. " Para ir a los Próximos estrenos pulse 3 o diga Próximos estrenos."
			. " Para hacer lóguin pulse 4 o diga lóguin"
			. " Para registrarse pulse 5 o diga registrar");

		$view = MenuView::create();
		$view->render($viewData);
	}


	public function menuSearch($data, $preprompt = '')
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

	public function menuNextRelease($data, $preprompt = '')
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

	public function viewRecomendations($data, $preprompt = '')
	{
		if (!CurrentSession::getInstance()->isLogged()) {
			$this->index($data);
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
		$filmLink = $this->getLink(self::CONTROLLER_NAME, 'getFilm', array(self::FILM_ID => $showTime->getFilm()->getFilmId()));
		$viewData = ShowTimeViewData::create();
		$viewData->setShowTime($showTime, $filmLink);
		$viewData->setTotalPages($totalPages);
		$viewData->setCurrentPageNumber($currentPageNumber);
		$params = array(self::PAGE_PARAM => $currentPageNumber);
		$viewData->addHiddenParam(self::BREADCRUMBS, $this->getLink(self::CONTROLLER_NAME, 'viewRecomendations', $params)->getHrefEncoded());

		if ($currentPageNumber > 0) {
			$params[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'viewRecomendations', $params));
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'viewRecomendations', $params));
		}

		if ($currentPageNumber < $totalPages) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'viewRecomendations', $params));
			$params[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'viewRecomendations', $params));
		}
		return $viewData;
	}


	public function getNextReleaseByRating($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseRatingSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->setTitle("Próximos Estrenos ordenadas por puntuacion.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getNextReleaseByVotes($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseVotesSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->setTitle("Próximos Estrenos ordenadas por popularidad.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getNextReleaseByDate($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getNextReleaseReleaseDateSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuNextRelease"));
		$viewData->setTitle("Próximos Estrenos ordenadas por fecha.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function menuCartelera($data, $preprompt = '')
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
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por puntuacion.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCarteleraByVotes($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCarteleraVotesSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por popularidad.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function getCarteleraByDate($data)
	{
		list($totalPages, $films) = FilmAffinityApi::getInstance()->getCarteleraReleaseDateSorted($data[self::PAGE_PARAM], self::FILMS_BY_PAGE);
		$viewData = $this->getFilmsPagedListViewData($films, $totalPages, $data[self::PAGE_PARAM], __FUNCTION__, $data);
		$mainMenuLink = $this->getMainMenuLink();
		$viewData->setMainMenuLink($mainMenuLink);
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, "menuCartelera"));
		$viewData->setTitle("Peliculas en cartelera ordenadas por fecha.");
		$view = MenuView::create();
		$view->render($viewData);
	}

	private function getFilmsPagedListViewData($filmsData, $totalPages = 0, $currentPageNumber = 0, $method = '', $params = array())
	{
		$viewData = PagedListViewData::create();
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setTotalPages($totalPages);
		$optionNumber = 1;
		$breadCrumbs = $this->getLink(self::CONTROLLER_NAME, $method, $params);
		$viewData->addHiddenParam(self::BREADCRUMBS, $breadCrumbs->getHrefEncoded());
		foreach ($filmsData as $filmId => $filmTitle) {
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
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'menuSearch'));
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
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'menuSearch'));
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
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'menuSearch'));
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