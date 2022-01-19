<?php

namespace src\main\services;

class ServiceForm {
	private array $data = [];
	private array $errors;
	private string $key;

	/**
	 * ServiceForm constructor.
	 * @param $formKey
	 */
	public function __construct($formKey) {
		$this->key = $formKey;
	}

	public function readData(): void {
		if (isset($_SESSION['form'][$this->key])) {
			$raw = $_SESSION['form'][$this->key];
			$this->data = $raw['data'];
			$this->errors = $raw['errors'];
		}
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->data = $_POST;
			$this->errors = [];
		}
	}

	/**
	 * @param string $name
	 * @param string $type
	 * @param bool $required
	 */
	public function checkData(string $name, string $type, bool $required = false) {
		$data = trim($this->data[$name]);
		if ($required === true and empty($data)) {
			$this->errors['name'] = 'Это поле обязательно должно быть заполнено!';
			return;
		}
		if ($required === false and empty($data)) {
			return;
		}
		switch ($type) {
			case 'integer':
				$ok = Validate::checkInteger($data);
				if ($ok === false) {
					$this->errors[$name] = 'Неверный тип данных. Данные должны быть ввиде целого числа';
				}
				break;
			case 'string':
				$ok = Validate::checkString($data);
				if ($ok === false) {
					$this->errors[$name] = 'Неверный тип данных. Данные должны быть ввиде строки';
				}
				break;
		}
		$this->data[$name] = Validate::deleteHTMLSymbol($data);
	}

	/**
	 * @return bool
	 */
	public function isError(): bool {
		return !empty($this->errors);
	}

	/**
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	public function setError(string $name, string $value): void {
		$this->errors[$name] = $value;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getValue(string $name) {
		return $this->data[$name];
	}

	public function save(): void {
		$_SESSION['form'][$this->key] = [
			'data' => $this->data,
			'errors' => $this->errors];
	}

	public function clear() {
		unset($_SESSION['form'][$this->key]);
	}
}
