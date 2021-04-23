<?php

require_once '../component/header.php';
require '../functions/functions.php';
require_once '../functions/sql.php';
require_once  'bdd.php';

if (!empty($_SESSION['start']) && !empty($_SESSION['end']) &&  !empty($_SESSION['chambreId'])  &&  !empty($_SESSION['numberAdult'])  ) {
    $end = $_SESSION['end'];
    $start = $_SESSION['start'];
    $chambreId = $_SESSION['chambreId'];
    $numberAdult = $_SESSION['numberAdult'];
    $numberChild = $_SESSION['numberChild'];
    unset($_SESSION['start']);
    unset($_SESSION['end']);
    unset($_SESSION['chambreId']);
    unset($_SESSION['numberAdult']);
    unset($_SESSION['numberChild']);

}elseif (!empty($_POST['start']) && !empty($_POST['end']) &&  !empty($_POST['chambreId']) &&  !empty($_POST['numberAdult'])  &&  isset($_POST['numberChild'])){

    $startPost = $_POST['start'];
    $endPost = $_POST['end'];
    $numberAdult = $_POST['numberAdult'];
    $numberChild = $_POST['numberChild'];
    $startDateTime = new DateTime("$startPost");
    $endDateTime = new DateTime("$endPost");


    while ($startDateTime < $endDateTime) {
        $chambreId =  $_POST['chambreId'];
        $id = $_SESSION['id'];
        $dateStartFormatted = $startDateTime->format('Y-m-d H:i:s');
        $dateEndFormatted = $endDateTime->format('Y-m-d H:i:s');
        addReservation($dbh, $chambreId, $dateStartFormatted, $dateEndFormatted, $numberAdult, $numberAdult, $id);
        $startDateTime->add(new DateInterval('P1D'));
    }
    header('Location:./vosReservations.php');
}else{
    unset($_SESSION['start']);
    unset($_SESSION['end']);
    unset($_SESSION['chambreId']);
    unset($_SESSION['numberAdult']);
    unset($_SESSION['numberChild']);
    header('Location:../index.php');
}

?>



    <main>
    <div class="content">
        <p>Confirmez votre chambre en appuyant sur le bouton</p>
        <form method="post" action="">
            <input type="text" name="chambreId" value="<?php echo $chambreId ?>" hidden="hidden">
            <input type="text" name="start" value="<?php echo $start?>" hidden="hidden">
            <input type="text" name="end" value="<?php echo $end ?>" hidden="hidden">
            <input type="text" name="numberAdult" value="<?php echo $numberAdult?>" hidden="hidden">
            <input type="text" name="numberChild" value="<?php echo $numberChild ?>" hidden="hidden">
            <input  type="submit" id="decale" class="btn btn-primary taille" name="confirmReserv" value="Valider votre réservation">
        </form>
    </div>
<?php

//$startDateTime = new DateTime("$start");
//$endDateTime = new DateTime("$end");


?>



















