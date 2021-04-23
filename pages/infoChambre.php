<?php
require_once '../component/header.php';
require_once '../functions/functions.php';
require_once '../functions/sql.php';
require_once  'bdd.php';


if(!empty($_POST['start']) && !empty($_POST['end']) && !empty($_POST['idChambre']) && !empty($_POST['capacity']) && !empty($_POST['numberAdult']) && isset($_POST['numberChild'])){

    $start = $_POST['start'];
    $end = $_POST['end'];
    $capacity = $_POST['capacity'];

    $trueStartDate = checkDateFormat($start);
    $trueEndDate = checkDateFormat($end);

    $numberAdult = intval($_POST['numberAdult']);
    $numberChild = checkCapacityChild($_POST['numberChild']);



    if ($trueStartDate == 1 && $trueEndDate == 1 && !empty($numberAdult) && !empty($numberChild)){
        if (!empty($_GET['id'])) {
            $numeroChambre = $_GET["id"];
            $capacite2 = getCapacity($dbh, $numeroChambre);
            if (!empty($capacite2)) {
                $capacite3 = $capacite2[0];
            }
        }
        var_dump($_POST);
        $capacite = $numberAdult + $numberChild;
        if ($capacite <= $capacite3['capacite']) {
            $dateStart = new DateTime("$start");
            $dateEnd = new DateTime("$end");
            $dateStartFormatted = $dateStart->format('Y-m-d H:i:s');
            $dateEndFormatted = $dateEnd->format('Y-m-d H:i:s');
            if ($dateStart < $dateEnd) {
                $diff = $dateStart->diff($dateEnd);
                $diffFormatted = $diff->format('%M');

                if ($diffFormatted < 1) {

                    while ($dateStart < $dateEnd) {
                        $id = $_GET['id'];
                        $reservationsCheck = checkReservationsEmpty($dbh, $dateStartFormatted, $id);
                        if (!empty($reservationsCheck)) {
                            $arrayCheck = array($reservationsCheck[0]['jour']);
                        }
                        $dateStart->add(new DateInterval('P1D'));
                    }
                    if (empty($arrayCheck)) {
                        $_SESSION['chambreId'] = $_GET['id'];
                        $_SESSION['start'] = $dateStartFormatted;
                        $_SESSION['end'] = $dateEndFormatted;
                        $_SESSION['numberAdult'] = $numberAdult;
                        $_SESSION['numberChild'] = $numberChild;
                        if (!empty($_SESSION['start']) && !empty($_SESSION['end']) &&  !empty($_SESSION['chambreId'])  &&  !empty($_SESSION['numberAdult']) && isset($_SESSION['numberChild'])) {
                        header('Location:./confirmReservation.php');
                        }
                    }
                }
            }
        }
    }
}
?>
<style>

    .reserver{
        display: flex;
        flex-direction: row;
    }
    @media screen and (max-width: 780px) {
        .reserver{
            display: flex;
            flex-direction: column;
        }
    }
    .decale{
        margin-left: 5%;
    }
    .bass{
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        font-size: 3.9rem;
        color: rgb(37, 34, 34);
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        height: 100vh;
        text-shadow: 2px 0 0 rgb(255, 255, 255), 2px 2px 0 rgb(255, 255, 255), 0 2px 0 rgb(255, 255, 255), -2px 2px 0 rgb(255, 255, 255), -2px 0 0 rgb(255, 255, 255), -2px -2px 0 rgb(255, 255, 255), 0 -2px 0 rgb(255, 255, 255), 2px -2px 0 rgb(255, 255, 255);
        background-repeat: no-repeat;
        background-size: cover;
    }
    label{
        text-decoration: underline;
    }
    h5{
        text-decoration: underline;
    }
</style>


<?php
if (!empty($_GET['id'])) {
    $numeroChambre = $_GET["id"];
    $chambre = getRoom($dbh, $numeroChambre);
    if (!empty($chambre)) {
        $chambres = $chambre[0];

    }
}
?>
<?php
$today =date("Y-m-d");

$tomorrow  = new DateTime();

$tomorrow->add(new DateInterval('P1D'));

$tomorrowFormatted = $tomorrow->format('Y-m-d');

?>
<main>
    <?php if (!empty($chambres)): ?>
    <div class="content">

        <br><br>

            <div class="container">
                <div class="picture">
                    <img src="<?php echo($chambres['Liens'])?>" class="card-img-top" style="height: 100%;" alt="...">
                </div>
                <div class="text">
                        <h3 class="card-title">Chambre <?php echo $numeroChambre;?></h3>
                        <p class="card-text"><?php echo ($chambres['description']);?></p>
                        <p>Etage : <?php echo ($chambres['etage']);?></p>
                        <p>Equipement : <?php echo ($chambres['douche']);?> douche</p>
                        <p>Capacité : <?php echo ($chambres['capacite']);?></p>
                        <p>Exposition : <?php echo ($chambres['exposition']);?></p>
                        <h4><?php echo ($chambres['prix']);?>€</h4>

                        <?php if(!empty($_POST['start']) && !empty($_POST['end'])) {
                            if (!empty($diffFormatted)&&$diffFormatted >= 1){
                                echo "<br><strong><h5>Veuillez rensiegner une durée de séjour d'un mois maximum</h5></strong>";
                            }elseif (!isset($diffFormatted)){
                                echo "<br><strong><h5>Veuillez rensiegner une date de départ supérieur à la date d'arrivée</h5></strong>";
                            }elseif (!empty($arrayCheck)){
                                echo "<br><strong><h5>Une date ou toutes les dates sélectionnés sont déja réservées</h5></strong>";
                            }
                        }?>
                        <br>
                        <form method="post" >
                        <div  class="reserver">
                            <div style="margin-right: 5%;">
                                <label for="" >Date d'arrivée</label>
                                <br>
                                <input type="date" name="start" value="<?php echo $today?>" min="<?php echo $today?>" >
                            </div>
                            <div>
                                <label for="start" >Date de départ</label>
                                <br>
                                <input type="date" name="end" value="<?php echo $tomorrowFormatted?>" min="<?php echo $tomorrowFormatted?>">
                            </div>
                            <div class="decale">
                                <label for="start">Nombres d'adultes</label>
                                <br>
                                <select class="form-select form-select-sm"  id="validationServer04" aria-describedby="validationServer04Feedback" name="numberAdult" required>
                                    <option selected disabled value="">Choisir ...</option>
                                    <?php for ($i = 1; $i <= $chambres['capacite']; $i++):?>
                                        <option><?php echo $i ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>
                            <div class="decale">
                                <label for="start">Nombres d'enfants</label>
                                <br>
                                <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="numberChild" required>
                                    <option selected disabled value="">Choisir ...</option>
                                    <?php for ($i = 0; $i <= $chambres['capacite']; $i++):?>
                                        <option><?php echo $i ?></option>
                                    <?php endfor;?>
                                </select>
                            </div>
                        </div>
                        <br><br><br><br>

                            <input type="number" name="idChambre" hidden="hidden" value="<?php echo($chambres['id']); ?>">
                            <input type="number" name="capacity" hidden="hidden" value="<?php echo($chambres['capacite']); ?>">
                            <input type="submit"  class="btn btn-primary" value="Cliquez pour valider réservation">

                        </form>

                </div>

    </div>
       <br><br><br>
    <?php else: ?>
        <div class="bass">
            <p> Aucune chambre trouvée</p>
        </div>
    <?php endif;?>