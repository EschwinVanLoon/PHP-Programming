<?php

class View {
	private $page = 'default';
	private $user = null;
	
	public function __construct(User $user) {
		$this->user = $user;
	}	
	public function getHTML(){
		$title = 'Login Systeem';
		$html  = $this->getPageHTML();
		$year  = date('Y');
		
		include 'template.php';
	}
	public function getPage():string {return $this->page;} 
	
	public function setPage($page) {$this->page = $page;}
	
	public function getPageHTML():string {
		switch ($this->page) {
			case 'in':   return $this->logoutForm();
			case 'out':  return $this->loggedOut();
			case 'fail': return $this->loginFail().$this->loginForm();
			default:	 return $this->loginForm();
		}
	}
	public function loggedOut():string {
		$link = '<a href="'.$_SERVER['PHP_SELF'].'">hier</a>';
		return "<p>Je bent uitgelogd! Klik $link.</p>";
	}
	public function logoutForm():string {
		$naam  = $this->user->getName();
		$html  = "<p>Welkom terug, $naam</p>";
		$html .= "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">";
		$html .= '<input type="submit" name="logout" value="Uitloggen">';
		$html .=  "</form>";
		return $html;
	}
	public function loginFail():string {
		return '<p>Ongeldige gebruikersnaam/wachtwoord combinatie.</p>';
	}
	public function loginForm():string {
		$html  = '<h2>Inloggen</h2>';
		$html .= "<form action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">";
		$html .= '<input type="text" name="name" placeholder="...vul hier je naam in"><br>';
		$html .= '<input type="password" name="pass" placeholder="...vul hier je wachtwoord in"><br>';
		$html .= '<input type="submit" value="Inloggen">';
		$html .= "</form>";
		return $html;
	}
}