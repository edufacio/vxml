<?php

class Link {
    private $href;

    private function __construct($href)
    {
        $this->href = $href;
    }

	public static function createFromEncondedHref($encodedHref) {
		return new Link(urldecode($encodedHref));
	}

	public static function createFromHref($href) {
		return new Link($href);
	}

    /**
     * @param mixed $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @return mixed
     */
    public function getHref()
    {
        return $this->href;
    }

	/**
	 * @return mixed
	 */
	public function getHrefForRender()
	{
		return htmlspecialchars($this->href);
	}

	public function getHrefEncoded()
	{
		return urlencode($this->href);
	}
}