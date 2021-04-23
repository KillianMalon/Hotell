<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';

if(isset($_GET['client'])){
    $id = intval($_GET['client']);
    $client = getClient($dbh, $id);
    $fname = $client['prenom'];
    $lname = $client['nom'];
    $mail = $client['mail'];
    $civility = $client['civilite'];
    $img = $client['image'];
    $rank = $client['type'];
}
?>
<div class="content">
    <form method="post">
        <div>
            <label>Prénom :</label>
            <input type="text" name="fname" value="<?php echo isset($fname)? $fname: ""?>">
        </div>
        <div>
            <label>Nom :</label>
            <input type="text" name="lname" value="<?php echo isset($lname)? $lname: " " ?>">
        </div>
        <div>
            <h4>Mail :<?php echo isset($mail)? $mail : " " ?> </h4>
        </div>
        <div>
            <label>Civilite :</label>
            <input type="text" value="<?php echo isset($civility)?$civility: " " ?>">
        </div>
        <div>
            <label>Image :</label>
            <input type="text" name="img" value="<?php echo isset($img)?$img: " " ?>">
        </div>
        <div>
            <label>Type d'utilisateurs : </label>
            <input type="text" value="<?php echo isset($rank)? $rank:""?>">
        </div>
        <input type="submit" value="Modifier">
    </form>
</div>
<img src="">
