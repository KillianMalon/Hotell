<?php
require_once '../component/header.php';
require_once '../functions/sql.php';
require_once 'bdd.php';
if(isset($_POST['send']) AND !empty($_POST['send'])){
    if(!empty($_POST['firstName']) AND !empty($_POST['lastName']) AND !empty($_POST['mail']) AND !empty($_POST['mailVerify']) AND !empty($_POST['password']) AND
        !empty($_POST['passwordVerify']) AND !empty($_POST['address']) AND !empty($_POST['postalCode']) AND !empty($_POST['city'])){
        $firstName = htmlspecialchars($_POST['firstName']);
        $firstNameLength = strlen(mbUcfirst(mb_strtolower($firstName)));
        if($firstNameLength <= 256){
            $lastName = htmlspecialchars($_POST['lastName']);
            $lastNameLength = strlen(mbUcfirst(mb_strtolower($lastName)));
            if($lastNameLength <= 256){
                $mail = htmlspecialchars($_POST['mail']);
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $mailCount = mailCheck($dbh, $mail);
                    if($mailCount === 0){
                        $mailVerify = htmlspecialchars($_POST['mailVerify']);
                        if($mail === $mailVerify){
                            $password = sha1($_POST['password']);
                            $passwordVerify = sha1($_POST['passwordVerify']);
                            if($password === $passwordVerify){
                                $passwordLength = strlen($password);
                                if($passwordLength >= 7 || $passwordLength <= 30){
                                    $address = htmlspecialchars($_POST['address']);
                                    $addressLength = strlen($address);
                                    if($addressLength < 255){
                                        $postalCode = intval($_POST['postalCode']);
                                        $postalCodeLength = strlen($postalCode);
                                        if($postalCodeLength >= 3 || $postalCodeLength <=5){
                                            $city = htmlspecialchars($_POST['city']);
                                            $cityLength = strlen($city);
                                            if($cityLength <= 255){
                                                $country = intval($_POST['country']);
                                                if($country <= 241){
                                                    $civility = htmlspecialchars($_POST['civility']);
                                                    if($civility === "Monsieur" || $civility === "Madame"){
                                                        if (filter_var($_POST['image'], FILTER_VALIDATE_URL)) {
                                                            if(isset($_POST['image']) AND !empty($_POST['image'])) {
                                                                $extensionOk = array('.jpg', '.png', 'webp','.gif','jpeg','.psd', '.svg');
                                                                $lastCharacters = substr($_POST['image'], -4);
                                                                if(in_array($lastCharacters,$extensionOk)){
                                                                    $image = $_POST['image'];
                                                                    inscription($dbh, $firstName, $lastName, $mail, $password, $address, $postalCode, $city, $country, $civility, $image);
                                                                    $user = getUserByMail($dbh, $mail);
                                                                    $_SESSION['id'] = $user['id'];
                                                                }else{
                                                                    $error = "Veuillez saisir une url d'image correcte!";
                                                                }
                                                            }else{
                                                                $image = "https://i.ibb.co/47nY0vM/default-avatar.jpg";
                                                            }

                                                        } else {
                                                            $error = "L'url n'est pas valide, veuillez en saisir un valide !";
                                                        }
                                                    }else{
                                                        $error = "Il ne faut pas modifier la valeur des intput (je te vois) ";
                                                    }
                                                }else{
                                                    $error = "Veuillez ne pas modifier la 'value' de notre select petit malin !!";
                                                }
                                            }else{
                                                $error = "Le nom de la ville est trop long veuillez en saisir un valide";
                                            }
                                        }else{
                                            $error = "Le code postal ne peut contenir moins de 3 chiffres ou plus de 5 chiffres !";
                                        }
                                    }else{
                                        $error = "Votre adresse est trop longue";
                                    }
                                }else{
                                    $error = "Votre mot de passe doit faire entre 7 et 30 caractères !";
                                }
                            }else{
                                $error = "Le mot de passe et sa confirmation ne correspondent pas, veuillez les ressaisir !";
                            }
                        }else{
                            $error = "Le mail et sa vérification ne correspondent pas !";
                        }
                    }else{
                        $error = "Ce mail est déjà utilisé veuillez en saisir un autre !";
                    }
                }else{
                    $error = "Votre mail est invalide, veuillez en saisir un valide!";
                }
            }else{
                $error = "Votre nom est trop long, veuillez le ressaisir !";
            }

        }else{
            $error = "Votre prénom est trop long, veuillez le ressaisir!";
        }
    }else{
        $error = "Veuillez remplir tous les champs!";
    }
}

?>
<div class="contentInscription">
    <div class="inscriptionH1">
        <h1>Formulaire d'inscription</h1>
    </div>
    <form action="" method="post">
        <?php
        var_dump($_POST);
        ?>
        <div class="divInfos">
            <div>
                <div>
                    <label for="" >Nom* :</label>
                </div>
                <input type="text" name="lastName">
            </div>
            <div>
                <div>
                    <label for="">Prénom* :</label>
                </div>
                <input type="text" name="firstName">
            </div>
        </div>
        <br>

        <div class="divInfos2">
            <div>
                <label for="">Adresse email :*</label>
            </div>
            <input type="mail" name="mail">
        </div>
        <br>
        <div class="divInfos2">
            <div>
                <label for="">Confirmer Adresse email :*</label>
            </div>
            <input type="mail" name="mailVerify">
        </div>


        <br>

        <div class="divInfos2">
            <div>
                <label for="">Mot de passe :*</label>
            </div>
            <input type="password" name="password">
        </div>
        <br>
        <div class="divInfos2">
            <div>
                <label for="">Confirmer mot de passe :*</label>
            </div>
            <input type="password" name="passwordVerify">
        </div>
        <br>

        <div class="divInfos2">
            <div>
                <label for="">Adresse :*</label>
            </div>
            <input type="text" name="address">
        </div>
        <br>
        <div class="divInfos2">
            <div>
                <label for="">Code postal :*</label>
            </div>
            <input type="number" name="postalCode">
        </div>

        <br>

        <div class="divInfos2">
            <div>
                <label for="">Ville :*</label>
            </div>
            <input type="text" name="city">
        </div>
        <br>
        <div class="divInfos2">
            <div>
                <label for=""> Photo de profil :</label>
            </div>
            <input type="url" name="image">
        </div>
        <br>

        <div class="divInfos2">
            <select name="country">
                <option value="">Pays</option>
                <?php
                $allCountry = getCountry($dbh);
                foreach($allCountry as $country){
                    $countryId = $country['id'];
                    $countryName = $country['nom_fr_fr'];
                    ?>
                    <option value="<?php echo $countryId?>"><?php echo $countryName; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <br>
        <div class="divInfos2">
            <select name="civility" id="">
                <option value="Monsieur" default>Monsieur</option>
                <option value="Madame">Madame</option>
            </select>
        </div>
        <br>
        <div class="errorMessageInscription">
            <p style="color:red;">
                <?php
                if(isset($error) AND !empty($error)){
                    echo $error;
                }
                ?>
            </p>
        </div>
        <br>
        <div class="divInfos3">
            <input type="submit" name="send" value="S'inscrire">
        </div>
    </form>
    <br>
</div>
</body>
</html>