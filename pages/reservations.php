<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

if (isset($_SESSION['id'])){
    echo '<div class="content">';
    $uid = $_SESSION['id'];
    $count = countReservations($dbh, $uid);
    $rlists = getReservations($dbh, $uid, $count);
    $i = 0;
    foreach ($rlists as $rlist){
        $i = $i+1;
        $chid = $rlist['chambre_id'];
        $day = $rlist['jour'];
        $acompte = $rlist['acompte'];
        $payed = $rlist['paye'];

?>

    <div class="rlist">
        <form>
            <div class="">
                <label>Chambre numéro</label>
                <input readonly type="text" value="<?= $chid ?>">
            </div>
            <div class="">
                <label>Date</label>
                <input readonly type="date" value="<?= $day ?>">
            </div>
            <div class="">
                <label>Acompte</label>
                <input readonly type="text" value="<?php if ($acompte==1){echo "Il y a un acompte";}else{ echo "Il n'y a pas d'acompte";} ?>">
            </div>
            <div class="">
                <label>Paiement</label>
                <input readonly type="text" value="<?php if ($payed==1){echo "Chambre payée";}else{ echo "Chambre non payée";} ?>">
            </div>
        </form>
    </div>
        <br>


<?php
    }
    echo "</div>";
}
?>