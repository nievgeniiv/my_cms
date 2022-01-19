<?php

namespace src\main\services;
use src\main\models\User;

class Auth {
	public static function checkAuth(): bool {
		if (!isset($_SESSION['auth'])) {
			return false;
		}

		if (!$_SESSION['auth']) {
			return false;
		}

		$model = new User();
		$hash = $_SESSION['hash_user'];
		$id = $model->getOneByHash($hash);
		return $id > 0;
	}

	public static function checkLoginAndPassword(string $login, string $password) : bool {
		if (empty($login)) {
			return false;
		}

		if (empty($password)) {
			return false;
		}

		$model = new User();
		$dataUser = $model->getOneByLogin($login);
		if (empty($dataUser)) {
			return false;
		}

		if (empty($dataUser['hash'])) {
			return false;
		}

		return password_verify($password, $dataUser['hash']);
	}
}
