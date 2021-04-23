<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    $clientinfo = getClient($dbh, $id);

    $fname = $clientinfo['prenom'];
    $lname = $clientinfo['nom'];
    $mail = $clientinfo['mail'];
    $pwd = $clientinfo['password'];
    $address = $clientinfo['adresse'];
    $pc = $clientinfo['codePostal'];
    $town = $clientinfo['ville'];
    $cid = $clientinfo['pays_id'];
    $img = $clientinfo['image'];
    $country = getCountrybyid($dbh, $cid);
    $civility = $clientinfo['civilite'];


    ?>
    <div class="content">
        <div class="client">
            <img class="mobile_profile_image" style="width: 100px; margin-right: 30px;" src="<?= $img ?>">
            <!-- le "=" a la place de "php" remplace le "php" et un "echo" -->
            <h4> Prénom : <?= $fname ?></h4>

            <h4>Nom : <?= $lname ?></h4>

            <h4>Mail : <?= $mail ?></h4>

            <h4>Mot de passe : ********* </h4>

            <h4>Adresse : <?= $address ?></h4>

            <h4>Code Postal : <?= $pc ?></h4>

            <h4>Vile : <?= $town ?></h4>

            <h4> Pays :<?= $country['nom_fr_fr'] ?> </h4>
            <h4>Civilité : <?= $civility ?></h4>
            <h4>Image : <?= $img ?></h4>
            <a href="update.php">Modifier </a>
        </div>
    </div>

<!--    </div>-->
<!--    </div>-->

    </body>
    </html>

    <?php
}
?>