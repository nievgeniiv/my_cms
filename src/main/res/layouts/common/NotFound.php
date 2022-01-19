<?php
require_once 'doctype.php';
if ($cnt->area === 'cms') {
	include_once DIR_LAYOUTS . 'cms/header.php';
} else {
	include_once DIR_LAYOUTS . 'public/header.php';
}

echo '404 Not Found';
