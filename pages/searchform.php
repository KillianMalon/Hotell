<?php
if ($index=1){
    require_once './functions/sql.php';
    require_once './pages/bdd.php';
}
else{
    require_once '../functions/sql.php';
    require_once 'bdd.php';
}


$prices = getPrices($dbh);


//Formulaire de recherche de disponibilité des chambres
?>
<div class="content">
    <form method="post" action="./pages/recherche.php">
        <div>
            <input name="arrivee" type="date" placeholder="Arrivée" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
        </div>
        <div>
            <input name="depart" type="date" placeholder="Départ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
        </div>
        <div>
            <select required name="adulte">
                <option value="">Adulte(s)</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
        <div>
            <select name="enfant">
                <option value="0">Enfant(s)</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
        <div>
            <select name="exposition">
                <option value="0">Exposition</option>
                <option value="port">Port</option>
                <option value="rempart">Rempart</option>
            </select>
        </div>
        <div>
            <select name="prix">
                <option value="0">Prix maximum</option>
                <option value="0">Peu importe</option>
                <?php
                foreach($prices as $price){
                    $id = $price['id'];
                    $prix = $price['prix'];
                    ?>
                    <option value="<?= $id ?>"><?= $prix ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <button type="submit">Rechercher</button>
    </form>
</div>

</body>
</html>