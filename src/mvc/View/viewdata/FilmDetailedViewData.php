<?php
class FilmDetailedViewData extends MenuViewData
{

	/**
	 * @return FilmDetailedViewData
	 */
	public static function create() {
		return Injector::get('FilmDetailedViewData');
	}

	public function setFilm(Film $film)
	{
		$rating = $film->hasRating() ? "Puntuacion: " . $film->getRating() . "sobre 10 de " . $film->getRateCount() . " votos" . $this->getWeakBreak() : '';
		$premiere = $film->hasPremiereDate() ? "Fecha de estreno: " . $film->getPremiereDate()  . $this->getWeakBreak(): '';
		$recommendation = $film->hasRecomendation() ? "Puntuacion sobre tus gustos: " . $film->getRecommendation() . "sobre 10" . $this->getWeakBreak() : '';

		$this->setPrompt(
			"Titulo: " . $film->getTitle() . $this->getWeakBreak()
				. "Género: " . $film->getGenre() . $this->getWeakBreak()
				. "Año: " . $film->getYear() . $this->getWeakBreak()
				. "Director: " . $film->getDirector() . $this->getWeakBreak()
				. $rating . $recommendation . $premiere
				. "Reparto: " . $film->getCasting() . $this->getWeakBreak()
				. "Guión: " . $film->getScriptWriter() . $this->getWeakBreak()
				. "País: " . $film->getCountry() . $this->getWeakBreak()
				. "Música: " . $film->getMusic() . $this->getWeakBreak()
				. "Fotografía: " . $film->getPhotography() . $this->getWeakBreak()
				. "Producción: " . $film->getProductor() . $this->getWeakBreak()
				. "Duración: " . $film->getDuration() . " minutos" . $this->getWeakBreak()
				. "Críticas" . $film->getCriticts() . $this->getWeakBreak()
				. "Sinopsis: " . $film->getSynopsis() . $this->getWeakBreak()
		);
		$this->setTitle("Información Detallada  sobre la película: " . $film->getTitle());
	}
}