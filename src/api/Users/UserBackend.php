<?php
/**
 * Created by JetBrains PhpStorm.
 * User: econtreras
 * Date: 8/8/15
 * Time: 7:21 PM
 * To change this template use File | Settings | File Templates.
 */

class UserBackend
{
	private static $instance;

	/**
	 * @return UserBackend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = Injector::get('UserBackend');
		}
		return self::$instance;
	}

	public function passwordIsCorrect($phone, $password)
	{
		/* @var $user User */
		$user = UserStorage::getInstance()->find(array(UserStorage::PHONE => $phone));
		return $user->getPassword() === $password;
	}

	public function exists($phone)
	{
		/* @var $user User */
		$user = UserStorage::getInstance()->find(array(UserStorage::PHONE => $phone));
		return $user !== null;
	}

	public function createUser($phone, $password)
	{
		/* @var $user User */
		$user = User::create();
		$user->setPhone($phone)->setPassword($password)->setRegisterTime(time());
		return UserStorage::getInstance()->save($user);
	}
}