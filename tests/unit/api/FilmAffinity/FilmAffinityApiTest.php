<?php

class FilmAffinityApiTest extends TestBase
{
	const FILM_URL = '/es/film12.html';
	const DIRECTOR_SEARCH_PAGED = 'es/search.php?stype=director&stext=james+cameron&from=9';
	const DIRECTOR_SEARCH_UNPAGED = 'es/search.php?stype=director&stext=james+cameron';
	const ACTOR_SEARCH_PAGED = 'es/search.php?stype=cast&stext=james+cameron&from=9';
	const ACTOR_SEARCH_UNPAGED = 'es/search.php?stype=cast&stext=james+cameron';
	const TITLE_SEARCH_PAGED = 'es/search.php?stype=title&stext=james+cameron&from=9';
	const TITLE_SEARCH_UNPAGED = 'es/search.php?stype=title&stext=james+cameron';
	const CARTELERA_SEARCH_PAGED = '/es/cat_new_th_es.html';
	const CARTELERA_HTML = 'cartelera.html';

	const PAGE_PARAM = 1;
	const FILM_PER_PAGE = 10;
	const TOTAL_PAGES = 2;
	const TOTAL_PAGES_CARTELERA = 49;
	const QUERY = 'james cameron';
	const FILM_ID = 12;
	const FILM_HTML = 'film.html';
	const FILM_LIST_PAGE = 'director.html';

	public function testGetFilm() {
		$api = $this->getApi(self::FILM_URL, self::FILM_HTML);
		$film = $api->getFilm(self::FILM_ID);
		$expectedFilm = $this->getExpectedFilm();
		$this->assertEquals($expectedFilm->getRateCount(), $film->getRateCount());
		$this->assertEquals($expectedFilm->getCasting(), $film->getCasting());
		$this->assertEquals($expectedFilm->getCountry(), $film->getCountry());
		$this->assertEquals($expectedFilm->getCriticts(), $film->getCriticts());
		$this->assertEquals($expectedFilm->getSynopsis(), $film->getSynopsis());
		$this->assertEquals($expectedFilm->getDirector(), $film->getDirector());
		$this->assertEquals($expectedFilm->getDuration(), $film->getDuration());
		$this->assertEquals($expectedFilm->getGenre(), $film->getGenre());
		$this->assertEquals($expectedFilm->getMusic(), $film->getMusic());
		$this->assertEquals($expectedFilm->getTitle(), $film->getTitle());
		$this->assertEquals($expectedFilm->getOriginalTitle(), $film->getOriginalTitle());
		$this->assertEquals($expectedFilm->getPhotography(), $film->getPhotography());
		$this->assertEquals($expectedFilm->getProductor(), $film->getProductor());
		$this->assertEquals($expectedFilm->getRating(), $film->getRating());
		$this->assertEquals($expectedFilm->getScriptWriter(), $film->getScriptWriter());
		$this->assertEquals($expectedFilm->getWeb(), $film->getWeb());
		$this->assertEquals($expectedFilm->getYear(), $film->getYear());
	}

	public function testSearchDirector() {
		$api = $this->getApi(self::DIRECTOR_SEARCH_PAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchDirector(self::QUERY, self::PAGE_PARAM);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testSearchDirectorUnpaged() {
		$api = $this->getApi(self::DIRECTOR_SEARCH_UNPAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchDirector(self::QUERY, 0);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testSearchActor() {
		$api = $this->getApi(self::ACTOR_SEARCH_PAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchActor(self::QUERY, self::PAGE_PARAM);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testSearchActorUnpaged() {
		$api = $this->getApi(self::ACTOR_SEARCH_UNPAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchActor(self::QUERY, 0);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testSearchTitle() {
		$api = $this->getApi(self::TITLE_SEARCH_PAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchTitle(self::QUERY, self::PAGE_PARAM);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testSearchTitleUnpaged() {
		$api = $this->getApi(self::TITLE_SEARCH_UNPAGED, self::FILM_LIST_PAGE);
		list($totalPages, $pages) = $api->searchTitle(self::QUERY, 0);
		$this->assertEquals($this->getExpectedFilmList(), $pages);
		$this->assertEquals(self::TOTAL_PAGES, $totalPages);
	}

	public function testGetCartelera() {
		$api = $this->getApi(self::CARTELERA_SEARCH_PAGED, self::CARTELERA_HTML);
		list($totalPages, $pages) = $api->getCartelera(self::PAGE_PARAM, 1);
		$expectedFilms =  array(111055 => 'Asesinos inocentes ');
		$this->assertEquals($expectedFilms, $pages);
		$this->assertEquals(self::TOTAL_PAGES_CARTELERA, $totalPages);
	}

	public function testGetCarteleraUnpaged() {
		$api = $this->getApi(self::CARTELERA_SEARCH_PAGED, self::CARTELERA_HTML);
		list($totalPages, $pages) = $api->getCartelera(0, 1);
		$expectedFilms =  array(297030 => 'Los Minions ',);
		$this->assertEquals($expectedFilms, $pages);
		$this->assertEquals(self::TOTAL_PAGES_CARTELERA, $totalPages);
	}

	private function getExpectedFilmList() {
		return array (
				478407 => 'Avatar 4 ',
				504830 => 'Battle Angel ',
				191071 => 'Avatar 3 ',
				899895 => 'Avatar 2 ',
				560280 => 'Secretos del Titanic con James Cameron (TV)',
				495280 => 'Avatar ',
				687252 => 'Misterios del océano ',
				887349 => 'Ghosts of the Abyss (Misterios del Titanic) ',
				628484 => 'Una expedición de James Cameron: El acorazado Bismark (TV)',
		);
	}

	/**
	 * @param $url
	 * @param $htmlFile
	 *
	 * @return FilmAffinityApi
	 */
	private function  getApi($url, $htmlFile) {
		$api = $this->bindToMock('FilmAffinityApi', array('curl'));
		$api->expects($this->once())
			->method('curl')
			->with($url)
			->willReturn(file_get_contents(dirname(__FILE__) . '/' . $htmlFile));
		return $api;
	}

	private function getExpectedFilm() {
		$filmRaw = array(
			'titulo' => 'Terminator Génesis  ',
			'Título original' => '                 Terminator Genisys aka               ',
			'AKA' => '                                              Terminator 5                                      ',
			'Año' => '2015',
			'Duración' => '126 min.',
			'País' => ' Estados Unidos',
			'Director' => 'Alan Taylor',
			'Guión' => 'Laeta Kalogridis, Patrick Lussier',
			'Música' => 'Lorne Balfe',
			'Fotografía' => 'Kramer Morgenthau',
			'Reparto' => 'Emilia Clarke,  Arnold Schwarzenegger,  Jason Clarke,  Jai Courtney,  J.K. Simmons,  Dayo Okeniyi,  Lee Byung-Hun,  Matt Smith,  Michael Gladis,  Sandrine Holt,  Natalie Stephany Aguilar,  Teri Wyble,  Brett Azar,  Starlette Miariaunii,  Nolan Gross',
			'Productora' => 'Paramount Pictures / Annapurna Pictures / Skydance Productions',
			'Género' => '               Ciencia ficción .          Acción  |     Robots.     Años 80.     Viajes en el tiempo.     Secuela            ',
			'Sinopsis' => 'Año 2032. La guerra del futuro se está librando y un grupo de rebeldes humanos tiene el sistema de inteligencia artificial Skynet contra las cuerdas. John Connor (Jason Clarke) es el líder de la resistencia, y Kyle Reese (Jai Courtney) es su fiel soldado, criado en las ruinas de una postapocalíptica California. Para salvaguardar el futuro, Connor envía a Reese a 1984 para salvar a su madre, Sarah (Emilia Clarke) de un Terminator programado para matarla con el fin de que no llegue a dar a luz a John. Pero lo que Reese encuentra en el otro lado no es como él esperaba... (FILMAFFINITY)
 Estreno en USA y España: julio 2015.',
			'Críticas' => '                                                                                    Revisar el código de la saga no tiene por qué ser malo. Y no siempre lo es en Terminator Génesis. Puntuación:  (sobre 5)                                           Yago García: Cinemanía                                                                                                       James Cameron ha expresado su entusiasmo por la película: “Soy un fanboy”. Considera a ‘Terminator: Genisys’ como la verdadera secuela de ’Terminator 2: El juicio final’.                                           FILMAFFINITY                                                                                                       Un intento fallido de hacerle a la saga un gran lifting facial (...) La franquicia, por ahora, se ha convertido en su peor Skynet (...) No se la puede considerar totalmente obsoleta, quizás, pero desgraciadamente se siente terriblemente fútil.                                           Justin Chang: Variety                                                                                                       Las escenas de acción se acumulan como si estuvieran bajo el mandato del cronómetro y casi siempre parecen un refrito de cosas que ya hemos visto antes                                           Todd McCarthy: The Hollywood Reporter                                                                                                       Razonablemente entretenida y muy bien realizada (...) el guión es más hábil en su logística que en el tratamiento de los personajes (...) la mayor atracción de la película sigue siendo Schwarzenegger.                                           Tim Grierson : Screendaily                                                     Mostrar 10 críticas más                        ',
			'Tu crítica' => '                                              Puedes escribir una crítica de esta película para que el resto de los usuarios la pueda leer.                        Añade tu crítica                                  ',
			'Votaciones de almas gemelas' => '                                   Regístrate y podrás acceder a recomendaciones personalizadas según tus gustos de cine                     ',
			'Votaciones de tus amigos' => '                              Regístrate y podrás acceder a todas las votaciones de tus amigos, familiares, etc.                      ',
			'Posición rankings listas' => '                                          22 Próximos estrenos: éstas no me las pierdo (332)                                              ',
			'puntuacion' => '                         5,7                    ',
			'total de votaciones' => '247 ',
		);
		return new Film($filmRaw);
	}
}

