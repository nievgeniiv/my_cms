<?php

namespace src\main\modules\common;

class NotFound extends Controller {
	public function actView() : void {
		$this->ctn->pageTitle = '404 NotFound';
		include_once DIR_LAYOUTS . 'common/NotFound.php';
	}
}
