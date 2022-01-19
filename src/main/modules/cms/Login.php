<?php

namespace src\main\modules\cms;

use src\main\modules\common\Controller;
use src\main\services\Auth;
use src\main\services\ServiceForm;

class Login extends Controller {

	private string $codeForm = 'loginForm';

	public function __construct() {
		parent::__construct('cms');
	}

	public function actView(): void {
		if (Auth::checkAuth()) {
			$url = $_SERVER['REQUEST_URI'];
			redirect(['url' => $url]);
		}

		$this->getFilledData($this->codeForm);
		$this->cnt->pageTitle = $this->cnt->STRINGS['page_title_login'];
	}

	public function actLogin(): void {
		if (Auth::checkAuth()) {
			redirect(['url' => '/cms/main/']);
		}

		$form = new ServiceForm($this->codeForm);
		$form->readData();
		$form->checkData('login', 'string', true);
		$form->checkData('password', 'string', true);
		$form->checkData('rememberMe', 'string');

		if ($form->isError()) {
			$form->save();
			redirect(['url' => '/cms/login/']);
		}

		$data = $form->getData();

		if (Auth::checkLoginAndPassword($data['login'], $data['password'])) {
			$form->clear();
			redirect(['url' => $_SESSION['url'] ?? '/cms/main/']);
		}

		$form->setError('login_and_password', $this->cnt->STRINGS['error_login_and_password']);
	}
}
