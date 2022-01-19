<?php
include_once __DIR__ . '/../common/doctype.php';
include_once __DIR__ . '/../common/head.php';
include_once __DIR__ . '/css.php';
include_once __DIR__ . '/header.php'; ?>
<div class="container-fluid">
	<div class="row">
		<?php include_once __DIR__ . '/sidebar.php' ?>
		<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
			<?php include_once  $cnt->tpl_path . $cnt->layout_name . '/'. $cnt->method . '.php'; ?>
		</main>
	</div>
</div>
<?php include_once __DIR__ . '/../common/footer.php';
