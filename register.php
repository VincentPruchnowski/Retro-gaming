<!DOCTYPE html>
<html lang="en">
<head>
	<title>Retro City</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="assets/favicon/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/styles/users.css">
</head>
<body>
	<div class="content">
		<div class="window2">
			<div class="image">
				<img src="assets/images/logosite.png">
			</div>

			<form class="form validate-form" method="post">
				<span>
					Créer un compte
				</span>

				<div class="input validate-input">
					<input class="inputt" type="text" name="name" placeholder="Nom">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-user"></i>
					</span>
				</div>

				<div class="input validate-input">
					<input class="inputt" type="text" name="surname" placeholder="Prénom">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-user"></i>
					</span>
				</div>

                <div class="input validate-input">
					<input class="inputt" type="text" name="email" placeholder="E-mail">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-envelope"></i>
					</span>
				</div>

                <div class="input validate-input">
					<input class="inputt" type="password" name="password" placeholder="Mot de passe">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-lock"></i>
					</span>
				</div>
				
				<?php
					if (isset($_POST["name"], $_POST["surname"], $_POST["email"], $_POST["password"])) {
						if(!empty($_POST["name"]) && !empty($_POST["surname"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
							$dsn = 'mysql:host=localhost;dbname=console_project';
							$dbh = new PDO($dsn, 'root', '');
							$reqprep = $dbh->prepare("INSERT INTO users (name, surname, email, password) VALUES (:name, :surname, :email, :password)");
							$verifmail = $dbh->prepare("SELECT * FROM users WHERE email=:email");
							$verifmail->execute(["email"=>$_POST["email"]]);
							$tabl = $verifmail->fetchAll();
							if (count($tabl)<1) {
								if (preg_match("/[0-9]/", $_POST["password"]) && preg_match("/[A-Z]/", $_POST["password"])) {
									if (strlen($_POST["password"]) >= 6) {
										if (preg_match("/@/", $_POST["email"])) {
											$reqprep->execute(["name"=>$_POST["name"], "surname"=>$_POST["surname"], "email"=>$_POST["email"], "password"=>$_POST["password"]]);
											echo "<div class='display2'>Le compte a été créé</div>";
                                            echo "<div class='input-btn'><button class='btn-connexion'><a href='login.php'> Se connecter </a><button></div>";
                                            ?>
												<script type="text/javascript">
													window.onload = function(){
														var x = document.getElementById("masquer");
														x.setAttribute("style", "display:none");
													}
												</script>
											<?php
										}
										else {
											echo "<div class='display'>L'adresse e-mail n'est pas valide</div>";
										}
									}
									else {
										echo "<div class='display'>Le mot de passe doit faire au moins 6 caractères</div>";
									}
								}
								else {
									echo "<div class='display'>Le mot de passe doit contenir au moins une majuscule et un chiffre</div>";
								}
							}
							else {
								echo "<div class='display'>Un compte associé à cette adresse e-mail existe déjà</div>";
							}
						} else {
							echo "<div class='display'> Tout les champs ne sont pas remplis </div>";
						}
					}
    			?>
		
				<div class="input-btn" id="masquer">
					<button class="btn-connexion">
						Créer un compte
					</button>
				</div>
				
				<div class="text-center pt136">
					<a class="txt" href="login.php">
						J'ai déjà un compte
						<i class="fa fa-long-arrow-right ml5"></i>
					</a>
				</div>
			</form>
		</div>
	</div>
	


</body>
</html>