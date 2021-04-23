<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

$rooms = getAllRooms($dbh);

foreach ($rooms as $room){
    $id = $room['id'];
    $tarifid = $room['tarif_id'];

    $prix = getPricebyid($dbh, $tarifid);
    ?>

<div class="content">
    <div class="client">
        <form>
            <div>
                <label>Chambre numéro</label>
                <input name="id" value="<?= $id ?>">
            </div>
            <div>
                <label>Prix</label>
                <select name="price">
                    <option default value="<?php echo $tarifid; ?>"><?php echo $prix; ?> €</option>
                    <?php
                    $allPrices = getPrices($dbh);
                    foreach ($allPrices as $prices){
                        $pid = $prices['id'];
                        $pprice = $prices['prix'];
                        if ($pprice != $prix){
                            ?>
                            <option value="<?= $pid ?>"><?= $pprice ?> €</option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </form>
        <?php var_dump($rooms); var_dump($prix);?>
    </div>
</div>

<?php
}
?>