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
    $civility = $clientinfo['civilite'];
    $country = getCountrybyid($dbh, $cid);
    //on entre que si y a un clique sur le bouton du formulaire
    if(isset($_POST['formModifications'])) {
        //on vérifie que la variable n'est pas vide, qu'elle existe puis on vérifie qu'elle est différente de celle de base, si elle ne l'est pas
        //on ne rentre pas dans la condition
        if (isset($_POST['fname']) and !empty($_POST['fname']) and $_POST['fname'] != $fname) {
            //on sécurise firtName pour éviter les injections direct dans la base de donnée
            $firstName = htmlspecialchars($_POST['fname']);
            $firstNameLength = strlen($firstName);
            if ($firstNameLength >= 1 && $firstNameLength <= 150) {
                updateFirstName($dbh, $firstName, $id);
            } else {
                $error = "Le prénom doit faire entre 1 et 150 caractères !";
            }
        }
        if (isset($_POST['lname']) and !empty($_POST['lname']) and $_POST['lname'] != $lname) {
            $lastName = htmlspecialchars($_POST['lname']);
            $lastNameLength = strlen($lastName);
            if ($lastNameLength >= 1 && $firstNameLength <= 150) {
                updateLastName($dbh, $lastName, $id);

            } else {
                $error = "Le nom doit faire entre 1 et 150 caractères !";
            }
        }
        if (isset($_POST['pwd']) and !empty($_POST['pwd']) and isset($_POST['pwd2']) and !empty($_POST['pwd2'])) {
            $passwordLength = strlen($_POST['pwd']);
            if ($passwordLength >= 7 && $passwordLength <= 25) {
                $password = sha1($_POST['pwd']);
                $passwordVerif = sha1($_POST['pwd2']);
                if ($password === $passwordVerif) {
                    updatePassword($dbh, $password, $id);
                } else {
                    $error = "Le mot de passe et sa confirmation ne correspondent pas, veuillez les ressaisir";
                }
            } else {
                $error = "Le mot de passe doit faire entre 7 et 25 caractères";
            }
        }
        if (isset($_POST['mail']) and !empty($_POST['mail']) and $_POST['mail'] != $mail) {
            $mailModif = htmlspecialchars($_POST['mail']);
            if (filter_var($mailModif, FILTER_VALIDATE_EMAIL)) {
                $nbMailUser = getUserByMailForVerif($dbh, $mailModif);
                if ($nbMailUser === 0) {
                    updateMail($dbh, $mailModif, $id);
                }else{
                    $error = "Un  autre utilisateur utilise déjà ce mail, veuillez en saisir un autre !";
                }
            }else{
                $error = "Le mail est invalide !";
            }
        }
        if(isset($_POST['address']) and !empty($_POST['address']) and $_POST['address'] != $address){
            $addressModif = htmlspecialchars($_POST['address']);
            $addressLength = strlen($addressModif);
            if($addressLength >= 7 && $addressLength <= 255){
                updateAddress($dbh, $addressModif,$id);
            }else{
                $error = "L'adresse doit faire entre 7 et 255 caractères !";
            }
        }
        if(isset($_POST['pc']) and !empty($_POST['pc']) and $_POST['pc'] != $pc) {
            $pcModif = intval($_POST['pc']);
            $pcModifLength = strlen($pcModif);
            if($pcModifLength >=3 && $pcModifLength <= 5){
                updatePostalCode($dbh, $pcModif, $id);
            }else{
                $error = "Veuillez saisir un code postal valide!";
            }
        }
        if(isset($_POST['town']) and !empty($_POST['town']) and $_POST['town'] != $town) {
            $city = htmlspecialchars($_POST['town']);
            $cityLength = strlen($city);
            if($cityLength >= 2 && $cityLength <= 255){
                updateCity($dbh, $city, $id);
            }else{
                $error = "Le nom de la ville doit contenir entre 2 et 255 caractères ! ";
            }
        }
        if(isset($_POST['country']) and !empty($_POST['country']) and $_POST['country'] != $country) {
            $countryModif = intval($_POST['country']);
            $countryModifLength = strlen($countryModif);
            if($countryModifLength >= 1 && $countryModifLength <= 255){
                updateCountry($dbh, $countryModif, $id);
            }else{
                $error = "Pays invalide !";
            }
        }
        if(isset($_POST['civility']) and !empty($_POST['civility']) and $_POST['civility'] != $civility) {
            $civilityModif = htmlspecialchars($_POST['civility']);
            $civilityModifLength = strlen($civilityModif);
            if($civilityModifLength === 8 || $civilityModifLength === 6){
                updateCivility($dbh, $civilityModif, $id);
            }else{
                $error = "Petit malin, c'est pas bien de modif le select";
            }
        }
        if(isset($_POST['img']) and !empty($_POST['img']) and $_POST['img'] != $img) {
            $image = $_POST['img'];
            //on vérifie qu'il s'agit d'une url valide avec filter_var
            if(filter_var($image, FILTER_VALIDATE_URL)){
                //on récupère les 4 derniers caractères de $lastCharacters
                $lastCharacters = substr($image, -4);
                //on stocke dans un tableau les différentes extensions
                $extensionOk = array('.jpg', '.png', 'webp','.gif','jpeg','.psd', '.svg');
                if(in_array($lastCharacters,$extensionOk)){
                    updateUserPP($dbh, $image, $id);
                }
            }else{
                $error = "Veuillez saisir l'url d'une image correct !";
            }
        }
        header("Location:update.php");
        }
    var_dump($_POST);
    ?>
    <div class="content">
        <div class="client">
            <img class="mobile_profile_image" style="width: 100px; margin-right: 30px;" src="<?= $img ?>">
            <form method="post">
                <div>
                    <label>Prénom</label>
                    <input name="fname" type="text" value="<?= $fname ?>">
                </div>
                <div>
                    <label>Nom</label>
                    <input name="lname" type="text" value="<?= $lname ?>">
                </div>
                <div>
                    <label>Mail</label>
                    <input name="mail" type="email" value="<?= $mail ?>">
                </div>
                <div>
                    <label>Mot de passe</label>
                    <input name="pwd" type="password" value="">
                </div>
                <div>
                    <label>Confirmer mot de passe</label>
                    <input name="pwd2" type="password">
                </div>
                <div>
                    <label>Adresse</label>
                    <input name="address" type="text" value="<?= $address ?>">
                </div>
                <div>
                    <label>Code Postal</label>
                    <input name="pc" type="text" value="<?= $pc ?>">
                </div>
                <div>
                    <label>Ville</label>
                    <input name="town" type="text" value="<?= $town ?>">
                </div>
                <div>
                    <label>Image :</label>
                    <input name="img" type="link" value="<?= $img ?>">
                </div>
                <div>
                    <label>Pays</label>
                    <select name="country" id="">
                        <option default value="<?php echo $cid?>"><?php echo $country['nom_fr_fr'] ?></option>
                        <?php
                        $allCountry = getCountry($dbh);
                        foreach($allCountry as $country){
                            $countryId = $country['id'];
                            $countryName = $country['nom_fr_fr'];
                            if($countryId != $cid){
                                ?>
                                <option value="<?php echo $countryId?>"><?php echo $countryName; ?></option>

                                <?php
                            }
                        }
                        ?>
                        </select>
                </div>
                <div>
                    <select name="civility">
                        <option <?php if($civility === "Monsieur"){ echo "selected";}else{ " "; } ?>>Monsieur</option>
                        <option <?php if($civility === "Madame"){ echo "selected";}else{ " "; } ?>>Madame</option>
                    </select>

                </div>
                <br>
                <div>
                    <input type="submit" name="formModifications"  value="Modifier">
                </div>
            </form>
        </div>
    </div>

            <?php } ?>