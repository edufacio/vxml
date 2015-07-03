<?php

Class Router
{
    public function request()
    {
        $navigationMap = NavigationMap::create();
        $controllerName = $navigationMap->getControllerName();
        $action = $navigationMap->getAction();
        $data = $navigationMap->getData($controllerName, $action);
        $controllerClass = $controllerName . "CONTROLLER";
        $controller = Injector::get($controllerClass, $navigationMap);
        $controller->$action($data);
    }
}