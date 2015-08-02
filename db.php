<?php
require_once dirname(__FILE__) . "/Bootstrap.php";
Bootstrap::load();
$router = new Router();
$router->request();
die;