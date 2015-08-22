<?php

class ReviewViewData extends PagedListViewData
{
	/**
	 * @return ReviewViewData
	 */
	public static function create()
	{
		return Injector::get('ReviewViewData');
	}

	public function setReviewWithoutSpoiler(Review $review, Link $reviewComplete) {
		$this->addOption("El resto de la crítica puede contener spoilers.Para oir la  diga oir completa", "oir completa", $reviewComplete);
		$this->setReview($review);
	}

	public function setReviewComplete(Review $review) {
		$prompt =
			"Titulo: " . $review->getTitle() . $this->getWeakBreak()
				. "Autor: " . $review->getAuthor() . $this->getWeakBreak()
				. "Fecha:" . $review->getDate() . $this->getWeakBreak()
				. "Puntuación:" . $review->getRating() . $this->getWeakBreak()
				. "Crítica:" .  $review->getSpoilerReview();

		$this->prompt = $prompt;
	}

	public function setReview(Review $review)
	{
		$prompt =
			"Titulo: " . $review->getTitle() . $this->getWeakBreak()
			. "Autor: " . $review->getAuthor() . $this->getWeakBreak()
			. "Fecha:" . $review->getDate() . $this->getWeakBreak()
			. "Puntuación:" . $review->getRating() . $this->getWeakBreak()
			. "Crítica:" . $review->getPublicReview();

		$this->prompt = $prompt;
	}

	public function getPrompt()
	{
		$prompt = "Mostrando Crítica " . $this->getCurrentPageNumber() . " de " . $this->getTotalPages() . ". ";
		$prompt .= $this->prompt;
		foreach ($this->getOptions() as $option) {
			$prompt .= $this->buildPromptForOption($option);
		}
		return $prompt;
	}
}