<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="assets/favicon/icons/favicon.ico"/>
    <link rel="stylesheet" href="assets/styles/common/navbar.css">
    <link rel="stylesheet" href="assets/styles/common/footer.css">
    <link rel="stylesheet" href="assets/styles/reservation.css">
    <link rel="stylesheet" href="assets/styles/common/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
</head>
<body>


<!--NAVBAR-->
<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3">
        <a href="accueil.html" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <img class="logosite"src="assets/images/logosite.png">
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="accueil.html" class="nav-link" aria-current="page">Accueil</a></li>
            <li class="nav-item"><a href="location.php" class="nav-link">Location</a></li>
            <li class="nav-item"><a href="apropos.html" class="nav-link">À propos</a></li>

        </ul>
    </header>
</div>

<!--BANNIERE-->
<div class="container-banner">
    <img class="banniere" src="assets/images/ban01.jpg">
</div>

    <h1 class="titre display">Réservation</h1>
<?php
try
{
    if (!isset($_GET["id"])) {
        echo "Aucune console n'a été selectionnée <br>";
        echo "<a href='selection.php'>Retour</a>";
    }
    else {
        $id = $_GET["id"];
        $dsn = 'mysql:host=localhost;dbname=console_project';
        $dbh = new PDO($dsn, 'root', '');
        $statement = $dbh->prepare("SELECT * FROM console WHERE id=:id");
        $statement->execute(["id"=>$id]);
        $rows = $statement->fetchAll();
        foreach ($rows as $row) {
            if ($row['stock'] > 0) {
                ?>
        <div class="col-lg-4  mx-auto">
            <form method="post">
                <div class="class">
                    <div class="champ">
                    Nom : <?=$_SESSION['name']?> <br>
                    Prénom : <?=$_SESSION['surname']?> <br>
                    E-mail : <?=$_SESSION['email']?> <br>
                    Vous allez louer une : <?php echo $row['device_model']; ?> <br>
                    Il reste <?php echo $row['stock']." ".$row['device_model']; ?> en stock
                </div>

                <div class="champ">
                    <label>Louer à partir du :</label>
                    <input type="date" name="date" value="<?=$date?>">
                </div>

                <div class="champ">
                    Vérification du mot de passe :
                    <input type="password" name="password">
                </div>

                <div class="champ">
                    <button id='reserv' type="submit" name="search">Réserver</button>
                </div>
                </div>
            </form>
        </div>
            
            <?php
        }
        else {
            echo "Il n'y a plus de ".$row['device_model']." en stock <br>";
            $statement = $dbh->prepare("SELECT * FROM console WHERE constructor=:constructor AND type=:type AND stock>=1");
            $statement->execute(["constructor"=>$row['constructor'], "type"=>$row['type']]);
            $rauws = $statement->fetchAll();
            echo "Peut-être que ces modèles pourraient vous intéresser : <br>";
            foreach ($rauws as $rauw) {
                echo "<a href='reservation.php?id=".$rauw['id']."'>".$rauw['device_model']."</a><br>";
            }
            echo "<br>";
        }

        if (isset($_POST["cancel"])) {
            $statement = $dbh->prepare("UPDATE console SET stock=stock+1 WHERE id=:id");
            $statement->execute(["id"=>$id]);
            echo "Votre commande a été annulée <br>";
        }

            if (isset($_POST["date"], $_POST["password"])) {
                if (!empty($_POST["date"] && !empty($_POST["password"]))) {
                    $date = isset($_POST['date']) ? $_POST['date'] : '';
                    $statement = $dbh->prepare("UPDATE console SET stock=stock-1 WHERE id=:id");
                    $dt = date('Y-m-d');
                    if ($date>=$dt) {
                        if ($_SESSION["password"] == $_POST["password"]) {
                            if ($row['stock'] >= 0) {
                                $statement->execute(["id"=>$id]);
                                echo "Votre commande a bien été prise en compte !";
                                echo "<style>#reserv{display:none;}</style>";
                                echo "<div><form method='post'><button type='submit' name='cancel'> Annuler </button></form></div>";
                                $statement = $dbh->prepare("INSERT INTO rental (id_console, id_user, date_location) VALUES (:id_console, :id_user, :date_location)");
                                $statement->execute(["id_console"=>$id, "id_user"=>$_SESSION['id'], "date_location"=>$date]);
                            }
                            else {
                                echo "Il n'y a plus de consoles en stock ! <br>";
                            }
                        }
                        else {
                            echo "Le mot de passe est invalide <br>";
                        }
                    }
                    else {
                        echo "La date est invalide <br>";
                    }
                }
                else {
                    echo "Tout les champs n'ont pas été remplis <br>";
                }
            }
        
        echo "<a href='location.php'>Retour</a>";
    }
        
        $dbh = null;
}
}
catch(Exception $e)
{
    print '<p>Erreur : '.$e->getMessage(). '</p>';
}
?>

<!--FOOTER-->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <h6>Rétro Gaming</h6>
                <p class="localisation">15 Avenue de la liberté<br>34000 MONTPELLIER</p>
            </div>

            <div class="col-xs-6 col-md-3">
                <h6>Projets</h6>
                <ul class="footer-links">
                    <li><a>Team buildings</a></li>
                    <li><a>Animation d'évènements</a></li>
                    <li><a>Jeux vidéo</a></li>
                </ul>
            </div>

            <div class="col-xs-6 col-md-3">
                <h6>Contactez-nous</h6>
                <ul class="footer-links">
                    <li><a>Services clients</a></li>
                    <li><a>Mentions légales</a></li>
                    <li><a>FAQ</a></li>
                </ul>
            </div>


            <div class="col-xs-6 col-md-3">
                <h6>Liens</h6>
                <ul class="footer-links">
                    <li><a>Nos partenaires</a></li>
                    <li><a>Location événement</a></li>
                    <li><a>Clients</a></li>
                    <li><a>Service technique</a></li>
                </ul>
            </div>
        </div>
        <hr>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-12">
                <p class="copyright-text">Copyright &copy;
                    <a>by 2021 Vincent - Lucas - Victor</a>.
                </p>
            </div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <ul class="social-icons">
                    <li><a class="facebook" href="#"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</body>
</html>