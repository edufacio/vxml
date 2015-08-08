<?php
require_once dirname(__FILE__) . "/Bootstrap.php";
Bootstrap::load();
$storage = new UserStorage();
$users = $storage->get("where phone = ?", array(30));
var_dump($users);


$user = User::create();
$user->setPhone(10);
$storage->save($user);

/* @var $user User */
$users = $storage->get("where phone = ?", array(10));
var_dump($users);
$user = $users[0];
var_dump($user->hasPhone(),$user->hasEndFavouriteSchedule(), $user->hasRegisterTime(), $user->hasPassword(), $user->hasProvinceId(), $user->hasRegisterStatus(), $user->hasStartFavouriteSchedule());
$user->setPassword(45)->setEndFavouriteSchedule(1)->setProvinceId("ES_M")->setStartFavouriteSchedule(2)->setRegisterStatus(1)->setRegisterTime(time());
$storage->save($user);
$users = $storage->get("where phone = ?", array(1));
var_dump($users);
$user = $users[0];

$user->setProvinceId("PUTITO");

$storage->update($user);
$users = $storage->get("where phone = ?", array(1));
var_dump($users);
$user = $users[0];
$storage->delete($user);
$users = $storage->get("where phone = ?", array(1));
var_dump($users);

die;

