<?php
require_once 'php/functions.php';

if (!isIngelogd()) {
	$fouten = ['Gebruiker niet ingelogd. Pagina mag niet getoond worden.'];
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
				
				?><h2>Welkom terug, <i><?= gebruikerNaam() ?></i>!</h2>
			</main>
			<?php include 'php/footer.php'; ?>
		</div>
	</body>
</html>