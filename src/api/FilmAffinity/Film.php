<?

class Film
{
	const MAX_RECOMMENDATION = 10;
	const TITLE = "titulo";
	const ORIGINAL_TITLE = "Título original";
	const YEAR = "Año";
	const DURATION = "Duración";
	const COUNTRY = "País";
	const DIRECTOR = "Director";
	const SCRIPTWRITER = "Guión";
	const MUSIC = "Música";
	const PHOTOGRAPHY = "Fotografía";
	const CASTING = "Reparto";
	const PRODUCTOR = "Productora";
	const GENRE = "Género";
	const WEB = "Web oficial";
	const SYNOPSIS = "Sinopsis";
	const CRITICS = "Críticas";
	const RATING = "puntuacion";
	const RATE_COUNT = "total de votaciones";
	const PREMIERE = "premiere";
	const FILM_ID = "filmId";
	const REVIEWS_NUMBER = "reviewsNumber";

	private $rawData;
	private $recommendation;
	private $filmId;

	function __construct($rawData)
	{
		$this->rawData = $rawData;
	}

	private function getKey($key)
	{
		return $this->escape($this->rawData[$key]);
	}

	public function getTitle()
	{
		return $this->getKey(self::TITLE);
	}

	public function getOriginalTitle()
	{
		return $this->getKey(self::ORIGINAL_TITLE);
	}

	public function getYear()
	{
		return $this->getKey(self::YEAR);
	}

	public function getDuration()
	{
		return intval($this->getKey(self::DURATION));
	}

	public function getCountry()
	{
		return $this->getKey(self::COUNTRY);
	}

	public function getDirector()
	{
		return $this->getKey(self::DIRECTOR);
	}

	public function getScriptWriter()
	{
		return $this->getKey(self::SCRIPTWRITER);
	}

	public function getMusic()
	{
		return $this->getKey(self::MUSIC);
	}

	public function getPhotography()
	{
		return $this->getKey(self::PHOTOGRAPHY);
	}

	public function getCasting()
	{
		return $this->getKey(self::CASTING);
	}

	public function getProductor()
	{
		return $this->getKey(self::PRODUCTOR);
	}

	public function getGenre()
	{
		return $this->getKey(self::GENRE);
	}

	public function getWeb()
	{
		return $this->getKey(self::WEB);
	}

	public function getSynopsis()
	{
		return $this->getKey(self::SYNOPSIS);
	}

	public function getCriticts()
	{
		return $this->getKey(self::CRITICS);
	}

	public function hasRating()
	{
		return isset($this->rawData[self::RATING]);
	}

	public function getRating()
	{
		return (float) $this->getKey(self::RATING);
	}

	public function getRateCount()
	{
		return $this->getKey(self::RATE_COUNT);
	}

	private function escape($text)
	{
		return preg_replace(array('/\s+/', '/\|/', '/&/'), array(' ', ',', 'y'), $text);
	}

	public function hasRecomendation()
	{
		return $this->recommendation !== null;
	}

	public function setRecommendation($recommendation)
	{
		$this->recommendation = $recommendation;
	}

	public function getRecommendation()
	{
		return $this->recommendation;
	}

	public function getFilmId()
	{
		return $this->filmId;
	}

	public function setFilmId($filmId)
	{
		return $this->filmId = $filmId;
	}

	public function getReviewNumbers() {
		return $this->getKey(self::REVIEWS_NUMBER);
	}

	public function getPremiereDate()
	{
		return $this->getKey(self::PREMIERE);
	}

	public function hasPremiereDate()
	{
		return $this->getPremiereDate() !== null;
	}
}