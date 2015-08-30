<?php
class FilmRecomendationCalculator
{
	const MAX_PER_RATING = 300;
	const MIN_PER_RATING = 50;
	const MAX_PER_DIRECTOR = 20;
	const DIRECTOR_MATCH = 20;
	const MAX_PER_ACTOR = 20;
	const ACTOR_MATCH = 10;
	const MAX_PER_GENRE = 20;
	const GENRE_MATCH = 10;

	private static $instance;

	/**
	 * @return FilmRecomendationCalculator
	 */
	public static function getInstance()
	{
		if (self::$instance === NULL) {
			self::$instance = Injector::get(__CLASS__);
		}
		return self::$instance;
	}

	public function calculate(Film $film) {
		if(CurrentSession::getInstance()->isLogged()) {
			return $this->calculateForPhone($film, CurrentSession::getInstance()->getCurrentPhone());
		} else {
			return $film;
		}

	}

	private function calculateForPhone(Film $film, $phone)
	{
		$preferences = UserBackend::getInstance()->getPreferences($phone);
		$dislikedPoints = $likedPoints = 0;
		list($dislikedPoints, $likedPoints) = $this->getDirectorRating($film, $preferences, $dislikedPoints, $likedPoints);
		list($dislikedPoints, $likedPoints) = $this->getActorRating($film, $preferences, $dislikedPoints, $likedPoints);
		list($dislikedPoints, $likedPoints) = $this->getGenreRating($film, $preferences, $dislikedPoints, $likedPoints);
		if ($dislikedPoints > 0 || $likedPoints > 0) {
			list($maxPuntuation, $puntuation) = $this->getFilmRating($film, $dislikedPoints, $likedPoints);
			$pointsTo10 = round($puntuation * 10 / $maxPuntuation, 2);
			$film->setRecommendation(min(10,$pointsTo10));
		}
		return $film;
	}

	private function getFilmRating(Film $film, $dislikedPoints, $likedPoints)
	{
		if ($film->hasRating()) {
			$ratingNumber = floatval($film->getRating());
			$maxPoints = max(min(intval(10 * $film->getRateCount()), self::MAX_PER_RATING), self::MIN_PER_RATING);
			$points =  $ratingNumber * $maxPoints / 10 + $likedPoints - $dislikedPoints;
			return array($maxPoints, $points);
		} else {
			return array(self::MIN_PER_RATING, self::MIN_PER_RATING / 2 + $likedPoints - $dislikedPoints);
		}
	}



	private function getDirectorRating(Film $film, FilmPreferences $preferences, $dislikedPoints, $likedPoints)
	{
		$dislikedPoints += $this->pointsByMatch($film->getDirector(), $preferences->getDislikedDirectors(), self::DIRECTOR_MATCH, self::MAX_PER_DIRECTOR);
		$likedPoints += $this->pointsByMatch($film->getDirector(), $preferences->getFavouriteDirectors(), self::DIRECTOR_MATCH, self::MAX_PER_DIRECTOR);
		return array($dislikedPoints, $likedPoints);
	}

	private function getActorRating(Film $film, FilmPreferences $preferences, $dislikedPoints, $likedPoints)
	{
		$dislikedPoints += $this->pointsByMatch($film->getCasting(), $preferences->getDislikedActors(), self::ACTOR_MATCH, self::MAX_PER_ACTOR);
		$likedPoints += $this->pointsByMatch($film->getCasting(), $preferences->getFavouriteActors(), self::ACTOR_MATCH, self::MAX_PER_ACTOR);
		return array($dislikedPoints, $likedPoints);
	}

	private function getGenreRating(Film $film, FilmPreferences $preferences, $dislikedPoints, $likedPoints)
	{
		$dislikedPoints += $this->pointsByMatch($film->getGenre(), $preferences->getDislikedGenres(), self::GENRE_MATCH, self::MAX_PER_GENRE);
		$likedPoints += $this->pointsByMatch($film->getGenre(), $preferences->getFavouriteGenres(), self::GENRE_MATCH, self::MAX_PER_GENRE);
		return array($dislikedPoints, $likedPoints);
	}

	private function pointsByMatch($stringToMatch, $possibles, $pointsByMatch, $maxPoints)
	{
		$points = 0;
		if (!is_array($possibles)) {
			return $points;
		}
		$stringToMatch = $this->escapeForMatch($stringToMatch);
		foreach ($possibles as $possible) {
			$possible = $this->escapeForMatch($possible);
			if (preg_match("/$possible/i", $stringToMatch) > 0) {
				$points += $pointsByMatch;
				if ($points >= $maxPoints) {
					return $maxPoints;
				}
			}
		}
		return $points;
	}

	private function escapeForMatch($string) {
		$escapeSearch = array('/á/i', '/é/i', '/í/i', '/ó/i', '/ú/i');
		$escapeReplacement = array('a', 'e', 'i', 'o', 'u');

		return trim(preg_replace($escapeSearch, $escapeReplacement, strtolower($string)));
	}
}