<?php
Class NavigationMap
{
    const PARAMS = 'getParams';
    const POST_PARAMS = 'postParams';
    const DEFAULT_CONTROLLER = 'IndexVxmlFilm';
    const DEFAULT_ACTION = 'index';
    const CONTROLLER_PARAM = 'c';
    const ACTION_PARAM = 'a';
    const URI = 'REQUEST_URI';
	const DEFAULT_VALUE = 'default value';


	private static $CONFIG = array(
        "IndexVxmlFilm" => array(
            "index" => array(
                self::PARAMS => array(),
            ),
            "searchTitle" => array(
                self::PARAMS => array(),
            ),
            "searchActor" => array(
                self::PARAMS => array(),
            ),
            "searchDirector" => array(
                self::PARAMS => array(),
            ),
	        "getCartelera" => array(
		        self::PARAMS => array('page'),
		        self::DEFAULT_VALUE => array('page' => 0),
            ),
            "searchTitleForm" => array(
                self::PARAMS => array('query', 'page'),
	            self::DEFAULT_VALUE => array('page' => 0),
            ),
            "searchActorForm" => array(
	            self::PARAMS => array('query', 'page'),
	            self::DEFAULT_VALUE => array('page' => 0),
            ),
            "searchDirectorForm" => array(
	            self::PARAMS => array('query', 'page'),
	            self::DEFAULT_VALUE => array('page' => 0),
            ),
            "getFilm" => array(
                self::PARAMS => array('filmId', 'breadCrumb'),
            ),
	        "getFilmDetailed" => array(
		        self::PARAMS => array('filmId', 'breadCrumb'),
	        ),
        ),
    );

	/**
	 * @return NavigationMap
	 */
	public static function create()
	{
		return Injector::get('NavigationMap');
	}

	public function getControllerName()
    {
        if (isset($_GET[self::CONTROLLER_PARAM])
            && $this->isValidController($_GET[self::CONTROLLER_PARAM])
        ) {
            return $_GET[self::CONTROLLER_PARAM];
        }
        return self::DEFAULT_CONTROLLER;
    }

    public function isValidParam($controller, $action, $paramName)
    {
        if ($this->isValidAction($controller, $action)) {
            return in_array($paramName, self::$CONFIG[$controller][$action][self::PARAMS]);
        }
        return false;
    }

    public function isValidController($controllerName)
    {
        return isset(self::$CONFIG[$controllerName]);
    }

    public function isValidAction($controllerName, $action)
    {

        if ($this->isValidController($controllerName)) {
            return isset(self::$CONFIG[$controllerName][$action]);
        }
        return false;
    }

    public function getAction()
    {
        if (isset($_GET[self::ACTION_PARAM]) && isset($_GET[self::CONTROLLER_PARAM])
            && $this->isValidAction($_GET[self::CONTROLLER_PARAM], $_GET[self::ACTION_PARAM])
        ) {
            return $_GET[self::ACTION_PARAM];
        }
        return self::DEFAULT_ACTION;
    }

    public function getData($controllerName, $action)
    {
        $data = array();

        $defaultParams = isset(self::$CONFIG[$controllerName][$action][self::DEFAULT_VALUE]) ?
	        self::$CONFIG[$controllerName][$action][self::DEFAULT_VALUE] : array();
        $validParams = self::$CONFIG[$controllerName][$action][self::PARAMS];

        foreach ($validParams as $paramName) {
	        if (isset($_POST[$paramName])) {
		        $data[$paramName] = $_POST[$paramName];
	        } elseif (isset($_GET[$paramName])) {
                $data[$paramName] = $_GET[$paramName];
            } elseif(isset($defaultParams[$paramName])) {
	            $data[$paramName] = $defaultParams[$paramName];
            } else {
		        throw new ParamNotFoundException($controllerName, $action, $paramName);
	        }
        }

        return $data;
    }
}

Class ParamNotFoundException extends InvalidArgumentException {
	function __construct($controller, $action , $paramName)
	{
		parent::__construct("$paramName without default value is not definded on $controller => $action");
	}
}