<?php
Class ProfileController extends Controller
{
	const CONTROLLER_NAME = 'Profile';
	const NAME = 'name';
	const CINEMA = 'cinema';
	const HOUR = 'hour';
	const START_HOUR = 'start_hour';
	const ONE_DAY = 24;
	const MINIMUM_HOURS = 2;
	const PAGE_PARAM = 'page';
	const CINEMA_PER_PAGE = 9;

	/**
	 * @return ProfileController
	 */
	public static function create($navigation)
	{
		return Injector::get('ProfileController', $navigation);
	}

	public function index($data, $preprompt = '')
	{
		if (CurrentSession::getInstance()->isLogged()) {
			$this->loggedMainMenu($data, $preprompt);
		} else {
			IndexVxmlFilmController::create($this->navigation)->index($data);
		}
	}

	private function loggedMainMenu($data, $preprompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("Tu provincia", "provincia", $this->getLink(self::CONTROLLER_NAME, "viewProvince"));
		$viewData->addOption("Tu provincia", KeyPhone::KEY_1, $this->getLink(self::CONTROLLER_NAME, "viewProvince"));

		$viewData->addOption("Horario preferido", "horario", $this->getLink(self::CONTROLLER_NAME, "viewSchedule"));
		$viewData->addOption("Horario preferido", KeyPhone::KEY_2, $this->getLink(self::CONTROLLER_NAME, "viewSchedule"));

		$viewData->addOption("Directores preferidos", "directores", $this->getLink(self::CONTROLLER_NAME, "menuDirectors"));
		$viewData->addOption("Directores preferidos", KeyPhone::KEY_3, $this->getLink(self::CONTROLLER_NAME, "menuDirectors"));

		$viewData->addOption("Actores preferidos", "actores", $this->getLink(self::CONTROLLER_NAME, "menuActors"));
		$viewData->addOption("Actores preferidos", KeyPhone::KEY_4, $this->getLink(self::CONTROLLER_NAME, "menuActors"));

		$viewData->addOption("Géneros preferidos", "géneros", $this->getLink(self::CONTROLLER_NAME, "menuGenres"));
		$viewData->addOption("Géneros preferidos", KeyPhone::KEY_5, $this->getLink(self::CONTROLLER_NAME, "menuGenres"));

		$viewData->addOption("Cines preferidos", "cines", $this->getLink(self::CONTROLLER_NAME, "menuCinema"));
		$viewData->addOption("Cines preferidos", KeyPhone::KEY_5, $this->getLink(self::CONTROLLER_NAME, "menuCinema"));

		$viewData->setPrompt("$preprompt Bienvenido a su perfil, con él seremos capaces de recomendarle películas"
			. " Para ver o modificar su provincia, diga provincia o marque 1"
			. " Para ver o modificar su horario preferido para ir al cine, diga horario o marque 2"
			. " Para ver o modificar sus directores preferidos, diga directores o marque 3"
			. " Para ver o modificar sus actores preferidos, diga actores o marque 4"
			. " Para ver o modificar sus géneros preferidos, diga géneros o marque 5"
			. " Para ver o modificar sus cines preferidos, diga cines o marque 5");

		$viewData->create()->setMainMenuLink($this->getMainMenuLink());
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function viewProvince($data, $preprompt = '')
	{
		$user = UserBackend::getInstance()->getUser(CurrentSession::getInstance()->getCurrentPhone());
		if ($user->hasProvinceId()) {
			$viewData = MenuViewData::create();
			$provinceName = Province::getProvinceName($user->getProvinceId());
			$viewData->addOption("Cambiar provincia", "cambiar provincia", $this->getLink(self::CONTROLLER_NAME, 'modifyProvince'));
			$viewData->setPrompt("$preprompt Tu actual provincia es: $provinceName, para cambiarla diga cambiar provincia");
			$viewData->setMainMenuLink($this->getMainMenuLink());
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

			MenuView::create()->render($viewData);
		} else {
			$this->modifyProvince($data, "No tienes provincia guardada. ");
		}
	}

	public function modifyProvince($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$provinces = Province::getAllProvinces();
		foreach ($provinces as $provinceName) {
			$viewData->addVoiceInput(Language::esES, $provinceName);
		}
		$viewData->setPrompt("$preprompt Por favor diga el nombre de su provincia");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveProvince'));
		FormView::create()->render($viewData);
	}

	public function saveProvince($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$provinceName = $data[self::NAME];

		UserBackend::getInstance()->saveProvince($phone, Province::getProvinceId($provinceName));
		$this->viewProvince($data, "Provincia guardada. ");
	}


	public function viewSchedule($data, $preprompt = '')
	{
		$user = UserBackend::getInstance()->getUser(CurrentSession::getInstance()->getCurrentPhone());
		if ($user->hasStartFavouriteSchedule() && $user->hasStartFavouriteSchedule()) {
			$viewData = MenuViewData::create();
			$endSchedule = $user->getEndFavouriteSchedule();
			$startSchedule = $user->getStartFavouriteSchedule();
			$viewData->addOption("cambiar horario", "cambiar horario", $this->getLink(self::CONTROLLER_NAME, 'modifySchedule'));
			$viewData->setPrompt("$preprompt Tu horario preferido es de: $startSchedule hasta $endSchedule, para cambiarlo diga cambiar horario");
			$viewData->setMainMenuLink($this->getMainMenuLink());
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

			$view = MenuView::create();
			$view->render($viewData);
		} else {
			$this->modifySchedule($data, "No tienes horario preferido guardado. ");
		}
	}

	public function modifySchedule($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->setPrompt("$preprompt Por favor marque la hora de 0 a 23 a la que empieza su horario preferido para ir al cine");
		$viewData->addNumericInput(2);
		$viewData->addNumericInput(1);
		$viewData->setVarReturnedName(self::HOUR);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveStartSchedule'));
		FormView::create()->render($viewData);
	}

	public function saveStartSchedule($data)
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->setPrompt("Por favor marque la hora de 0 a 23 a la que termina su horario preferido para ir al cine");
		$viewData->addNumericInput(2);
		$viewData->addNumericInput(1);
		$viewData->addHiddenParam(self::START_HOUR, InputSanitizer::toInt($data[self::HOUR]));
		$viewData->setVarReturnedName(self::HOUR);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveEndSchedule'));
		FormView::create()->render($viewData);
	}

	public function saveEndSchedule($data)
	{
		$startHour = InputSanitizer::toInt($data[self::START_HOUR]);
		$endHour = InputSanitizer::toInt($data[self::HOUR]);
		$hoursBetween = $endHour - $startHour;
		if ($hoursBetween < 0) {
			$hoursBetween += self::ONE_DAY;
		}
		if ($hoursBetween <= self::MINIMUM_HOURS) {
			$this->modifySchedule($data, "El horario preferido debe de tener un rango de más de dos horas");
		} else {
			UserBackend::getInstance()->saveSchedule(CurrentSession::getInstance()->getCurrentPhone(), $startHour, $endHour);
			$this->viewSchedule($data, "Horario guardado. ");
		}
	}

	public function menuDirectors($data, $prompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("nuevo director favorito", "nuevo director favorito", $this->getLink(self::CONTROLLER_NAME, 'addFavouriteDirector'));
		$viewData->addOption("nuevo director en lista negra", "nuevo director en lista negra", $this->getLink(self::CONTROLLER_NAME, 'addBlackListedDirector'));
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

		$directors = UserBackend::getInstance()->getFavouriteDirectors(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($directors)) {
			$directorList = implode(',', $directors);
			$prompt .= " Estos son tus directores favoritos: $directorList,
			 para añadir un nuevo director favorito diga nuevo director favorito, para borrarlos diga borrar directores favoritos";
			$viewData->addOption("borrar directores favoritos", "borrar directores favoritos", $this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteDirectors'));
		} else {
			$prompt .= "Aun no tienes ningun director favorito guardado,
			 para añadir un nuevo director diga nuevo director favorito.";
		}

		$blackListedDirectors = UserBackend::getInstance()->getDislikedDirectors(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($blackListedDirectors)) {
			$directorList = implode(',', $blackListedDirectors);
			$prompt .= " Estos es la lista negra de directores : $directorList,
			 para añadir un nuevo director a la lista negra diga nuevo director en lista negra, para borrarlos diga borrar lista negra";
			$viewData->addOption("borrar lista negra", "borrar lista negra", $this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedDirectors'));
		} else {
			$prompt .= "Aun no tienes lista negra de directores,
			 para añadir un nuevo director a la lista negra diga nuevo director en lista negra.";
		}

		$viewData->setPrompt($prompt);
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function addFavouriteDirector($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/directors.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del director");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveFavouriteDirector'));
		FormView::create()->render($viewData);
	}

	public function saveFavouriteDirector($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$directorName = $data[self::NAME];
		UserBackend::getInstance()->addFavouriteDirector($phone, $directorName);
		$this->menuDirectors($data, "Director añadido a la lista de directores favoritos. ");
	}

	public function deleteFavouriteDirectors($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteFavouriteDirectors($phone);
		$this->menuDirectors($data, "Lista de directores favoritos borrada");
	}

	public function addBlackListedDirector($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/directors.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del director");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveBlackListedDirector'));
		FormView::create()->render($viewData);
	}

	public function saveBlackListedDirector($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$directorName = $data[self::NAME];
		UserBackend::getInstance()->addDislikedDirector($phone, $directorName);
		$this->menuDirectors($data, "Director añadido a la lista negra de directores. ");
	}

	public function deleteBlackListedDirectors($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteDislikedDirectors($phone);
		$this->menuDirectors($data, "Lista negra de directores borrada");
	}

	public function menuActors($data, $prompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("nuevo actor favorito", "nuevo actor favorito", $this->getLink(self::CONTROLLER_NAME, 'addFavouriteActor'));
		$viewData->addOption("nuevo actor en lista negra", "nuevo actor en lista negra", $this->getLink(self::CONTROLLER_NAME, 'addBlackListedActor'));
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

		$actors = UserBackend::getInstance()->getFavouriteActors(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($actors)) {
			$actorList = implode(',', $actors);
			$prompt .= " Estos son tus actores favoritos: $actorList,
			 para añadir un nuevo actor favorito diga nuevo actor favorito, para borrarlos diga borrar actores favoritos";
			$viewData->addOption("borrar actores favoritos", "borrar actores favoritos", $this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteActors'));
		} else {
			$prompt .= "Aun no tienes ningun actor favorito guardado,
			 para añadir un nuevo actor diga nuevo actor favorito.";
		}

		$blackListedActors = UserBackend::getInstance()->getDislikedActors(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($blackListedActors)) {
			$actorList = implode(',', $blackListedActors);
			$prompt .= " Estos es la lista negra de actores : $actorList,
			 para añadir un nuevo actor a la lista negra diga nuevo actor en lista negra, para borrarlos diga borrar lista negra";
			$viewData->addOption("borrar lista negra", "borrar lista negra", $this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedActors'));
		} else {
			$prompt .= "Aun no tienes lista negra de actores,
			 para añadir un nuevo actor a la lista negra diga nuevo actor en lista negra.";
		}

		$viewData->setPrompt($prompt);
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function addFavouriteActor($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/actors.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del actor");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveFavouriteActor'));
		FormView::create()->render($viewData);
	}

	public function saveFavouriteActor($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$actorName = $data[self::NAME];
		UserBackend::getInstance()->addFavouriteActor($phone, $actorName);
		$this->menuActors($data, "Actor añadido a la lista de actores favoritos. ");
	}

	public function deleteFavouriteActors($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteFavouriteActors($phone);
		$this->menuActors($data, "Lista de actores favoritos borrada");
	}

	public function addBlackListedActor($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/actors.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del actor");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveBlackListedActor'));
		FormView::create()->render($viewData);
	}

	public function saveBlackListedActor($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$actorName = $data[self::NAME];
		UserBackend::getInstance()->addDislikedActors($phone, $actorName);
		$this->menuActors($data, "Actor añadido a la lista negra de actores. ");
	}

	public function deleteBlackListedActors($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteDislikedActors($phone);
		$this->menuActors($data, "Lista negra de actores borrada");
	}

	public function menuGenres($data, $prompt = '')
	{
		$viewData = MenuViewData::create();
		$viewData->addOption("nuevo género favorito", "nuevo género favorito", $this->getLink(self::CONTROLLER_NAME, 'addFavouriteGenre'));
		$viewData->addOption("nuevo género en lista negra", "nuevo género en lista negra", $this->getLink(self::CONTROLLER_NAME, 'addBlackListedGenre'));
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

		$genres = UserBackend::getInstance()->getFavouriteGenres(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($genres)) {
			$genreList = implode(',', $genres);
			$prompt .= " Estos son tus géneros favoritos: $genreList,
			 para añadir un nuevo genre favorito diga nuevo género favorito, para borrarlos diga borrar géneros favoritos";
			$viewData->addOption("borrar géneros favoritos", "borrar géneros favoritos", $this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteGenres'));
		} else {
			$prompt .= "Aun no tienes ningun género favorito guardado,
			 para añadir un nuevo género diga nuevo género favorito.";
		}

		$blackListedGenres = UserBackend::getInstance()->getDislikedGenres(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($blackListedGenres)) {
			$genreList = implode(',', $blackListedGenres);
			$prompt .= " Estos es tu lista negra de géneros : $genreList,
			 para añadir un nuevo género a la lista negra diga nuevo género en lista negra, para borrarlos diga borrar lista negra";
			$viewData->addOption("borrar lista negra", "borrar lista negra", $this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedGenres'));
		} else {
			$prompt .= "Aun no tienes lista negra de géneros,
			 para añadir un nuevo genre a la lista negra diga nuevo género en lista negra.";
		}

		$viewData->setPrompt($prompt);
		$view = MenuView::create();
		$view->render($viewData);
	}

	public function addFavouriteGenre($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/genres.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del genre");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveFavouriteGenre'));
		FormView::create()->render($viewData);
	}

	public function saveFavouriteGenre($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$genreName = $data[self::NAME];
		UserBackend::getInstance()->addFavouriteGenre($phone, $genreName);
		$this->menuGenres($data, "género añadido a la lista de géneros favoritos. ");
	}

	public function deleteFavouriteGenres($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteFavouriteGenres($phone);
		$this->menuGenres($data, "Lista de géneros favoritos borrada");
	}

	public function addBlackListedGenre($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/genre.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del genre");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveBlackListedGenre'));
		FormView::create()->render($viewData);
	}

	public function saveBlackListedGenre($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$genreName = $data[self::NAME];
		UserBackend::getInstance()->addDislikedGenres($phone, $genreName);
		$this->menuGenres($data, "género añadido a la lista negra de genrees. ");
	}

	public function deleteBlackListedGenres($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteDislikedGenres($phone);
		$this->menuGenres($data, "Lista negra de géneros borrada");
	}

	public function menuCinema($data, $prompt = '')
	{
		$currentPhone = CurrentSession::getInstance()->getCurrentPhone();
		$userBackend = UserBackend::getInstance();
		$user = $userBackend->getUser($currentPhone);

		if ($user->hasProvinceId()) {
			$viewData = MenuViewData::create();
			$viewData->addOption("nuevo cine", "nuevo cine", $this->getLink(self::CONTROLLER_NAME, 'chooseCinema', array(self::PAGE_PARAM => 0)));
			$preferences = $userBackend->getPreferences($currentPhone);
			if ($preferences->hasCinemas()) {
				$cinemaList = implode(',', $preferences->getCinemaNames($user->getProvinceId()));
				$prompt .= "Esta es la lista de tus cines favoritos: " . $cinemaList . ". Para añadir un nuevo cine di nuevo cine, para borrarlos di borrar cines";
				$viewData->addOption("borrar cines", "borrar cines", $this->getLink(self::CONTROLLER_NAME, 'deleteCinemas'));
			} else {
				$prompt .= "Aún no tienes ningún cine favorito guardado. Para añadir un nuevo cine di nuevo cine";
			}
			$viewData->setMainMenuLink($this->getMainMenuLink());
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
			$viewData->setPrompt($prompt);
			$view = MenuView::create();
			$view->render($viewData);
		} else {
			$this->viewProvince($data, "Necesitamos saber tu provincia para poder buscar cines.");
		}
	}

	public function chooseCinema($data, $prompt = '')
	{
		$currentPhone = CurrentSession::getInstance()->getCurrentPhone();
		$userBackend = UserBackend::getInstance();
		$user = $userBackend->getUser($currentPhone);

		if ($user->hasProvinceId()) {
			list($totalPages, $cinemas) = FilmAffinityApi::getInstance()->getCinemasPaginated($user->getProvinceId(), $data[self::PAGE_PARAM], self::CINEMA_PER_PAGE);
			$viewData = $this->getCinemasPagedListViewData($cinemas, $totalPages, $data[self::PAGE_PARAM]);
			$viewData->setMainMenuLink($this->getMainMenuLink());
			$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
			$view = MenuView::create();
			$view->render($viewData);
		} else {
			$this->viewProvince($data, "Necesitamos saber tu provincia para poder buscar cines.");
		}
	}

	public function saveCinema($data, $prompt = '')
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->addFavouriteCinemas($phone, $data[self::CINEMA]);
		$this->menuCinema($data, "cine añadido a cines favoritos");
	}

	/**
	 * @param Cinema[] $cinemas
	 * @param int $totalPages
	 * @param int $currentPageNumber
	 * @param array $params
	 *
	 * @return PagedListViewData
	 */
	private function getCinemasPagedListViewData($cinemas, $totalPages = 0, $currentPageNumber = 0, $params = array())
	{
		$viewData = PagedListViewData::create();
		$viewData->setCurrentPageNumber($currentPageNumber);
		$viewData->setTotalPages($totalPages);
		$optionNumber = 1;

		foreach ($cinemas as $cinemaId => $cinema) {
			$link = $this->getLink(self::CONTROLLER_NAME, 'saveCinema', array(self::CINEMA => $cinemaId));
			$viewData->addOption($cinema->getName(), KeyPhone::fromDigit($optionNumber), $link);
			$optionNumber++;
		}

		if ($currentPageNumber > 0 && !empty($method)) {
			$params[self::PAGE_PARAM] = 0;
			$viewData->setFirstPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'chooseCinema', $params));
			$params[self::PAGE_PARAM] = $currentPageNumber - 1;
			$viewData->setPreviousPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'chooseCinema', $params));
		}

		if ($currentPageNumber < $totalPages && !empty($method)) {
			$params[self::PAGE_PARAM] = $currentPageNumber + 1;
			$viewData->setNextPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'chooseCinema', $params));
			$params[self::PAGE_PARAM] = $totalPages;
			$viewData->setLastPageNumberLink($this->getLink(self::CONTROLLER_NAME, 'chooseCinema', $params));
		}

		return $viewData;
	}


}