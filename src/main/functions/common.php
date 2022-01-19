<?php

function isPost(): bool {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function strEsc(string $var) : void {
	echo htmlspecialchars($var);
}

function strEch(string $var) : void {
	echo $var;
}

/**
 * @param array $parameters
 */
function redirect(array $parameters) {
	$url = $parameters['url'];
	if (!empty($parameters['get'])) {
		$url .= '?';
		foreach ($parameters['get'] as $k => $v) {
			$url .= '&' . $k . '=' . $v;
		}
	}

	if (PHP_SAPI === 'cgi') {
		header('Status: 301 Moved Permanently');
	} else {
		header('HTTP/1.0 301 Moved Permanently');
	}
	header('Location: ' . $url);
}

function writeLog(string $str, string $nameFile): void {
	$data = date('d.M.Y H:i:s') . ' ' . $str . PHP_EOL;
	if (OS === 'windows') {
		exit($data);
	}

	if (!file_exists(DIR_LOG . $nameFile . '.log')) {
		shell_exec('touch ' . DIR_LOG . $nameFile . '.log');
	}
	$fd = fopen(DIR_LOG . $nameFile . ".log", 'ab') or die(ERROR_MESSAGE);
	fwrite($fd, $data);
	fclose($fd);
	if (ENVIRONMENT === 'master') {
		require_once __DIR__ . '/../Services/ServiceSendMail.php';
		ServiceSendMail::SendMail('Warning!', 'Произошла ошибка! Сайт не работает!');
	}
}

/**
 * @throws Exception
 */
function formatDate(string $format, string $date): string {
	return (new DateTime($date))->format($format);
}
