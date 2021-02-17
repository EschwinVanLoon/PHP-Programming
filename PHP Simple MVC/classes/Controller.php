<?php

class Controller {
	private $model = null;
	private $view = null;
	
	public function __construct(){
		$this->model = new User();
		$this->view  = new View($this->model);
	}	
	public function processRequest(){
		if (!$this->model->isAuthenticated()) {
			if (!empty($_POST['name']) && empty(!$_POST['pass'])) {
				if ($this->model->login($_POST['name'], $_POST['pass'])) {
					$this->view->setPage('in');
				} else {
					$this->view->setPage('fail');
				}
			}
		} else {
			if (isset($_POST['logout'])) {			
				$this->model->logout();
				$this->view->setPage('out');
			} else {
				$this->view->setPage('in');				
			}
		}
	}	
	public function getModel(){return $this->model;}
	public function getView(){return $this->view;}
}