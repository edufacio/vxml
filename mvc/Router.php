<?php

Class Router
{
    public function request()
    {

        $navigationMap = new NavigationMap();
        $controllerName = $navigationMap->getControllerName();
        $action = $navigationMap->getAction();
        $data = $navigationMap->getData($controllerName, $action);
        $controllerClass = $controllerName . "Controller";
        $controller = new $controllerClass();
        $controller->$action($data);



    }
}