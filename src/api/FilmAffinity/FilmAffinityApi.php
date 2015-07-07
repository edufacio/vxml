<?

class FilmAffinityApi
{
	const BASE_URL = 'http://www.filmaffinity.com/';
	const ACTOR_QUERY = 'es/search.php?stype=cast&stext=';
	const TITLE_QUERY = 'es/search.php?stype=title&stext=';
	const DIRECTOR_QUERY = 'es/search.php?stype=director&stext=';
	const CARTELERA_QUERY = '/es/cat_new_th_es.html';
	const FILM_QUERY = '/es/film%id%.html';
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

	/**
	 * @param $filmId
	 *
	 * @return Film
	 */
	public function getFilm($filmId)
	{
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
			$totalVotes = reset($pageDom->find('div[id=movie-count-rat] span'))->text();
			$filmRaw["puntuacion"] = reset($ratingDiv)->text();
			$filmRaw["total de votaciones"] = $totalVotes;
		}

		return new Film($filmRaw);
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
			$cartelera[$this->getFilmId($film->href)] = $film->text();
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

	public function curl($page) {
		$c = curl_init(self::BASE_URL . $page);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		return curl_exec($c);
	}

	private function getFilmId($href)
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
		$totalFilmsTitle = reset($pageDom->find('div[class=sub-header-search]'))->text();
		$totalFilms = filter_var($totalFilmsTitle, FILTER_SANITIZE_NUMBER_INT);
		$totalPages = floor($totalFilms / $filmsPerPage);
		$filmsPaged = array_slice($films, 0, $filmsPerPage);
		foreach ($filmsPaged as $film) {
			$search[$this->getFilmId($film->href)] = $film->text();
		}
		return array($totalPages, $search);
	}
}





