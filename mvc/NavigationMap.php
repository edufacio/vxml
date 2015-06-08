<?php
Class NavigationMap
{
    const GET_PARAMS = 'getParams';
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
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array(),
            ),
            "searchTitle" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array(),
            ),
            "searchActor" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array(),
            ),
            "searchDirector" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array(),
            ),
	        "getCartelera" => array(
		        self::GET_PARAMS => array('page'),
		        self::POST_PARAMS => array(),
		        self::DEFAULT_VALUE => array('page' => 0),
            ),
            "searchTitleForm" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array('query'),
            ),
            "searchActorForm" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array('query'),
            ),
            "searchDirectorForm" => array(
                self::GET_PARAMS => array(),
                self::POST_PARAMS => array('query'),
            ),
            "getFilm" => array(
                self::GET_PARAMS => array('filmId', 'breadCrumb'),
                self::POST_PARAMS => array(),
            ),
	        "getFilmDetailed" => array(
		        self::GET_PARAMS => array('filmId', 'breadCrumb'),
		        self::POST_PARAMS => array(),
	        ),
        ),
    );

    public function getControllerName()
    {
        if (isset($_GET[self::CONTROLLER_PARAM])
            && $this->isValidController($_GET[self::CONTROLLER_PARAM])
        ) {
            return $_GET[self::CONTROLLER_PARAM];
        }
        return self::DEFAULT_CONTROLLER;
    }

    public function isValidGetParam($controller, $action, $paramName)
    {
        if ($this->isValidAction($controller, $action)) {
            return in_array($paramName, self::$CONFIG[$controller][$action][self::GET_PARAMS]);
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

        $defaultParams = isset(self::$CONFIG[$controllerName][$action][self::GET_PARAMS]) ?
	        self::$CONFIG[$controllerName][$action][self::GET_PARAMS] : array();
        $validParams = self::$CONFIG[$controllerName][$action][self::GET_PARAMS];

        foreach ($validParams as $paramName) {
            if (isset($_GET[$paramName])) {
                $data[$paramName] = $_GET[$paramName];
            } elseif($defaultParams[$paramName]) {
	            $data[$paramName] = $defaultParams[$paramName];
            }
        }

        $validParams = self::$CONFIG[$controllerName][$action][self::POST_PARAMS];
        foreach ($validParams as $paramName) {
            if (isset($_POST[$paramName])) {
                $data[$paramName] = $_POST[$paramName];
            } elseif($defaultParams[$paramName]) {
	            $data[$paramName] = $defaultParams[$paramName];;
            }
        }

        return $data;
    }
}