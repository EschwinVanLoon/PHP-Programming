<?php
require_once 'php/functions.php';

$gebruiker = $_POST['gebruiker'] ?? '';
$email     = $_POST['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if ($_POST['csrfToken'] === $_SESSION['csrfToken']) {
		$db = makeConnection();
	
		$wachtwoord = $_POST['wachtwoord'] ?? '';
		$bevestig   = $_POST['bevestig'] ?? '';
		$fouten = gebruikerRegistreren($db, $gebruiker, $wachtwoord, $bevestig, $email);
	} else {
		$fouten = ['Mogelijke CSRF hacking.'];
	}
}

?><!DOCTYPE html>
<html>
	<head>
		<title>t04m04 - Registreren</title>
		<link rel="stylesheet" href="style/style.css">
		<script>
			window.addEventListener('keyup', function(event) {
				const form       = document.querySelector('form');
				const wachtwoord = form.querySelector('[name="wachtwoord"]');
				const bevestig   = form.querySelector('[name="bevestig"]');
				
				if (wachtwoord && bevestig) {
					if (wachtwoord.value !== bevestig.value) {
						bevestig.setCustomValidity("Wachtwoord werd niet bevestigd.");
					} else {
						bevestig.setCustomValidity("");
					}
				}
			});
		</script>
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
				
				?><form action="register.php" method="POST">
					<fieldset>
						<legend>Nieuwe Gebruiker Registreren</legend>
						<input type="hidden" id="crsf" name="csrfToken" value="<?php echo generateToken(); ?>">
						<div><label>
							<span>Gebruikersnaam</span>
							<input name="gebruiker" value="<?= $gebruiker ?>" required>
						</label></div>
						<div><label>
							<span>Wachtwoord</span>
							<input type="password" name="wachtwoord" required>
						</label></div>
						<div><label>
							<span>Bevestig Wachtwoord</span>
							<input type="password" name="bevestig" required>
						</label></div>
						<div><label>
							<span>E-mail</span>
							<input type="email" name="email" value="<?= $email ?>" required>
						</label></div>
						<div>
							<input type="submit" name="registeren" value="Registeren">
						</div>
					</fieldset>
				</form>
			</main>
			<?php include 'php/footer.php'; ?>
			<!-- <?php echo '(CSRF Token = '.$_SESSION['csrfToken'].')'; ?> -->
		</div>
	</body>
</html>