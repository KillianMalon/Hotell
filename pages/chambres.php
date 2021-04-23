<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

?>

<style>
    .chambre{
        width: 85%;
        display: flex;
        flex-direction: row;
        align-items: center;
        border-radius: 15px;
        margin-bottom: -4%;
        padding: 3%;
        margin-left: 2%;
    }
    .picture{
        width: 25%;
    }
    .text{
        width: 100%;
        height: 100%;
        padding-left: 5%;
    }
    .description{
        width: 85%;
    }
    h5 {
        text-decoration: black
    }
    img{
        width: 100%;
        border-radius: 10px;
    }
    .price{
        color : #f57465;
    }
    .button{
        padding: 30%;
        border-radius: 15px;
        border: none;
        background-color: #f57465;
        cursor:pointer;
        color: white;
        transition: background-color 500ms ease-out ;
    }
    .button:hover{
        background-color: rgb(241, 84, 84);
        /*box-shadow: 2px 2px 12px grey;*/
    }
    .prix {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    #check:checked ~ .content{
        margin-left: 60px;
    }
    .capacity{
        color: lightslategrey ;
    }
    @media screen and (max-width:600px){
        .description{
            display: none;
        }
        .text{
           display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding-right: 5%;
        }
        picture{
            width: 45%;
        }
    }
</style>
<?php ?>
<div class="content">

        <br><br>
        <?php

    ?>
        <?php
        if (empty($_GET['sort']) && empty($_POST['search'])){
            $chambres = getAllRoom($dbh);

        }elseif(!empty($_GET['sort']) && empty($_POST['search'])){
            if ($_GET['sort'] == "nomAZ") {
                $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id ORDER BY id ASC ');
            }else if ($_GET['sort'] == "nomZA"){
                $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id ORDER BY id DESC ');
            }else if ($_GET['sort'] =="prixCroissant"){
                $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id ORDER BY tarif_id ASC');
            }else if ($_GET['sort'] =="prixDecroissant"){
                $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id ORDER BY tarif_id DESC ');
            }
            if (!empty($statement)) {
                $statement->execute(); // execute le SQL dans la base de données (MySQL / MariaDB)
                $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
            }
        }elseif (!empty($_POST['search'])){
            $search = $_POST['search'];
            $statement = $dbh->prepare("SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND chambres.id = '$search' ORDER BY tarifs.prix ASC ");
            $statement->execute();
            $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($chambres)) {
                $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND prix LIKE "%' . $search . '%" ORDER BY tarifs.prix ASC ');
                $statement->execute();
                $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
                if (empty($chambres)) {
                    $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND exposition LIKE "%' . $search . '%" ORDER BY tarifs.prix ASC ');
                    $statement->execute();
                    $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($chambres)) {
                        $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND libelle LIKE "%' . $search . '%" ORDER BY tarifs.prix ASC ');
                        $statement->execute();
                        $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
                        if (empty($chambres)) {
                            $statement = $dbh->prepare('SELECT chambres.*,tarifs.prix, libelle FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND description LIKE "%' . $search . '%" ORDER BY tarifs.prix ASC ');
                            $statement->execute();
                            $chambres = $statement->fetchAll(PDO::FETCH_ASSOC);
                        }
                    }
                }
            }
        }
        ?>

        <?php if (!empty($chambres)): ?>
                    <?php foreach ($chambres as $chambre): ?>
                        <div class="chambre">
                            <div class="picture"><img src="<?php echo($chambre['image'])?>"></div>
                                <div class="text">
                                    <h3>Chambre <?php echo($chambre['id'])?></h3>
                                    <p class="description"><?php echo($chambre['description'])?></p>
                                    <p class="capacity"><?php echo($chambre['capacite'])?>  <?php echo $chambre['capacite'] < 2 ? 'personne' : 'personnes' ;?></p>
                                </div>
                                <div class="prix" >
                                    <h2 class="price"><?php echo($chambre['prix'])?> €</h2>
                                    <a href="infoChambre.php?id=<?php echo($chambre['id'])?>"><button class="button">Réserver</button></a>
                                </div>
                        </div>
                    <?php endforeach; ?>
        <?php else: ?>
            <div class="milieu">
                <p> Aucun résultat pour votre recherche</p>
            </div>
        <?php endif; ?>
        <br><br>
</div>



