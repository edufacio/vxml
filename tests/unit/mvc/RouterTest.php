<?php

class RouterTest extends TestBase
{
	public function testThatRouterCallController()
	{
		$expectedController = 'IndexVxmlFilmController';
		$expectedControllerName = 'IndexVxmlFilm';
		$expectedAction = 'searchActor';
		$expectedParams = array('anyStuff' => 'any');
		$navigationMap = $this->bindToMock('NavigationMap');
		$navigationMap->expects($this->once())->method('getControllerName')->willReturn($expectedControllerName);
		$navigationMap->expects($this->once())->method('getAction')->willReturn($expectedAction);
		$navigationMap->expects($this->once())->method('getData')
			->with($expectedControllerName, $expectedAction)
			->willReturn($expectedParams);

		$controller = $this->bindToMock($expectedController);
		$controller->expects($this->once())->method($expectedAction)->willReturn($expectedParams);

		$router = new Router();
		$router->request();
	}
}
