<?
class FilmAffinityApi
{
	const BASE_URL = 'http://www.filmaffinity.com/';
	const ACTOR_QUERY = 'es/search.php?stype=cast&stext=';
	const TITLE_QUERY = 'es/search.php?stype=title&stext=';
	const DIRECTOR_QUERY = 'es/search.php?stype=director&stext=';
	const CARTELERA_QUERY = 'es/cat_new_th_es.html';
	const CARTELERA_BY_RATING_QUERY = 'es/topcat.php?id=new_th_es';
	const CARTELERA_BY_POPULAR_QUERY = 'es/countcat.php?id=new_th_es';
	const CARTELERA_BY_RELEASE_QUERY = 'es/rdcat.php?id=new_th_es';
	const NEXT_RELEASE_QUERY = 'es/cat_upc_th_es.html';
	const NEXT_RELEASE_BY_RATING_QUERY = 'es/topcat.php?id=upc_th_es';
	const NEXT_RELEASE_BY_POPULAR_QUERY = 'es/countcat.php?id=upc_th_es';
	const NEXT_RELEASE_BY_RELEASE_QUERY = 'es/rdcat.php?id=upc_th_es';

	const FILM_QUERY = 'es/film%id%.html';
	const CINEMAS_QUERY = 'es/theaters.php?state=%id%';
	const SHOWTIMES_QUERY = 'es/theater-showtimes.php?id=%id%';
	const CACHE_TIME = 86400;
	private static $instance;

	/**
	 * @return FilmAffinityApi
	 */
	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = Injector::get('FilmAffinityApi');
		}
		return self::$instance;
	}

	public function getShowtimes($provinceId, $cinemaIds, $startHour, $endHour)
	{
		$allCinemas = $this->getAllCinemas($provinceId);
		if (empty($cinemaIds)) {
			$cinemaIds = array_keys($allCinemas);
		}
		$showTimes = array();
		$films = array();
		foreach ($cinemaIds as $cinemaId) {
			if (!isset($allCinemas[$cinemaId])) {
				continue;
			}
			$cinema = $allCinemas[$cinemaId];
			$pageDom = $this->request(str_replace('%id%', $cinemaId, self::SHOWTIMES_QUERY));
			$movies = $pageDom->find('div[class=movie]');
			foreach ($movies as $movie) {
				$minDuration = intval(reset($movie->find('[span class=runtime]'))->text());
				$sessions = $this->getValidShowTimeSessions($movie, $minDuration, $startHour, $endHour);
				if (!empty($sessions)) {
					$filmId = $this->getId($movie->id);
					if (!isset($films[$filmId])) {
						$films[$filmId] = $this->getFilm($filmId);
					}
					$film = $films[$filmId];
					$sessionTime = new SessionTime($cinema, $sessions);
					if (isset($showTimes[$filmId])) {
						$showTimes[$filmId]->addSessionTime($sessionTime);
					} else {
						$showTimes[$filmId] = new ShowTime($film, array($sessionTime));
					}
				}
			}
		}
		uasort($showTimes, array($this, 'sortByRating'));
		return $showTimes;
	}

	/**
	 * @param ShowTime $itemA
	 * @param ShowTime $itemB
	 *
	 * @return mixed
	 */
	private function sortByRating($itemA, $itemB) {
		if ($itemA->getFilm()->hasRecomendation() && $itemB->getFilm()->hasRecomendation()) {
			return 100 * ($itemB->getFilm()->getRecommendation() - $itemA->getFilm()->getRecommendation());
		}

		if ($itemA->getFilm()->hasRating() && $itemB->getFilm()->hasRating()) {
				return 100 * ($itemB->getFilm()->getRating() - $itemA->getFilm()->getRating());
		} elseif ($itemA->getFilm()->hasRating()) {
			return -1;
		} elseif ($itemB->getFilm()->hasRating()) {
			return 1;
		} else {
			return reset($itemB->getSessionTimes()) - reset($itemA->getSessionTimes());
		}
	}

	private function getValidShowTimeSessions($movie, $minDuration, $startTime = null , $endTime = null)
	{
		$validSessions = array();
		$currentDay = date('j');
		$startTimestamp =  $startTime !== null ? strtotime("$startTime:00") : 0;
		$endTimestamp = $endTime !== null ? strtotime("$endTime:00") : strtotime("+1 month");
		if ($endTimestamp < $startTimestamp) {
			$endTimestamp += 3600 * 24;
		}

		$secondsDuration = $minDuration * 60;
		$sessionsDates = $movie->find("div[class=sess-date] span[class=mday]");
		$sessionsTimes = $movie->find("ul[class=sess-times]");
		foreach ($sessionsDates as $key => $sessionDate) {
			$sessionDay = intval($sessionDate->text());
			if ($currentDay > $sessionDay) {
				$sessionDate = date('Y-m-', strtotime('+1 month')) . $sessionDay;
			} else {
				$sessionDate = date('Y-m-') . $sessionDay;
			}
			if (isset($sessionsTimes[$key])) {
				$sessionHours = $sessionsTimes[$key]->find("li");

				foreach ($sessionHours as $sessionHour) {
					$hour = $sessionHour->text();
					$sessionBegin = strtotime($hour);
					$sessionEnd = $sessionBegin + $secondsDuration;
					if ($sessionBegin >= $startTimestamp && $sessionEnd <= $endTimestamp) {
						$validSessions[] = strtotime("$sessionDate, $hour");
					}
				}
			}
		}
		return $validSessions;
	}

	public function getCinemasPaginated($provinceId, $page, $cinemasPerPage)
	{
		$allCinemas = $this->getAllCinemas($provinceId);
		$cinemasPaginated = array_slice($allCinemas, $page * $cinemasPerPage, $cinemasPerPage, true);
		$totalPages = ceil(count($allCinemas) / $cinemasPerPage);
		return array($totalPages, $cinemasPaginated);
	}

	public function getCinemas($provinceId, array $cinemaIds) {
		$cinemas = $this->getAllCinemas($provinceId);
		$cinemasFound = array();
		foreach ($cinemaIds as $cinemaId) {
			if (isset($cinemas[$cinemaId])) {
				$cinemasFound[$cinemaId] = $cinemas[$cinemaId];
			}
		}
		return $cinemasFound;
	}

	public function getAllCinemas($provinceId)
	{
		$pageDom = $this->request(str_replace('%id%', $provinceId, self::CINEMAS_QUERY));
		$cinemas = $pageDom->find('a[class=theater-data]');
		$validCinemas = array();
		foreach ($cinemas as $cinema) {
			$noValid = $cinema->find("i[class=fa fa-info-circle no-assigned]");
			if (empty($noValid)) {
				$id = $this->getId($cinema->href);
				$validCinemas[$id] = new Cinema($id, $cinema->title, $cinema->text());
			}
		}
		return $validCinemas;
	}

	/**
	 * @param $filmId
	 *
	 * @return Film
	 */
	public function getFilm($filmId)
	{
		$filmCachestorage  = FilmCacheStorage::getInstance();
		$cache = $filmCachestorage->getCache($filmId);
		if ($cache->isExpired()) {
			$rawFilmData = $this->requestFilmData($filmId);
			$cache->setContent($rawFilmData)->setExpiration(time() + self::CACHE_TIME);
			$filmCachestorage->save($cache);
		}
		$film = new Film($cache->getContent());
		$film->setFilmId($filmId);
		return FilmRecomendationCalculator::getInstance()->calculate($film);
	}

	private function requestFilmData($filmId) {
		$filmRaw = array();
		$pageDom = $this->request(str_replace('%id%', $filmId, self::FILM_QUERY));
		$keysContent = $pageDom->find('dl[class=movie-info] dt');

		$content = $pageDom->find('dl[class=movie-info] dd');
		$mainTitle = reset($pageDom->find('h1[id=main-title] span'))->text();
		$filmRaw["titulo"] = $mainTitle;

		foreach ($keysContent as $key => $value) {
			$filmRaw[($value->text())] = preg_replace('/&[#\w]*;/', '', $content[$key]->text());
		}

		$ratingDiv = $pageDom->find('div[id=movie-rat-avg]');
		if (!empty($ratingDiv)) {
			$filmRaw["puntuacion"] = preg_replace('/,/', '.', reset($ratingDiv)->text());
			$totalVotes = reset($pageDom->find('div[id=movie-count-rat] span'))->text();
			$filmRaw["total de votaciones"] = preg_replace('/\./', '', $totalVotes);
		}

		$premiereContent = $pageDom->find('div[id=movie-categories]');
		if (!empty($premiereContent)) {
			preg_match('/\d*\/\d*\/\d*/', reset($premiereContent)->text(), $match);
			$filmRaw[Film::PREMIERE] = $match[0];
		}

		return $filmRaw;
	}

	/**
	 * @param $directorName
	 *
	 * @return array
	 */
	public function searchDirector($directorName, $page = 0, $filmsByPage = 9)
	{
		$query = self::DIRECTOR_QUERY . $this->escapeQuery($directorName);
		return $this->requestSearch($query, $page, $filmsByPage);
	}

	/**
	 * @param $title
	 *
	 * @return array
	 */
	public function searchTitle($title, $page = 0, $filmsByPage = 9)
	{
		$query = self::TITLE_QUERY . $this->escapeQuery($title);
		return $this->requestSearch($query, $page, $filmsByPage);
	}

	/**
	 * @param $actorName
	 *
	 * @return array
	 */
	public function searchActor($actorName, $page = 0, $filmsByPage = 9)
	{
		$query = self::ACTOR_QUERY . $this->escapeQuery($actorName);
		return $this->requestSearch($query, $page, $filmsByPage);
	}

	private function escapeQuery($query)
	{
		return urlencode($query);
	}

	/**
	 * @return array
	 */
	public function getCartelera($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::CARTELERA_QUERY);
		$films = $pageDom->find('div[class=movie-card] h3 a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getCarteleraRatingSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::CARTELERA_BY_RATING_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getCarteleraVotesSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::CARTELERA_BY_POPULAR_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getCarteleraReleaseDateSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::CARTELERA_BY_RELEASE_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}


	/**
	 * @return array
	 */
	public function getNextRelease($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::NEXT_RELEASE_QUERY);
		$films = $pageDom->find('div[class=movie-card] h3 a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getNextReleaseRatingSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::NEXT_RELEASE_BY_RATING_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getNextReleaseVotesSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::NEXT_RELEASE_BY_POPULAR_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @return array
	 */
	public function getNextReleaseReleaseDateSorted($pageNumber, $filmsPerPage)
	{
		$cartelera = array();
		$pageDom = $this->request(self::NEXT_RELEASE_BY_RELEASE_QUERY);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalPages = floor(count($films) / $filmsPerPage);
		$filmsPaged = array_slice($films, $pageNumber * $filmsPerPage, $filmsPerPage);

		foreach ($filmsPaged as $film) {
			$cartelera[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $cartelera);
	}

	/**
	 * @param $page
	 *
	 * @return simple_html_dom
	 */
	private function request($page)
	{
		$html = $this->curl($page);
		$dom = New simple_html_dom();
		$dom->load($html);
		return $dom;
	}

	public function curl($page)
	{
		$c = curl_init(self::BASE_URL . $page);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($c);
	}

	private function getId($href)
	{
		preg_match('/\d+/', $href, $match);
		return $match[0];
	}

	private function requestSearch($query, $page, $filmsPerPage)
	{
		$search = array();
		if ($page > 0) {
			$offset = $page * $filmsPerPage;
			$query .= "&from=$offset";
		}
		$pageDom = $this->request($query);
		$films = $pageDom->find('div[class=mc-title] a');
		$totalFilmsDom = reset($pageDom->find('div[class=sub-header-search]'));
		if ($totalFilmsDom == false) {
			return array(0, array());
		}


		$totalFilmsTitle = $totalFilmsDom->text();

		$totalFilms = filter_var($totalFilmsTitle, FILTER_SANITIZE_NUMBER_INT);
		$totalPages = floor($totalFilms / $filmsPerPage);
		$filmsPaged = array_slice($films, 0, $filmsPerPage);
		foreach ($filmsPaged as $film) {
			$search[$this->getId($film->href)] = $film->text();
		}
		return array($totalPages, $search);
	}
}





