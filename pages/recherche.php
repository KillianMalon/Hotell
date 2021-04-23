<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

$nbjour = 0;

$today = date("Y-m-d");

//Si la réservation dure plus d'une nuit :
if (isset($_POST['depart']) and !empty($_POST['depart'])){
    ?>
          <div class="content">
          <div class="client">
    <?php

    //Stockage des informations du $_POST
    $arrivee = $_POST['arrivee'];
    $depart = $_POST['depart'];
    $adulte = $_POST['adulte'];
    $enfant = $_POST['enfant'];
    $total = $adulte + $enfant;
    $exposition = $_POST['exposition'];
    $idprix = $_POST['prix'];

    //On transforme un string en date
    $datearrivee = new DateTime("$arrivee");
    $datedepart = new DateTime("$depart");


    //Si la date entrée est avant aujourd'hui, ou que la date de départ est avant la date d'arrivée : afficher une erreur/
    if ($today < $datearrivee or $datedepart < $datearrivee){
        ?>
        <div>
            <label>La date saisie n'est pas valide</label>
            <a href="../index.php">Retour à l'Accueil</a>
        </div>
       <?php


    //Sinon
    }else{


        //On compte le nombre de jours séléctionnés
        while ($datearrivee <= $datedepart){
            $nbjour++;
            $datearrivee->add(new DateInterval('P1D'));
        }

        //On effectue la requête SQL avec les tags et on la stock dans un tableau
        $tags = tagSearch($dbh, $total, $exposition, $idprix, $arrivee, $depart);


        //Pour chaque chambre disponible correspondant aux critères séléctionnés, on affiche :
        foreach ($tags as $tag){
            $chid = $tag['chambre_id'];
            $chtarid = $tag['tarif_id'];
            $chexp = $tag['exposition'];
            $chcap = $tag['capacite'];
            $chetage = $tag['etage'];
            $chprix = getPricebyid($dbh, $chtarid);
            $prixtoto = $chprix['prix'];
            ?>
                  <form method="post" action="reserver.php">
                      <h3>2 dates</h3>
                      <div>
                          <label>Chambre numéro <?= $chid ?></label>
                      </div>
                      <div>
                          <label>Prix : <?= $prixtoto ?> €</label>
                      </div>
                      <div>
                          <label>Capacité : <?= $chcap ?> personnes</label>
                      </div>
                      <div>
                          <label>Exposition : <?= $chexp ?></label>
                      </div>
                      <div>
                          <label>Etage numéro <?= $chetage ?></label>
                      </div>

                  </form>
    <?php
        }
    }
    ?>
          </div>
      </div>
<?php
}

//Si la réservation ne dure qu'une nuit :
else{
    ?>
    <div class="content">
    <div class="client">
    <?php

    $arrivee = $_POST['arrivee'];
    $adulte = $_POST['adulte'];
    $enfant = $_POST['enfant'];
    $total = $adulte + $enfant;
    $exposition = $_POST['exposition'];
    $idprix = $_POST['prix'];

    $datearrivee = new DateTime("$arrivee");
    if ($today < $datearrivee){
        ?>
        <div>
            <label>La date saisie n'est pas valide</label>
            <a href="../index.php">Retour à l'Accueil</a>
        </div>
        <?php

    }else{

        $tags = tagSearchOne($dbh, $total, $exposition, $idprix, $arrivee);

    foreach ($tags as $tag){
        $chid = $tag['chambre_id'];
        $chtarid = $tag['tarif_id'];
        $chexp = $tag['exposition'];
        $chcap = $tag['capacite'];
        $chetage = $tag['etage'];
        $chprix = getPricebyid($dbh, $chtarid);
        $prixtoto = $chprix['prix'];
        ?>
        <form>
            <h3>1 date</h3>
            <div>
                <label>Chambre numéro <?= $chid ?></label>
            </div>
            <div>
                <label>Prix : <?= $prixtoto ?> €</label>
            </div>
            <div>
                <label>Capacité : <?= $chcap ?> personnes</label>
            </div>
            <div>
                <label>Exposition : <?= $chexp ?></label>
            </div>
            <div>
                <label>Etage numéro <?= $chetage ?></label>
            </div>
        </form>
        <?php
    }
        ?>
        </div>
        </div>
        <?php
    }
}
?>