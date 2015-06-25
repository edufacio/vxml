<?php
class FilmDetailedViewData extends MenuViewData
{

	public function setFilm(Film $film)
	{
		$rating = $film->hasRating() ? "Puntuacion: " . $film->getRating() . "sobre 10 de " . $film->getRateCount() . " votos" . $this->getWeakBreak() : '';
		$this->setPrompt(
			"Titulo: " . $film->getTitle() . $this->getWeakBreak()
				. "Género: " . $film->getGenre() . $this->getWeakBreak()
				. "Año: " . $film->getYear() . $this->getWeakBreak()
				. "Director: " . $film->getDirector() . $this->getWeakBreak()
				. $rating
				. "Reparto: " . $film->getCasting() . $this->getWeakBreak()
				. "Guión: " . $film->getScriptWriter() . $this->getWeakBreak()
				. "País: " . $film->getCountry() . $this->getWeakBreak()
				. "Música: " . $film->getMusic() . $this->getWeakBreak()
				. "Fotografía: " . $film->getPhotography() . $this->getWeakBreak()
				. "Producción: " . $film->getProductor() . $this->getWeakBreak()
				. "Duración: " . $film->getDuration() . $this->getWeakBreak()
				. "Críticas" . $film->getCriticts() . $this->getWeakBreak()
				. "Sinopsis: " . $film->getSynopsis() . $this->getWeakBreak()
		);
		$this->setTitle("Información Detallada  sobre la película: " . $film->getTitle());
	}
}