<?php
Abstract Class Controller {
    const BASE_URL = "index.php?";

    private $navigation;

    function __construct()
    {
        $this->navigation = new NavigationMap();
    }

    abstract public function index($data);

    public function getLink($controllerName, $action, $params = array()) {
        $validParams = $this->assertLinkIsValid($controllerName, $action, $params);

        $url = self::BASE_URL . $this->getUrlParam(NavigationMap::CONTROLLER_PARAM, $controllerName)
            . '&' . $this->getUrlParam(NavigationMap::ACTION_PARAM, $action) . $this->getUrlGetParams($validParams);

        return Link::createFromHref($url);
    }

    /**
     * @param $view
     * @return View
     */
    protected function instantiateView($view) {
        return new $view();
    }

    private function assertLinkIsValid($controllerName, $action, $params) {
	    if (!$this->navigation->isValidAction($controllerName, $action)) {
		    throw new DomainException("$controllerName with $action is not configured in NavigationMap yet");
	    }

	    $validParams = array();
        foreach ($params as $paramName => $paramValue) {
	        if ($this->navigation->isValidAction($controllerName, $action, $paramName)) {
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
        foreach($params as $paramName => $paramValue) {
            $url .= '&' . $this->getUrlParam($paramName, $paramValue);
        }
        return $url;
    }
}