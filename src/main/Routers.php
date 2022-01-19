<?php
use src\main\modules\common\Router;
Router::GET('/cms/login/', 'Login', 'View', 'cms');
Router::POST('/cms/login/', 'Login', 'Login', 'cms');
Router::GET('/cms/main/', 'Main', 'list', 'cms');
