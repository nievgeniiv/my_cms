<?php

use src\main\models\Container;

$cnt = Container::getInstance();
$cnt->STRINGS = [
	'main_page' => 'Главная страница',
	'cms_main_page' => 'CMS',
	'contacts' => 'Контакты',
	'page_title_login' => 'Вход',
	'error_login_and_password' => 'Не правильно введен логин или пароль',
	'page_title_cms_main' => 'Страницы',
];
