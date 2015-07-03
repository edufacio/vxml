<?php

class NavigationMapTest extends TestBase
{
	const VALID_CONTROLLER = 'IndexVxmlFilm';
	const UNKNOWN_CONTROLLER = 'anyUnknownController';
	const VALID_ACTION = 'searchActorForm';
	const UNKNOWN_ACTION = 'anyUnknownAction';
	const VALID_PARAM = 'query';
	const PAGE_PARAM = 'page';
	const EXPECTED_PARAM_VALUE = 'search';
	const UNKNOWN_PARAM = 'anyUnknownParam';

	public function testIsValidControllerReturnsTrueForValidController()
	{
		$navigationMap = new NavigationMap();
		$this->assertTrue($navigationMap->isValidController(self::VALID_CONTROLLER));
	}

	public function testIsValidControllerReturnsFalseForFakeController()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidController(self::UNKNOWN_CONTROLLER));
	}

	public function testIsValidActionRetunsTrueForValidControllerAndValidAction()
	{
		$navigationMap = new NavigationMap();
		$this->assertTrue($navigationMap->isValidAction(self::VALID_CONTROLLER, self::VALID_ACTION));
	}

	public function testIsValidActionRetunsFalseForValidControllerAndUnknownAction()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidAction(self::VALID_CONTROLLER, self::UNKNOWN_ACTION));
	}

	public function testIsValidActionRetunsFalseForUnknownControllerAndValidAction()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidAction(self::UNKNOWN_CONTROLLER, self::VALID_ACTION));
	}

	public function testIsValidActionRetunsFalseForUnknownControllerAndUnknownAction()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidAction(self::UNKNOWN_CONTROLLER, self::UNKNOWN_ACTION));
	}

	public function testGetControllerReturnsOkTheController()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::VALID_CONTROLLER;
		$this->assertEquals(self::VALID_CONTROLLER, $navigationMap->getControllerName());
	}

	public function testGetControllerReturnsDefaultControllerIfControllerIsNotDefined()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = null;
		$this->assertEquals(NavigationMap::DEFAULT_CONTROLLER, $navigationMap->getControllerName());
	}

	public function testGetControllerReturnsDefaultControllerIfControllerIsUnknown()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::UNKNOWN_CONTROLLER;
		$this->assertEquals(NavigationMap::DEFAULT_CONTROLLER, $navigationMap->getControllerName());
	}

	public function testIsValidParamReturnsTrueForValidControllerValidActionAndValidParam()
	{
		$navigationMap = new NavigationMap();
		$this->assertTrue($navigationMap->isValidParam(self::VALID_CONTROLLER, self::VALID_ACTION, self::VALID_PARAM));
	}

	public function testIsValidParamReturnsFalseForValidControllerValidActionAndUnknownParam()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidParam(self::VALID_CONTROLLER, self::VALID_ACTION, self::UNKNOWN_PARAM));
	}

	public function testIsValidParamReturnsFalseForValidControllerUnknownActionAndValidParam()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidParam(self::VALID_CONTROLLER, self::UNKNOWN_ACTION, self::UNKNOWN_PARAM));
	}

	public function testIsValidParamReturnsFalseForUnknownControllerValidActionAndValidParam()
	{
		$navigationMap = new NavigationMap();
		$this->assertFalse($navigationMap->isValidParam(self::UNKNOWN_CONTROLLER, self::VALID_ACTION, self::UNKNOWN_PARAM));
	}

	public function testGetActionReturnsDefaultActionForValidControllerAndUnknownAction()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::VALID_CONTROLLER;
		$_GET[NavigationMap::ACTION_PARAM] = self::UNKNOWN_ACTION;
		$this->assertEquals(NavigationMap::DEFAULT_ACTION, $navigationMap->getAction());
	}

	public function testGetActionReturnsDefaultActionForUnknownControllerAndValidAction()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::UNKNOWN_CONTROLLER;
		$_GET[NavigationMap::ACTION_PARAM] = self::VALID_ACTION;
		$this->assertEquals(NavigationMap::DEFAULT_ACTION, $navigationMap->getAction());
	}

	public function testGetActionReturnsDefaultActionForUnknownControllerAndUnknownAction()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::UNKNOWN_ACTION;
		$_GET[NavigationMap::ACTION_PARAM] = self::UNKNOWN_ACTION;
		$this->assertEquals(NavigationMap::DEFAULT_ACTION, $navigationMap->getAction());
	}

	public function testGetActionReturnsActionForValidControllerAndValidAction()
	{
		$navigationMap = new NavigationMap();
		$_GET[NavigationMap::CONTROLLER_PARAM] = self::VALID_CONTROLLER;
		$_GET[NavigationMap::ACTION_PARAM] = self::VALID_ACTION;
		$this->assertEquals(self::VALID_ACTION, $navigationMap->getAction());
	}

	public function testGetDataThrowsAnExceptionForAParamNotSetted()
	{
		$this->setExpectedException('InvalidArgumentException');
		$navigationMap = new NavigationMap();
		$navigationMap->getData(self::VALID_CONTROLLER, self::VALID_ACTION);

	}

	public function testGetDataGetDataWorksOk()
	{
		$navigationMap = new NavigationMap();
		$_GET[self::VALID_PARAM] = self::EXPECTED_PARAM_VALUE;
		$expectedData = array(self::VALID_PARAM => self::EXPECTED_PARAM_VALUE, self::PAGE_PARAM => 0);
		$data = $navigationMap->getData(self::VALID_CONTROLLER, self::VALID_ACTION);
		$this->assertEquals($expectedData, $data);
	}
}