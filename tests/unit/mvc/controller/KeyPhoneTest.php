<?php

class KeyPhoneTest extends TestBase
{
	public function testIsValidKeyReturnsTrueForAllKeys()
	{
		$keys = $this->getKeyNames();
		foreach ($keys as $keyPhone => $name) {
			$this->assertTrue(KeyPhone::isKeyPhone($keyPhone));
		}
	}

	public function testIsValidKeyReturnsFalseForUnknownKey()
	{
		$this->assertFalse(KeyPhone::isKeyPhone("any"));
	}

	public function testToDigitWorksOk()
	{
		$keys = $this->getKeyDigits();
		foreach ($keys as $keyPhone => $digit) {
			$this->assertEquals($digit, KeyPhone::toDigit($keyPhone));
		}
	}

	public function testToDigitReturnNullForUnknonwKeyPhone()
	{
		$this->assertNull(KeyPhone::toDigit("any"));
	}

	public function testFromDigitWorksOk()
	{
		$keys = $this->getKeyDigits();
		foreach ($keys as $keyPhone => $digit) {
			$this->assertEquals($keyPhone, KeyPhone::FromDigit($digit));
		}
	}

	public function testFromDigitReturnNullForUnknonwKeyPhone()
	{
		$this->assertNull(KeyPhone::fromDigit("any"));
	}

	public function testToNumberNameWorksOk()
	{
		$keys = $this->getKeyNames();
		foreach ($keys as $keyPhone => $name) {
			$this->assertEquals($name, KeyPhone::toNumberName($keyPhone));
		}
	}

	public function testToNumberNameReturnsNullForUnknownKeyNumber()
	{
		$this->assertNull(KeyPhone::toNumberName("any"));
	}

	private function getKeyNames() {
		return array(
			KeyPhone::KEY_0 => 'cero',
			KeyPhone::KEY_1 => 'uno',
			KeyPhone::KEY_2 => 'dos',
			KeyPhone::KEY_3 => 'tres',
			KeyPhone::KEY_4 => 'cuatro',
			KeyPhone::KEY_5 => 'cinco',
			KeyPhone::KEY_6 => 'seis',
			KeyPhone::KEY_7 => 'siete',
			KeyPhone::KEY_8 => 'ocho',
			KeyPhone::KEY_9 => 'nueve',
			KeyPhone::KEY_STAR => 'asterisco',
		);
	}

	private function getKeyDigits() {
		return array(
			KeyPhone::KEY_0 => 0,
			KeyPhone::KEY_1 => 1,
			KeyPhone::KEY_2 => 2,
			KeyPhone::KEY_3 => 3,
			KeyPhone::KEY_4 => 4,
			KeyPhone::KEY_5 => 5,
			KeyPhone::KEY_6 => 6,
			KeyPhone::KEY_7 => 7,
			KeyPhone::KEY_8 => 8,
			KeyPhone::KEY_9 => 9,
			KeyPhone::KEY_STAR => '*',
		);
	}
}

