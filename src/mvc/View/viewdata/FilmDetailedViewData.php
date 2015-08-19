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
		$recommendation =  "Puntuacion sobre tus gustos: ";
		if (CurrentSession::getInstance()->isLogged()) {
			$recommendation .= $film->hasRecomendation() ?  "{$film->getRecommendation()} sobre 10" : 'No tenemos suficiente información en tu perfil';
			$recommendation .= $this->getWeakBreak();
		} else {
			$recommendation .= "Por favor identificate o registrate para poder recomendarte peliculas dependiendo tus gustos";
		}

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