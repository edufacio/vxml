<?php

class LanguageTest extends TestBase
{
	public function thatesEsIsLanguageValid()
	{
		$this->assertTrue(Language::isLanguageValid(Language::esES));
	}

	public function thatenUSIsLanguageValid()
	{
		$this->assertTrue(Language::isLanguageValid(Language::enUS));
	}

	public function thatUnknownLanguageIsNotValid()
	{
		$this->assertTrue(Language::isLanguageValid('Orco'));
	}
}

