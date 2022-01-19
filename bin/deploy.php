<?php

require_once __DIR__ . '/../src/main_config.php';

class Deploy {
	private const PATH_FILE_VERSION = __DIR__ . '/../version.json';
	private const PATH_ROOT = __DIR__ . '/../';
	private array $obj;
	private array $tree;

	/**
	 * @throws JsonException
	 */
	public function __construct() {
		$jsonObj = file_get_contents(self::PATH_FILE_VERSION);
		$this->obj = json_decode($jsonObj, true, 512, JSON_THROW_ON_ERROR);
	}

	/**
	 * @throws JsonException
	 */
	public function run(): void {
		$this->makeTree();
		$needUpdate = $this->needUpdate();
		if ($needUpdate) {
			[$msg, $err] = $this->saveVersion();
			echo $msg . PHP_EOL;
			if ($err) {
				exit();
			}

			[$msg, $err] = $this->deployOnServer();
			echo $msg . PHP_EOL;
			if ($err) {

				exit();
			}
		}
	}

	private function makeTree(string $startDir = '') : array {
		if (empty($startDir)) {
			$startDir = self::PATH_ROOT;
		}

		$dirs = array_diff(scandir($startDir), ['..', '.', 'var', '.idea', 'version.json']);
		foreach ($dirs as $dir) {
			if (is_file($startDir . $dir)) {
				$path = str_replace(self::PATH_ROOT, '', $startDir . $dir);
				$this->tree[$path] = md5_file($startDir . $dir);
				continue;
			}

			[$isError] = $this->makeTree($startDir . $dir . '/');
			if ($isError) {
				exit('Не удалось просканировать папку ' . $startDir . $dir . '/');
			}
		}

		return [false];
	}

	private function needUpdate() : bool {
		foreach ($this->tree as $file => $hash) {
			if (!isset($this->obj['hashes'][$file])) {
				return true;
			}

			if ($this->obj['hashes'][$file] !== $hash) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @throws JsonException
	 */
	private function saveVersion(): array {
		$patConstants = __DIR__ . '/../src/main/constants.php';
		$versions = file_get_contents($patConstants);
		$versionsArr = explode(PHP_EOL, $versions);
		foreach ($versionsArr as $k => $v) {
			if (strpos($v, 'VERSION_CSS') > 0) {
				$versionsCss = str_replace("const VERSION_CSS = '", '', $v);
				$versionsCss = trim($versionsCss, "'");
				$versionsCss = (int) $versionsCss + 1;
				$versionsArr[$k] = "const VERSION_CSS = '" . $versionsCss . "';";
			}

			if (strpos($v, 'VERSION_JS') > 0) {
				$versionsJs = str_replace("const VERSION_JS = '", '', $v);
				$versionsJs = trim($versionsJs, "'");
				$versionsJs = (int) $versionsJs + 1;
				$versionsArr[$k] = "const VERSION_JS = '" . $versionsJs . "';";
			}
		}

		$versions = implode(PHP_EOL, $versionsArr);
		$res = file_put_contents($patConstants, $versions);
		if (!$res) {
			return ["Не удалось обновить версии js и css", true];
		}

		$obj = [
			'version' => (float)$this->obj['version'] + 0.01,
			'hashes' => $this->tree
		];
		$jsonObj = json_encode($obj, JSON_THROW_ON_ERROR);
		$res = file_put_contents(self::PATH_FILE_VERSION, $jsonObj);
		if (!$res) {
			return ["Не удалось обновить версию проекта", true];
		}

		return ["Версия проекта была обновлена", false];
	}

	private function deployOnServer() : array {
		$tree = array_diff(scandir(self::PATH_ROOT), ['..', '.', '.idea', 'version.json']);
		$cmdStart = 'scp -P ' . PORT_TEST . ' -r ';
		$cmdAfter = USER . '@' . IP_ADDRESS_TEST . ':' . DEPLOY_DIR_TEST;
		foreach ($tree as $dir) {
			$patPrj = str_replace('\\', '/', self::PATH_ROOT);

			$cmd = $cmdStart . $patPrj . $dir . ' ' . $cmdAfter;
			$res = exec($cmd, $out);
			if ($res === false) {
				return ['Не удалось отправить данные на сервер', true];
			}
		}
		return ['Данные успешно отправленны на сервер', false];
	}
}

$a = new Deploy();
$a->run();
