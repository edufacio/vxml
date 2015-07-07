<?php

class LinkTest extends TestBase
{
	const HREF = 'index.php&c=anyController&a=anyAction&anyParam=&anyValue';
	public function testHrefIsRetrievedOk()
	{
		$link = Link::createFromHref(self::HREF);
		$this->assertEquals(self::HREF, $link->getHref());
		$this->assertEquals($this->getExpectedEncodedHref(), $link->getHrefEncoded());
		$this->assertEquals($this->getExpectedHrefForRender(), $link->getHrefForRender());
	}

	public function testHrefIsRetrievedOkForCreationWithEncodedHref()
	{
		$link = Link::createFromEncondedHref($this->getExpectedEncodedHref());
		$this->assertEquals(self::HREF, $link->getHref());
		$this->assertEquals($this->getExpectedEncodedHref(), $link->getHrefEncoded());
		$this->assertEquals($this->getExpectedHrefForRender(), $link->getHrefForRender());
	}


	private function getExpectedEncodedHref() {
		return urlencode(self::HREF);
	}

	private function getExpectedHrefForRender() {
		return htmlspecialchars(self::HREF);
	}
}

