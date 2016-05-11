<?php
//======================================================================
// CONTROLLER file
//======================================================================

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/model.class.php';
require __DIR__ . '/view.class.php';

$game = new Game();
$render = new Renderer();
$templateName = 'index.tpl';

// choose view
if (!empty($game->turn->errors)) {
	$view = 'error';
} elseif ($game->year == 1) {
	$view = 'start';
} elseif ($game->year > 10) {
	$view = 'gameover';
	$templateName = 'gameover.tpl';
} elseif ($game->turn->impeach) {
	$view = 'impeach';
	$templateName = 'impeach.tpl';
} else {
	$view = 'midgame';
}


$render->setView($view);
$render->assignGameToTemplate($game);
$render->displayWithTemplate($templateName);


?>