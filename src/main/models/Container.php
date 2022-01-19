<?php

namespace src\main\models;

class Container {
	private static Container $instance;
	private array $data;

	public static function getInstance() : Container {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->data = [];
	}

	public function __copy() {}

	public function __get(string $name) {
		return $this->data[$name];
	}

	public function __set(string $name, $value) : void {
		$this->data[$name] = $value;
	}

	public function __isset(string $name) : bool  {
		return isset($this->data[$name]);
	}

	public function safe(string $name, $default) {
		if ($this->__isset($name)) {
			return $this->__get($name);
		}
		return $default;
	}
}
