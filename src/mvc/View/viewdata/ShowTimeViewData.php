<?php

class ShowTimeViewData extends PagedListViewData
{

	/**
	 * @return ShowTimeViewData
	 */
	public static function create()
	{
		return Injector::get('ShowTimeViewData');
	}

	public function setShowTime(ShowTime $showTime, Link $linkFilmDetails)
	{
		$film = $showTime->getFilm();
		$this->addOption("Para ir a la ficha de " . $film->getTitle(), "ver película", $linkFilmDetails);
		$rating = $film->hasRating() ? "Puntuacion de filmafiniti: " . $film->getRating() . "sobre 10 de " . $film->getRateCount() . " votos" . $this->getWeakBreak() : '';
		$recommendation = "Puntuacion sobre tus gustos: ";
		$recommendation .= $film->hasRecomendation() ? "{$film->getRecommendation()} sobre 10" : 'No tenemos suficiente información en tu perfil';
		$recommendation .= $this->getWeakBreak();
		$prompt =
			"Titulo: " . $film->getTitle() . $this->getWeakBreak()
				. "Género: " . $film->getGenre() . $this->getWeakBreak()
				. "Director: " . $film->getDirector() . $this->getWeakBreak()
				. $rating . $recommendation
				. "Proyecciones: ";

		$sessionTimes = $showTime->getSessionTimes();
		foreach ($sessionTimes as $sessionTime) {
			$prompt .= "Cine " . $sessionTime->getCinema()->getName() . ". Sesiones:";
			$dates = array();
			foreach ($sessionTime->getTimes() as $time) {
				$dayDate = $this->getDayDate($time);
				$dates[$dayDate][] = $this->getHourDate($time);
			}

			foreach ($dates as $day => $sessions) {
				$prompt .= $day . " a las " . implode(', ', $sessions) . '. ';
			}
		}
		$prompt .= $this->getWeakBreak() . "Sinopsis: " . $film->getSynopsis();
		$this->prompt = $prompt;

	}

	public function getPrompt()
	{
		$prompt = "Mostrando película " . $this->getCurrentPageNumber() . " de " . $this->getTotalPages() . ". ";
		$prompt .= $this->prompt;
		foreach ($this->getOptions() as $option) {
			$prompt .= $this->buildPromptForOption($option);
		}
		return $prompt;
	}

	private function getDayDate($time)
	{
		$days = array("lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo");
		$dayName = $days[date('N', $time) - 1];
		return $dayName . date(" j/m/Y", $time);
	}

	private function getHourDate($time)
	{
		$minutes = date("i", $time);
		if ($minutes == 0) {
			$hourDate = date("g ", $time) . " en punto";
		} else if ($minutes == 15) {
			$hourDate = date("g ", $time) . " y cuarto ";
		} else if ($minutes == 30) {
			$hourDate = date("g ", $time) . " y media ";
		} else if ($minutes == 45) {
			$hourDate = date("g ", $time + 3600) . " menos cuarto ";
		} else if ($minutes > 30) {
			$rest = 60 - $minutes;
			$hourDate = date("g ", $time + 3600) . " menos $rest";
		} else {
			$hourDate = date("g ", $time) . " y $minutes ";
		}
		return $hourDate;
	}
}