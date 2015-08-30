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
		$viewData->addOption("Cines preferidos", KeyPhone::KEY_6, $this->getLink(self::CONTROLLER_NAME, "menuCinema"));

		$viewData->setPrompt("$preprompt Bienvenido a su perfil, con él seremos capaces de recomendarle películas"
			. " Para ver o modificar su provincia, diga provincia o marque 1"
			. " Para ver o modificar su horario preferido para ir al cine, diga horario o marque 2"
			. " Para ver o modificar sus directores preferidos, diga directores o marque 3"
			. " Para ver o modificar sus actores preferidos, diga actores o marque 4"
			. " Para ver o modificar sus géneros preferidos, diga géneros o marque 5"
			. " Para ver o modificar sus cines preferidos, diga cines o marque 6");

		$viewData->setMainMenuLink($this->getMainMenuLink());
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
		$currentPhone = CurrentSession::getInstance()->getCurrentPhone();
		$this->getMenuFavourite($prompt,
			'director',
			'directores',
			UserBackend::getInstance()->getFavouriteDirectors($currentPhone),
			UserBackend::getInstance()->getDislikedDirectors($currentPhone),
			$this->getLink(self::CONTROLLER_NAME, 'addFavouriteDirector'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteDirectors'),
			$this->getLink(self::CONTROLLER_NAME, 'addBlackListedDirector'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedDirectors')
		);
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
		$currentPhone = CurrentSession::getInstance()->getCurrentPhone();
		$this->getMenuFavourite($prompt,
			'actor',
			'actores',
			UserBackend::getInstance()->getFavouriteActors($currentPhone),
			UserBackend::getInstance()->getDislikedActors($currentPhone),
			$this->getLink(self::CONTROLLER_NAME, 'addFavouriteActor'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteActors'),
			$this->getLink(self::CONTROLLER_NAME, 'addBlackListedActor'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedActors')
		);
	}

	private function getMenuFavourite($prompt, $name, $namePlural, $favList, $blackList, Link $addFavLink, Link $deleteFavLink, Link $addBlackList, Link $deleteBlackList) {
		$viewData = MenuViewData::create();
		$viewData->addOption("nuevo $name favorito", "nuevo $name favorito", $addFavLink);
		$viewData->addOption("nuevo $name favorito", KeyPhone::KEY_1, $addFavLink);
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));

		if (!empty($favList)) {
			$addBlackListedKey = 3;
			$nameList = implode(',', $favList);
			$prompt .= " Estos son tus $namePlural favoritos: $nameList,
			 para añadir un nuevo $name favorito diga nuevo $name favorito o pulse 1, para borrarlos diga borrar $namePlural favoritos o pulse 2. ";
			$viewData->addOption("borrar $namePlural favoritos", "borrar $namePlural favoritos", $deleteFavLink);
			$viewData->addOption("borrar $namePlural favoritos", KeyPhone::KEY_2, $deleteFavLink);
		} else {
			$addBlackListedKey = 2;
			$prompt .= "Aun no tienes ningun $name favorito guardado,
			 para añadir un nuevo $name diga nuevo $name favorito o pulse 1. ";
		}

		$viewData->addOption("nuevo $name en lista negra", "nuevo $name en lista negra", $addBlackList);
		$viewData->addOption("nuevo $name en lista negra", KeyPhone::fromDigit($addBlackListedKey), $addBlackList);
		$blackListedActors = UserBackend::getInstance()->getDislikedActors(CurrentSession::getInstance()->getCurrentPhone());
		if (!empty($blackList)) {
			$deleteBlackListKey = $addBlackListedKey + 1;
			$nameList = implode(',', $blackList);
			$prompt .= " Esta es su lista negra de $namePlural : $nameList,
			 para añadir un nuevo $name a la lista negra diga nuevo $name en lista negra o pulse  $addBlackListedKey, para borrarlos diga borrar lista negra o pulse $deleteBlackListKey";
			$viewData->addOption("borrar lista negra", "borrar lista negra", $deleteBlackList);
			$viewData->addOption("borrar lista negra", KeyPhone::fromDigit($deleteBlackListKey), $deleteBlackList);
		} else {
			$prompt .= "Aun no tienes lista negra de $namePlural,
			 para añadir un nuevo $name a la lista negra diga nuevo $name en lista negra o pulse $addBlackListedKey.";
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
		UserBackend::getInstance()->addDislikedActor($phone, $actorName);
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
		$currentPhone = CurrentSession::getInstance()->getCurrentPhone();
		$this->getMenuFavourite($prompt,
			'género',
			'géneros',
			UserBackend::getInstance()->getFavouriteGenres($currentPhone),
			UserBackend::getInstance()->getDislikedGenres($currentPhone),
			$this->getLink(self::CONTROLLER_NAME, 'addFavouriteGenre'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteFavouriteGenres'),
			$this->getLink(self::CONTROLLER_NAME, 'addBlackListedGenre'),
			$this->getLink(self::CONTROLLER_NAME, 'deleteBlackListedGenres')
		);
	}

	public function addFavouriteGenre($data, $preprompt = '')
	{
		$viewData = FormViewData::create();
		$viewData->setMainMenuLink($this->getMainMenuLink());
		$viewData->setPreviousPageLink($this->getLink(self::CONTROLLER_NAME, 'index'));
		$viewData->addInputFromCsv(GRAMMAR_CSV_PATH . "/genre.csv");
		$viewData->setPrompt("$preprompt Por favor diga el nombre del género");
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
		$viewData->setPrompt("$preprompt Por favor diga el nombre del género");
		$viewData->setVarReturnedName(self::NAME);
		$viewData->setSubmitLink($this->getLink(self::CONTROLLER_NAME, 'saveBlackListedGenre'));
		FormView::create()->render($viewData);
	}

	public function saveBlackListedGenre($data)
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		$genreName = $data[self::NAME];
		UserBackend::getInstance()->addDislikedGenre($phone, $genreName);
		$this->menuGenres($data, "género añadido a la lista negra de géneros. ");
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
			$cinemas = $preferences->getCinemaNames($user->getProvinceId());
			if (!empty($cinemas)) {
				$cinemaList = implode(',', $cinemas);
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
		$this->menuCinema($data, "Cine añadido a cines favoritos. ");
	}

	public function deleteCinemas($data, $prompt = '')
	{
		$phone = CurrentSession::getInstance()->getCurrentPhone();
		UserBackend::getInstance()->deleteFavouriteCinemas($phone);
		$this->menuCinema($data, "cines eliminados. ");
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