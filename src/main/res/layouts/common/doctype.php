<?php

use src\main\models\Container;

$cnt = Container::getInstance();
?>

<!DOCTYPE html>
<html lang="<?php strEsc($cnt->lang); ?>>">
