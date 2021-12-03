<?php
    if (isset($_POST['reset'])) {
        $annee = '';
        $constr = '';
        $type = '';
        $format = '';
        $graphics = '';
        $generation = '';        
    } else {
        $annee = isset($_POST['year']) ? $_POST['year'] : '';
        $constr = isset($_POST["constructor"]) ? $_POST['constructor'] : '';
        $type = isset($_POST["type"]) ? $_POST['type'] : '';
        $format = isset($_POST["format"]) ? $_POST['format'] : '';
        $graphics = isset($_POST["graphics"]) ? $_POST['graphics'] : '';
        $generation = isset($_POST["generation"]) ? $_POST['generation'] : '';
    }
?>
  <!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Location</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="icon" type="image/png" href="assets/favicon/icons/favicon.ico"/>
        <link rel="stylesheet" href="assets/styles/common/navbar.css">
        <link rel="stylesheet" href="assets/styles/common/footer.css">
        <link rel="stylesheet" href="assets/styles/location.css">
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
            <li class="nav-item"><a href="location.php" class="nav-link active">Location</a></li>
            <li class="nav-item"><a href="apropos.html" class="nav-link">À propos</a></li>

        </ul>
    </header>
</div>

<!--BANNIERE-->
<div class="container-banner">
    <img class="banniere" src="assets/images/ban01.jpg">
</div>



<!--TITRAGE-->
<h1>Liste des consoles</h1>


<!--FORMULAIRE SELECTION-->
<form method="post">
        <div>
            <div class="filtre">
                <label>Année</label> <br>
                <input class="reset" type="text" name="year" value="<?=$annee?>">
            </div>

            <div class="filtre">
                <label>Constructeur</label> <br>
                <select class="reset" name="constructor">
                    <option value=""></option>
                    <?php
                    $constructeurs = ["Amstrad", "Apf", "Apple","Atari","Bandai","Bss","Casio","Coleco","Commodore","Compagnies",
                    "Gakken","Hanimex","Henry","Interton","Magnavox","Mattel","Microsoft","Nec",
                    "Nichibutsu","Nintendo","Nokia","Ouya","Panasonic","Philips","Seb","Sega",
                    "Sèleco","Smith Engineering","Snk","Sony","Tomy","Valve","Vtech"];
                    foreach($constructeurs as $c) {
                        echo '<option value="' . $c . '"' . ($c == $constr ? 'selected' : '') . '>' . $c . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filtre">
                <label>Type</label> <br>
                <select class="reset" name="type">
                    <option value=""></option>
                <?php
                $typ = ["Portable", "Salon"];
                foreach($typ as $t) {
                        echo '<option value="' . $t . '"' . ($t == $type ? 'selected' : '') . '>' . $t . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filtre">
                <label>Format</label> <br>
                <select class="reset" name="format">
                    <option value=""></option>
                    <?php
                    $forma = ["Cartouche", "Disque", "Réseau"];
                    foreach($forma as $f) {
                        echo '<option value="' . $f . '"' . ($f == $format ? 'selected' : '') . '>' . $f . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filtre">
                <label>Graphisme</label> <br>
                <select class="reset" name="graphics">
                    <option value=""></option>
                    <?php
                    $graph = ["2D", "3D"];
                    foreach($graph as $g) {
                        echo '<option value="' . $g . '"' . ($g == $graphics ? 'selected' : '') . '>' . $g . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="filtre">
                <label>Génération</label> <br>
                <select class="reset" name="generation">
                <option value=""></option>
                <?php
                    $gene = ["première génération", "deuxième génération", "troisième génération", "quatrième génération", "cinquième génération", "sixième génération", "septième génération", "huitième génération", "neuvième génération"];
                    foreach($gene as $ge) {
                        echo '<option value="' . $ge . '"' . ($ge == $generation ? 'selected' : '') . '>' . $ge . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <p class="twobutt">
            <button class="butt01 input" type="submit" name="search">Rechercher</button>
            <button class="butt02 input" type="submit" name="reset">Rafraichir</button>
        </p>
    </form>

    <table id="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Modèle</th>
            <th scope="col">Année</th>
            <th scope="col">Constructeur</th>
            <th scope="col">Type</th>
            <th scope="col">Format</th>
            <th scope="col">Graphisme</th>
            <th scope="col">Génération</th>
        </tr>
        </thead>
        <tbody>
<?php
    try
    {
    $dsn = 'mysql:host=localhost;dbname=console_project';
    $dbh = new PDO($dsn, 'root', '');
    $where = '';
    $param = [];

    if (strlen($annee)) {
        $where = ' where year = :annee';
        $param['annee'] = $annee;
    }

    if (strlen($constr)) {
        if (strlen($where)) {
            $where .= ' and ';
        } else {
            $where .= ' where ';
        }
        $where .= ' constructor = :constr';
        $param['constr'] = $constr;
    }

    if (strlen($type)) {
        if (strlen($where)) {
            $where .= ' and ';
        } else {
            $where .= ' where ';
        }
        $where .= ' type = :type';
        $param['type'] = $type;
    }

    if (strlen($format)) {
        if (strlen($where)) {
            $where .= ' and ';
        } else {
            $where .= ' where ';
        }
        $where .= ' format = :format';
        $param['format'] = $format;
    }

    if (strlen($graphics)) {
        if (strlen($where)) {
            $where .= ' and ';
        } else {
            $where .= ' where ';
        }
        $where .= ' graphics = :graphics';
        $param['graphics'] = $graphics;
    }

    if (strlen($generation)) {
        if (strlen($where)) {
            $where .= ' and ';
        } else {
            $where .= ' where ';
        }
        $where .= ' generation = :generation';
        $param['generation'] = $generation;
    }

    $statement = $dbh->prepare('select * from console'.$where.' order by device_model');
    if (!$statement->execute($param)) {
        print '<p>Erreur de récupération des données : '. $statement->errorCode() .'</p>';
    }
    else {
        $rows = $statement->fetchAll();
        $count = count($rows);
        if ($count==0) {
            echo "Aucune console ne correspond aux critères selectionnés !";
            echo "<style>#table{display:none;}</style>";
        }
    }
    if (!empty($where)) {
        foreach ($rows as $row) {
            ?>
            <tr>
                <td><a class="monlien" href="reservation.php?id=<?= $row['id'] ?>"><?= $row['device_model'] ?></a></td>
                <td><?= $row['year'] ?></td>
                <td><?= $row['constructor'] ?></td>
                <td><?= $row['type'] ?></td>
                <td><?= $row['format'] ?></td>
                <td><?= $row['graphics'] ?></td>
                <td><?= $row['generation'] ?></td>
            </tr>
    
            <?php
        }
    }
    else {
        echo "<style>#table{display:none;}</style>";
    }

    
    $dbh = null;
}
catch(Exception $e) {
    echo 'Erreur : '.$e->getMessage();
}
?>
        </tbody>
    </table>


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