<?php

class Review {
	private $title;
	private $author;
	private $date;
	private $rating;
	private $publicReview;
	private $spoilerReview;

	function __construct($author, $date, $publicReview, $rating, $spoilerReview, $title)
	{
		$this->author = $author;
		$this->date = $date;
		$this->publicReview = $publicReview;
		$this->rating = $rating;
		$this->spoilerReview = $spoilerReview;
		$this->title = $title;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function getPublicReview()
	{
		return $this->publicReview;
	}

	public function getRating()
	{
		return $this->rating;
	}

	public function getSpoilerReview()
	{
		return $this->spoilerReview;
	}
	public function hasSpoilerReview()
	{
		return $this->spoilerReview !== null;
	}

	public function getTitle()
	{
		return $this->title;
	}
}