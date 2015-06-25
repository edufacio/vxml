<?php
class FilmBasicViewData extends MenuViewData
{
	public function setFilm(Film $film, Link $filmDetails)
	{
		$this->addOption("Detalles de pelicula", "más detalles", $filmDetails);
		$rating = $film->hasRating() ? "Puntuacion: " . $film->getRating() . "sobre 10 de " . $film->getRateCount() . " votos" . $this->getWeakBreak() : '';
		$this->setPrompt(
			"Titulo: " . $film->getTitle() . $this->getWeakBreak()
				. "Género: " . $film->getGenre() . $this->getWeakBreak()
				. "Director: " . $film->getDirector() . $this->getWeakBreak()
				. $rating
				. "Año: " . $film->getYear() . $this->getWeakBreak()
				. "Sinopsis: " . $film->getSynopsis() . $this->getWeakBreak()
				. "Para más detalles de " . $this->getTitle() . "diga más detalles"
		);
		$this->setTitle("Información  sobre la película: " . $film->getTitle());
	}
}