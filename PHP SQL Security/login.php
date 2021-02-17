<?php

require_once 'php/functions.php';

$gebruiker = $_POST['gebruiker'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST['csrfToken'] === $_SESSION['csrfToken']) {
		$db = makeConnection();
		
		$wachtwoord = $_POST['wachtwoord'] ?? '';
		if (gebruikerInloggen($db, $gebruiker, $wachtwoord)) {
			header('Location: home.php');
			die();
		} else {
			$fouten = ['Ongeldige gebruikersnaam en/of wachtwoord.'];
		}
	} else {
		$fouten = ['Mogelijke CSRF hacking.'];
	}
}

?><!DOCTYPE html>
<html>
	<head>
		<title>t04m04 - Inloggen</title>
		<link rel="stylesheet" href="style/style.css">
	</head>
	<body>
		<div class="container">
			<?php include 'php/header.php'; ?>
			<?php include 'php/menu.php'; ?>
			<main>
				<?php
					if (isset($fouten) && is_array($fouten)) {
						echo '<ul class="fouten">';
						foreach($fouten as $fout) {echo '<li>'.$fout.'</li>';}
						echo '</ul>';
					}
				
				?><form action="login.php" method="POST">
					<fieldset>
						<legend>Inloggen</legend>
						<input type="hidden" id="crsf" name="csrfToken" value="<?php echo generateToken(); ?>">
						<div><label>
							<span>Gebruikersnaam (hash)</span>
							<input name="gebruiker" value="<?= $gebruiker ?>" required>
						</label></div>
						<div><label>
							<span>Wachtwoord</span>
							<input type="password" name="wachtwoord" required>
						</label></div>
						<div>
							<input type="submit" name="inloggen" value="Inloggen">
						</div>
					</fieldset>
				</form>
			</main>
			<?php include 'php/footer.php'; ?>
			<!-- <?php echo '(CSRF Token = '.$_SESSION['csrfToken'].')'; ?> -->
		</div>
	</body>
</html>