<?php

class TestBase extends PHPUnit_Framework_TestCase
{
	protected function bindToMock($className, $methods = array())
	{
		$mockBuilder = $this->getMockBuilder($className)
			->disableOriginalConstructor();
		if(!empty($methods)) {
			$mockBuilder->setMethods($methods);
		}
		$mock = $mockBuilder->getMock();

		Injector::bind($className, $mock);
		return $mock;
	}
}
