<?php

namespace src\main\models;

class User extends Models {

	private const TABLE = 'user';

	public function getOneByHash(string $hash): int {
		$sql = 'SELECT id FROM ' . self::TABLE . ' WHERE hash=?';
		$data = $this->db->getRow($sql, $hash);
		return !empty($data) ? $data['id'] : 0;
	}

	public function getOneByLogin(string $login): array {
		$sql = 'SELECT * FROM ' . self::TABLE . ' WHERE login=?';
		$data = $this->db->getRow($sql, $login);
		return !empty($data) ? $data : [];
	}
}
