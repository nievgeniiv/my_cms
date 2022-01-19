<?php

namespace src\main\modules\cms;

use Exception;
use src\main\models\Models;
use src\main\modules\common\Controller;
use src\main\services\Auth;
use src\main\models\Pages;

class Main extends Controller {

	public function __construct() {
		parent::__construct('cms');
		$this->cnt->tpl_path .= 'main/';
		$this->model = new Pages('page');
	}

	/**
	 * @throws Exception
	 */
	public function actList(): void {
		if (!Auth::checkAuth()) {
			$redirectPrm = [
				'url' => '/cms/login/'
			];
			redirect($redirectPrm);
		}

		$limit = $this->getLimit();
		$sort = $this->getSort();
		$pages = $this->model->getAll(Models::TYPE_CMS, $sort, $limit);

		if ($pages) {
			foreach ($pages as $key => $page) {
				$pages[$key]['dt_create'] = formatDate(FORMAT_DATE_AND_TIME, $page['dt_create']);
				$pages[$key]['dt_update'] = formatDate(FORMAT_DATE_AND_TIME, $page['dt_update']);
			}
		}

		$this->cnt->pages = $pages;
		$this->cnt->pageTitle = $this->cnt->STRINGS['page_title_cms_main'];
	}


}
