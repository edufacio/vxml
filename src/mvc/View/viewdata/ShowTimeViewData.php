<?php

class FilmBasicViewData extends MenuViewData
{
	/**
	 * @return FilmBasicViewData
	 */
	public static function create() {
		return Injector::get('FilmBasicViewData');
	}

	public function setFilm(Film $film, Link $filmDetails)
	{
		$this->addOption("Detalles de pelicula", "más detalles", $filmDetails);
		$rating = $film->hasRating() ? "Puntuacion de filmafiniti: " . $film->getRating() . "sobre 10 de " . $film->getRateCount() . " votos" . $this->getWeakBreak() : '';
		$recommendation = $film->hasRecomendation() ? "Puntuacion sobre tus gustos: " . $film->getRecommendation() . "sobre 10" . $this->getWeakBreak() : '';
		$premiere = $film->hasPremiereDate() ? "Fecha de estreno: " . $film->getPremiereDate()  . $this->getWeakBreak(): '';
		$this->setPrompt(
			"Titulo: " . $film->getTitle() . $this->getWeakBreak()
				. "Género: " . $film->getGenre() . $this->getWeakBreak()
				. "Director: " . $film->getDirector() . $this->getWeakBreak()
				. $rating . $recommendation . $premiere
				. "Año: " . $film->getYear() . $this->getWeakBreak()
				. "Sinopsis: " . $film->getSynopsis() . $this->getWeakBreak()
				. "Para más detalles de " . $this->getTitle() . "diga más detalles"
		);
		$this->setTitle("Información  sobre la película: " . $film->getTitle());
	}
}