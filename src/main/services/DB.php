<?php

namespace src\main\services;

class DB {
	private $link;
	private $mysql;
	private static DB $instance;
	public const ERROR_COUNT_PARAMETERS = 1;
	public const ERROR_DATA_EMPTY = 2;
	public const SUCCESS = 0;

	public static function getInstance(): DB {
		if (self::$instance === null) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->link = null;
	}

	public function connect() {
		if ($this->link === null) {
			$this->link = @ mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
			if (mysqli_connect_error()) {
				$str = __METHOD__ . ' Ошибка подключения к базе данных. HOST=' . DB_HOST .
					', USER=' . DB_USER . ', PASS=' . DB_PASSWORD . ', DB=' . DB_NAME;
				writeLog($str, 'auth');
				exit('Ошибка соединения с MySQL! Обратитесь к администратору!');
			}
		}
		return $this->link;
	}

	private function __clone() {
	}

	/**
	 * @param string $sql
	 * @param mixed ...$param
	 * @return array
	 */
	public function getRows(string $sql, ...$param): array {
		$nof = substr_count($sql, '?');
		if (count($param) === $nof) {
			$sql = $this->prepare($sql, $param);
			$res = mysqli_query($this->mysql, $sql);
			if ($res === false) {
				$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_DATA_EMPTY);
				return [];
			}
			while ($k = mysqli_fetch_assoc($res)) {
				$row[] = $k;
			}
			return $row ?? [];
		}

		$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_COUNT_PARAMETERS);
		return [];
	}

	/**
	 * @param string $sql
	 * @param mixed ...$param
	 * @return array
	 */
	public function getRow(string $sql, ...$param): array {
		$nof = substr_count($sql, '?');
		if (count($param) === $nof) {
			$sql = $this->prepare($sql, $param);
			$res = mysqli_query($this->mysql, $sql);
			if ($res === false) {
				$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_DATA_EMPTY);
				return [];
			}
			$row = mysqli_fetch_assoc($res);
			return $row ?? [];
		}

		$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_COUNT_PARAMETERS);
		return [];
	}

	/**
	 * @param string $sql
	 * @param mixed ...$param
	 * @return array
	 */
	public function getCell(string $sql, ...$param): array {
		$nof = substr_count($sql, '?');
		if (count($param) === $nof) {
			$sql = $this->prepare($sql, $param);
			$res = mysqli_query($this->mysql, $sql);
			if ($res === false) {
				$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_DATA_EMPTY);
				return [];
			}
			$value = mysqli_fetch_assoc($res);
			return $value ?? [];
		}

		$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_COUNT_PARAMETERS);
		return [];
	}

	/**
	 * @param string $sql
	 * @param mixed ...$param
	 */
	public function setData(string $sql, ...$param): bool {
		$nof = substr_count($sql, '?');
		if (count($param) === $nof) {
			$sql = $this->prepare($sql, $param);
			$res = mysqli_query($this->mysql, $sql);
			if ($res === false) {
				$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_DATA_EMPTY);
				return false;
			}
			$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::SUCCESS);
			return true;
		}

		$this->writeLog(__METHOD__ . ' ' . $sql, $param, self::ERROR_COUNT_PARAMETERS);
		return false;
	}

	/**
	 * @param string $sql
	 * @param array $param
	 * @return string
	 */
	private function prepare(string $sql, array $param): string {
		$this->mysql = $this->connect();
		$str = explode('?', $sql);
		$i = 1;
		$sql = $str[0];
		foreach ($param as $value) {
			if (is_string($value) or gettype($value) === 'date') {
				$value = '"' . $this->mysql->real_escape_string($value) . '"';
			} else {
				$value = $this->mysql->real_escape_string($value);
			}
			$sql .= $value . $str[$i];
			$i++;
		}
		return $sql;
	}

	/**
	 * @param string $sql
	 * @param array $param
	 * @param int $error
	 */
	private function writeLog(string $sql, array $param, int $error = 0): void {
		switch ($error) {
			case self::ERROR_COUNT_PARAMETERS:
				$str = 'Количество данных не соответсвует количеству входных параметров.';
				break;
			case self::ERROR_DATA_EMPTY:
				$str = 'Запращиваемые данные не существуют.';
				break;
			default:
				$str = 'Запрос в БД произошел успешно.';
		}

		$str .= ' Запрос: ' . $sql . '  Параметры:';
		foreach ($param as $item) {
			$str .= $item . ', ';
		}
		writeLog($str, 'database');
	}
}
