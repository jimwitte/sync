<?php
//======================================================================
// VIEW objects
//======================================================================


class Renderer {
	public $template;
	public $viewName = 'start';

	function __construct() {
		$this->template = new Smarty();
		$this->template->setTemplateDir('./views/templates');
		$this->template->setCompileDir('./views/templates_c');
		$this->template->setCacheDir('./views/cache');
		$this->template->setConfigDir('./views/configs');
		
	}
	
	function displayWithTemplate($templateName) {
		$this->template->display($templateName);
	}
	
	function assignGameToTemplate($game) {
		$this->template->assign('game', $game);		
		$this->template->assign('view',$this->viewName);
	}
	
	function setView($view) {
		$this->viewName = $view;
	}

}






?>