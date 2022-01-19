<?php

namespace src\main\modules\common;

use src\main\models\Container;
use src\main\models\Route as RouteMDL;
use src\main\modules\common\Controller;
use src\main\modules\common\NotFound as NotFoundMod;

class Router {

	private const NAMESPACE = 'src\main\modules';
	private static array $get;
	private static array $post;
	private string $uri;
	private array $url;
	private RouteMDL $model;
	private Container $ctr;

	public function __construct() {
		$this->uri = $_SERVER['REQUEST_URI'];
		$uri = trim($this->uri, ' /');
		$this->url = explode(DIRECTORY_SEPARATOR, $uri);
		$this->model = new RouteMDL();
		$this->ctr = Container::getInstance();
		$this->ctr->area = $this->url[0];
	}

	public function go() : void {
		if (!isPost()) {
			$class = self::$get[$this->uri][0] ?? '';
			$method = self::$get[$this->uri][1] ?? '';
		} else {
			$class = self::$post[$this->uri][0] ?? '';
			$method = self::$post[$this->uri][1] ?? '';
		}

		if (($this->ctr->area === 'cms') && empty($class)) {
			$mod = new NotFoundMod();
			$mod->actView();
			return;
		}

		if (empty($class)) {
			$class = 'BuilderPage';
			$method = 'View';
		}

		$this->ctr->method = strtolower($method);
		$classArr = explode('\\', $class);
		$index = count($classArr) - 1;
		$this->ctr->layout_name = strtolower($classArr[$index]);
		$mod = new $class();
		$nameMethod = 'act' . $method;
		$mod->$nameMethod();
		$mod->includeTpl();
	}

	public static function GET(string $uri, string $class, string $method, string $area): void {
		self::$get[$uri] = [
			0 => self::NAMESPACE . '\\' . $area . '\\' . $class,
			1 => $method,
		];
	}

	public static function POST(string $uri, string $class, string $method, string $area): void {
		self::$post[$uri] = [
			0 => self::NAMESPACE . '\\' . $area . '\\' . $class,
			1 => $method,
		];
	}
}
