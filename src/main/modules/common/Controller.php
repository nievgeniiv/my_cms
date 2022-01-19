<?php

namespace src\main\modules\common;

use src\main\models\Container;
use src\main\services\ServiceForm;

class Controller {
	protected Container $cnt;

	public function __construct(string $area = 'pub') {
		$this->cnt = Container::getInstance();
		if ($area === 'cms') {
			$this->cnt->tpl_path = DIR_LAYOUTS . 'cms/';
		}

		$this->cnt->lang = $_GET['ln'] ?? 'ru';
		$this->cnt->meta_description = '';
		$this->cnt->meta_keywords = '';
		$this->cnt->author = 'Ni Evgenii';
	}

	public function getFilledData(string $code): void {
		$form = new ServiceForm($code);
		$form->readData();
		if ($form->isError()) {
			$this->cnt->data = $form->getData();
			$this->cnt->errors = $form->getErrors();
		}
	}

	public function includeTpl(): void {
		include_once $this->cnt->tpl_path . 'main.php';
	}

	protected function getLimit(): array {
		$idStart = isset($_GET['id_start']) ? (int) $_GET['id_start'] : 0;
		$idCount = isset($_GET['id_count']) ? (int) $_GET['id_count'] : 20;
		return [$idStart, $idCount];
	}

	protected function getSort(): array {
		$field = $_GET['sort_field'] ?? 'id';
		$sort = $_GET['sort'] ?? 'DESC';

		return [$field, $sort];
	}
}
