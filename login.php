<?php
session_start();
?>
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
		<div class="window">
			<div class="image">
				<img src="assets/images/logosite.png">
			</div>

			<form class="form validate-form" method="post">
				<span>
					Se connecter
				</span>

				<div class="input validate-input" data-validate = "Une adresse e-mail valide est recquise">
					<input class="inputt" type="text" name="email" placeholder="E-mail">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-envelope"></i>
					</span>
				</div>

				<div class="input validate-input" data-validate = "Un mot de passe valide est requis">
					<input class="inputt" type="password" name="password" placeholder="Mot de passe">
					<span class="focus-input"></span>
					<span class="symbol-input">
						<i class="fa fa-lock"></i>
					</span>
				</div>
				
				<div class="input-btn">
					<button class="btn-connexion">
						Connexion
					</button>
				</div>

				<div class="text-center pt12">
					<a class="txt" href="#">

					</a>
				</div>
                <?php
                if (!empty($_POST["email"]) && !empty($_POST["password"])) {
                    $dsn = 'mysql:host=localhost;dbname=console_project';
                    $dbh = new PDO($dsn, 'root', '');
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $reqprep = $dbh->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
                    $reqprep->execute(["email"=>$email, "password"=>$password]);
                    $tab = $reqprep->fetchAll();
                    if (count($tab)>0) {
                        echo "<div class='display2'>Vous êtes connecté</div>";
                        echo "<style> .pt12 {display:none;} </style>";
                        $_SESSION["name"] = $tab[0]["name"];
                        $_SESSION["surname"] = $tab[0]["surname"];
                        $_SESSION["email"] = $tab[0]["email"];
                        $_SESSION["password"] = $tab[0]["password"];
                        $_SESSION["id"] = $tab[0]["id"];
                        header("Location: accueil.html");
                    }
                    else {
                        echo "<div class='display'>Les identifiants sont invalides</div>";
                    }
                }
                ?>

				<div class="text-center pt136">
					<a class="txt" href="register.php">
						Créer un compte
						<i class="fa fa-long-arrow-right ml5"></i>
					</a>
				</div>
			</form>
		</div>
	</div>
	


</body>
</html>