<?php
require_once 'settings.php';

session_start();

/* 
 *	In deze functie wordt een connectie gelegd met de database.
 *	De actieve databaseverbinding wordt vervolgens geretourneerd.
 */
function makeConnection() {
	static $pdo = false;
	if ($pdo) {return $pdo;}
	
	// Verbinding maken met de database
	$host     = DB_HOST;
	$schema   = DB_NAME;
	$user     = DB_USER;
	$password = DB_PASS;
	$charset  = 'utf8mb4';

	// Hulpvariabelen voor het verbinden
	$dsn = "mysql:host=$host;dbname=$schema;charset=$charset";
	$options = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];

	// Maak een nieuwe database verbinding aan
	$pdo = new PDO($dsn, $user, $password, $options);
	$pdo->exec("SET sql_mode='traditional';");
	
	return $pdo;
}

function generateToken() {
	$token = '';
	$charSet = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
	
	if (!isset($_SESSION['csrfToken'])) {
		for ($i = 0; $i < 20; $i++) {
			$char = $charSet[rand(0, (strlen($charSet) - 1))];
			$token .= $char;
		}
		$_SESSION['csrfToken'] = $token;
	} else {
		$token = $_SESSION['csrfToken'];
	}
	
	return $token;
}

function isIngelogd() {
	return isset($_SESSION['gebruiker_id']) && isset($_SESSION['gebruiker_naam']);
}

function gebruikerId() {
	return $_SESSION['gebruiker_id'] ?? false;
}

function gebruikerNaam() {
	return $_SESSION['gebruiker_naam'] ?? 'Gast';
}

function gebruikerInloggen($db, $naam, $wachtwoord) {
	// Gebruikers account opzoeken in de database
	$query = 'SELECT * FROM gebruikers WHERE naam = :naam;';
	$ps = $db->prepare($query);
	
	$ps->execute([
		':naam' => $naam
	]);
	
	// Als we een account terug konden vinden, klopt dan het wachtwoord?
	if ($account = $ps->fetch()) {
		if (password_verify($wachtwoord, $account['wachtwoord'])) {
			// Alles juist? Opslaan in de sessie en missie geslaagd.
			$_SESSION['gebruiker_id'] = $account['id'];
			$_SESSION['gebruiker_naam'] = $account['naam'];
			return true;
		}
	}
	
	// Komen we hier? Dan was het inloggen niet gelukt.
	unset($_SESSION['gebruiker_id']);
	unset($_SESSION['gebruiker_naam']);
	return false;
}

function gebruikerUitloggen() {
	unset($_SESSION['gebruiker_id']);
	unset($_SESSION['gebruiker_naam']);
	return false;	
}

function gebruikerRegistreren($db, $naam, $wachtwoord, $bevestig, $email) {
	// Array voor foutmeldingen
	$fouten = [];
	// Gebruikersnaam mag geen HTML-code bevatten
	if ($naam != strip_tags($naam)) {
		$fouten[] = 'Uw gebruikersnaam mag geen HTML-code bevatten';
	}
	// De gebruikersnaam moet minimaal 4 tekens lang zijn
	if (strlen($_POST['gebruiker']) < 4) {
		$fouten[] = "Uw gebruikersnaam dient minimaal 4 tekens lang te zijn";
	}
	// Het wachtwoord moet minstens 8 tekens lang zijn.
	if (strlen($_POST['wachtwoord']) < 8) {
		$fouten[] = "Uw wachtwoord dient minimaal 8 tekens lang te zijn";
	}
	// Het email-adres moet een correct geformuleerd zijn
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)
		&& $email != strip_tags($email)) {
			$fouten[] = "Uw dient een geldig e-mail adres op te geven";
	}
	
	// Formulier niet correct ingevuld? Dan ook niet opslaan in de database.
	if (count($fouten) > 0) {return $fouten;}
	
	try {
		// Maak in de database een nieuw record aan voor de gebruiker.
		$hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
		
		$query = 'INSERT INTO gebruikers (naam, wachtwoord, email)'.
			' VALUES (:naam, :wachtwoord, :email);';
			
		$ps = $db->prepare($query);
		$ps->execute([
			':naam' => $naam,
			':wachtwoord' => $hash,
			':email' => $email
		]);
		
		// Alles geslaagd?
		$_SESSION['gebruiker_id']   = $db->lastInsertId();
		$_SESSION['gebruiker_naam'] = $naam;
		$fouten = ['Registratie geslaagd'];
		return $fouten;
	} catch(Exception $e) {
		// Niet goed? Typische fouten afvangen...
		if (preg_match("/Duplicate entry '.+' for key 'naam'/i",$e->getMessage())) {
			$fouten[] = 'Gebruiker <q>'.$naam.'</q> bestaat al.';
		} else if (preg_match("/Duplicate entry '.+' for key 'email'/i",$e->getMessage())) {
			$fouten[] = 'Email <q>'.$email.'</q> al in gebruik.';
		} else {
			$fouten[] = 'Foutmelding: '.$e->getMessage().'<br>'.$query;
		}
	}
	
	return $fouten;
}
