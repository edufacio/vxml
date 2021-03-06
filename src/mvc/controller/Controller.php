<?php
Abstract Class Controller
{
	const BASE_URL = 'index.php?';
	const SESSION = 'session';
	const CALLER_ID = 'caller_id';
	protected $navigation;

	function __construct(NavigationMap $navigationMap)
	{
		$this->navigation = $navigationMap;
	}

	abstract public function index($data);

	protected function getLink($controllerName, $action = 'index', $params = array())
	{
		$validParams = $this->assertLinkIsValid($controllerName, $action, $params);

		$url = self::BASE_URL . $this->getUrlParam(NavigationMap::CONTROLLER_PARAM, $controllerName)
			. '&' . $this->getUrlParam(NavigationMap::ACTION_PARAM, $action) . $this->getUrlGetParams($validParams);

		return Link::createFromHref($url);
	}

	private function assertLinkIsValid($controllerName, $action, $params)
	{
		if (!$this->navigation->isValidAction($controllerName, $action)) {
			throw new DomainException("$controllerName with $action is not configured in NavigationMap yet");
		}

		$validParams = array();
		foreach ($params as $paramName => $paramValue) {
			if ($this->navigation->isValidParam($controllerName, $action, $paramName)) {
				$validParams[$paramName] = $paramValue;
			}
		}

		return $validParams;
	}

	private function getUrlParam($paramName, $paramValue)
	{
		return "$paramName=" . urlencode($paramValue);
	}

	private function getUrlGetParams($params)
	{
		$url = '';
		foreach ($params as $paramName => $paramValue) {
			$url .= '&' . $this->getUrlParam($paramName, $paramValue);
		}
		return $url;
	}

	protected function getMainMenuLink()
	{
		return $this->getLink(IndexVxmlFilmController::CONTROLLER_NAME, "index");
	}
}