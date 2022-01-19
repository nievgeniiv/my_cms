<?php

namespace src\main\models;

use src\main\services\DB;

class Models {

	public const TYPE_CMS = 0;
	public const TYPE_HIDDEN = 1;
	public const TYPE_PUBLIC = 2;

	protected DB $db;
	protected string $table = '';

	public function __construct(string $table) {
		$this->table = $table;
		$this->db = new DB();
	}

	public function getAll(int $type = self::TYPE_PUBLIC, array $sort = ['id', 'DESC'], array $limit = [0, 20], string $where = ''): array {
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE `type`=? ' . $where .
			' LIMIT ? ? ORDER BY ? ?';
		$data = $this->db->getRows($sql, $type, $limit[0], $limit[1], $sort[0], $sort[1]);
		return $data ?? [];
	}

	public function geOneById(int $id, int $type = self::TYPE_PUBLIC, string $where = ''): array {
		$sql = 'SELECT * FROM ' . $this->table . ' WHERE id=? AMD `type`=? ' . $where;
		$data = $this->db->getRow($sql, $id, $type);
		return $data ?? [];
	}

	public function getEmpty(): array {
		return [];
	}
}
