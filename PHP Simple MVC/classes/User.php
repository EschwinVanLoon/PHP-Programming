<?php

class User {
	private $users = [
					  'root' => 'abcde',
					  'admin' => '12345'
					 ];
	
	public function getName() {
		if (isset($_SESSION['username'])) {
			return $_SESSION['username'];
		}
	}
				
	public function isAuthenticated():bool {
		return isset($_SESSION['username']);
	}
	
	public function login(string $name, string $pass):bool {
		if (!isset($this->users[$name])) {return false;}
		if ($this->users[$name] !== $pass) {return false;}
		
		$_SESSION['username'] = $name;
		return true;
	}	
	public function logout() {
		unset($_SESSION['username']);
	}
}