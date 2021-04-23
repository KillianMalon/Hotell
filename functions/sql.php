<?php

//Fonction pour obtenir les informations de tous les pays
function getCountry($dbh)
{
    $query = $dbh->prepare( 'SELECT * FROM pays' );
    $query->execute();
    return $query->fetchAll();
}

//Fonction pour obtenir les informations d'une chambre et son prix avec l'id de la chambre
function getRoom($dbh, $numeroChambre)
{
    $query = $dbh->prepare( 'SELECT chambres.*,tarifs.prix FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id AND chambres.id =' . $numeroChambre );
    $query->execute(); // execute le SQL dans la base de données (MySQL / MariaDB)
    return $query->fetchAll( PDO::FETCH_ASSOC );
}

//Fonction pour obtenir la capacité d'une chambre avec son id
function getCapacity($dbh, $numeroChambre)
{
    $query = $dbh->prepare( 'SELECT capacite FROM chambres WHERE chambres.id =' . $numeroChambre );
    $query->execute();
    return $query->fetchAll( PDO::FETCH_ASSOC );
}

//Fonction pour vérifier que la date de réservation est libre
function checkReservationsEmpty($dbh, $date, $id)
{
    $query = $dbh->prepare( "SELECT * FROM planning WHERE chambre_id = '$id' AND jour = '$date'" );
    $query->execute(); // execute le SQL dans la base de données (MySQL / MariaDB)
    return $query->fetchAll( PDO::FETCH_ASSOC );
}

//Fonction pour réserver une chambre
function addReservation($dbh, $chambreId, $dateStart, $dateEnd, $numberAdult, $numberChild, $id)
{
    $query = $dbh->prepare( "INSERT INTO planning (chambre_id, jour, acompte, paye, nombreadulte, nombreenfant, client_id)
                                                VALUES('$chambreId', '$dateStart', '0', '0', '$numberAdult', '$numberChild', '$id')" );
    $query->execute();
    return $query->fetchAll();
}

//Fonction pour obtenir les informations d'un client avec son id
function getClient($dbh, $id)
{
    $query = $dbh->prepare( "SELECT * FROM clients WHERE id = ?" );
    $query->execute( array($id) );
    $client = $query->fetch();
    return $client;
}

//Fonction pour obtenir les informations d'un pays avec son id
function getCountrybyid($dbh, $cid)
{
    $query = $dbh->prepare( "SELECT * FROM pays WHERE id = ?" );
    $query->execute( array($cid) );
    $cname = $query->fetch();
    return $cname;
}

//Fonction pour mettre le premier caractère en majuscule et tous les autres en minuscule
function mbUcfirst($str, $encode = 'UTF-8')
{
    $start = mb_strtoupper( mb_substr( $str, 0, 1, $encode ), $encode );
    $end = mb_strtolower( mb_substr( $str, 1, mb_strlen( $str, $encode ), $encode ), $encode );
    return $str = $start . $end;
}

//Fonction pour vérifier le mail d'un utilisateur
function mailCheck($dbh, $mail)
{
    $request = $dbh->prepare( 'SELECT * FROM clients WHERE mail = ?' );
    $request->execute( array($mail) );
    return $emailCount = $request->rowCount();
}

//Fonction pour effectuer une inscription
function inscription($dbh, $firstName, $lastName, $mail, $password, $address, $postalCode, $city, $country, $civility, $image)
{
    $sql = $dbh->prepare( "INSERT INTO clients (civilite, nom, prenom, adresse, codePostal, ville, pays_id, mail, password, image) VALUES(?,?,?,?,?,?,?,?,?,?)" );
    $sql->execute( array($civility, $firstName, $lastName, $address, $postalCode, $city, $country, $mail, $password, $image) );
}

//Fonction pour obtenir les informations d'un utilisateur en fonction de son mail
function getUserByMail($dbh, $mail)
{
    $query = $dbh->prepare( 'SELECT * FROM clients WHERE mail = ?' );
    $query->execute( array($mail) );
    return $user = $query->fetch();
}

//Fonction pour rechercher dans la base de données si l'adresse mail n'existe pas déjà
function getUserByMailForVerif($dbh, $mail){
    $query = $dbh->prepare('SELECT * FROM clients WHERE mail = ?');
    $query->execute(array($mail));
    return $user = $query->rowCount();
}

//Fonction pour obtenir les informations d'un utilisateur en fonction de son mail et de son mot de passe
function getUserByMailAndPassword($dbh, $mail, $password)
{
    $query = $dbh->prepare( 'SELECT * FROM clients WHERE mail = ? and password = ?' );
    $query->execute( array($mail, $password) );
    return $query;
}

//Fonction pour obtenir les réservations d'un utilisateur en fonction de son id
function getReservations($dbh, $uid)
{
    $query = $dbh->prepare( 'SELECT * FROM planning WHERE client_id = ?' );
    $query->execute( array($uid) );
    $rlist = $query->fetchAll();
    return $rlist;
}

//Fonction pour obtenir le nombre de réservations d'un utilisateur en fonction de son id
function countReservations($dbh, $uid)
{
    $query = $dbh->prepare( 'SELECT COUNT(*) FROM planning WHERE client_id = ?' );
    $query->execute( array($uid) );
    return $count = $query->fetch();
}

//Fonction pour obtenir la grille des clients
function getAllClients($dbh)
{
    $query = $dbh->prepare( 'SELECT * FROM clients' );
    $query->execute();
    return $allclients = $query->fetchAll();
}

//Fonction pour obtenir la grille des chambres
function getAllRoom($dbh){
    $query = $dbh->prepare( 'SELECT chambres.*,tarifs.prix FROM chambres,tarifs WHERE chambres.tarif_id = tarifs.id ORDER BY id ASC ' );
    $query->execute();
    return $query ->fetchAll( PDO::FETCH_ASSOC );
}

//Fonction pour obtenir la valeur d'un prix en fonction de son id
function getPricebyid($dbh, $tid)
{
    $query = $dbh->prepare( 'SELECT prix FROM tarifs WHERE id = ?' );
    $query->execute( array($tid) );
    return $tarif = $query->fetch();
}

//Fonction pour obtenir toute la grille de prix
function getPrices($dbh)
{
    $query = $dbh->prepare( 'SELECT * FROM tarifs' );
    $query->execute();
    return $tarifs = $query->fetchAll();
}

//Modification du compte utilisateur
function updateFirstName($dbh, $firstName, $id){
    $insertFname = $dbh->prepare('UPDATE clients SET prenom = ? WHERE id = ?');
    $insertFname ->execute(array($firstName, $id));
}
function updateLastName($dbh, $lastName, $id){
    $insertLname = $dbh->prepare('UPDATE clients SET nom = ? WHERE id = ?');
    $insertLname ->execute(array($lastName, $id));
}
function updatePassword($dbh, $password, $id){
    $insertPassword = $dbh->prepare('UPDATE clients SET password = ? WHERE id = ?');
    $insertPassword ->execute(array($password, $id));
}
function updateMail($dbh, $mailModif, $id){
    $insertMail = $dbh->prepare('UPDATE clients SET mail = ? WHERE id = ?');
    $insertMail ->execute(array($mailModif, $id));
}
function updateAddress($dbh,$addressModif, $id){
    $insertAddress = $dbh->prepare('UPDATE clients SET adresse = ? WHERE id = ?');
    $insertAddress ->execute(array($addressModif, $id));
}
function updatePostalCode($dbh, $pcModif, $id){
    $insertPC = $dbh->prepare('UPDATE clients SET codePostal = ? WHERE id = ?');
    $insertPC ->execute(array($pcModif, $id));
}
function updateCity($dbh, $city, $id){
    $insertTown = $dbh->prepare('UPDATE clients SET ville = ? WHERE id = ?');
    $insertTown ->execute(array($city, $id));
}
function updateCountry($dbh, $countryModif, $id){
    $insertCountry = $dbh->prepare('UPDATE clients SET pays_id = ? WHERE id = ?');
    $insertCountry ->execute(array($countryModif, $id));
}
function updateCivility($dbh, $civilityModif, $id){
    $insertCountry = $dbh->prepare('UPDATE clients SET civilite = ? WHERE id = ?');
    $insertCountry ->execute(array($civilityModif, $id));
}
function updateUserPP($dbh, $image, $id){
    $insertPP = $dbh->prepare('UPDATE clients SET image = ? WHERE id = ?');
    $insertPP ->execute(array($image, $id));
}
function getLastUsers($dbh){
    $query = $dbh->prepare('SELECT * FROM clients ORDER BY id DESC LIMIT 10');
    $query -> execute();
    return $clients = $query->fetchAll();
}

//Fonction de recherche multi-tag avec une date de début et une date de fin (+ d'1 jour)
function tagSearch($dbh, $total, $exposition, $idprix, $datedebut, $datefin)
{
    //On écrit la requête SQL qu'on réutilise avec n'importe quel "tag"
    $type = "SELECT * FROM chambres c LEFT JOIN planning p ON c.id = p.chambre_id WHERE p.jour NOT BETWEEN CONVERT(?, DATETIME) AND CONVERT(?, DATETIME) AND c.capacite >= ?";

    //Si ni prix, ni exposition demandés
    if ($idprix == 0) {
        if ($exposition == 0) {
            $query = $dbh->prepare( "$type" );
            $query->execute( array($datedebut, $datefin, $total) );
            return $final = $query->fetchAll();

            //Si exposition demandée mais pas le prix
        } else {
            $query = $dbh->prepare( "$type" . " AND c.exposition = ?" );
            $query->execute( array($datedebut, $datefin, $total, $exposition) );
            return $final = $query->fetchAll();
        }

        //Si prix demandé mais pas l'exposition
    } else {
        if ($exposition == 0) {
            $query = $dbh->prepare( "$type" . " AND c.tarif_id = ?" );
            $query->execute( array($datedebut, $datefin, $total, $idprix) );
            return $final = $query->fetchAll();

            //Si prix et expositions sont demandés
        } else {
            $query = $dbh->prepare( "$type" . " AND c.exposition = ? AND c.tarif_id = ?" );
            $query->execute( array($datedebut, $datefin, $total, $exposition, $idprix) );
            return $final = $query->fetchAll();
        }
    }
}

//Fonctiond e recherche multi-tag avec seulement une date de début (1 jour)
function tagSearchOne($dbh, $total, $exposition, $idprix, $datedebut)
{
    $type = "SELECT * FROM chambres c LEFT JOIN planning p ON c.id = p.chambre_id WHERE p.jour != CONVERT(?, DATETIME) AND c.capacite >= ?";

    if ($idprix == 0) {
        if ($exposition == 0) {
            $query = $dbh->prepare( "$type" );
            $query->execute( array($datedebut, $total) );
            return $final = $query->fetchAll();
        } else {
            $query = $dbh->prepare( "$type" . " AND c.exposition = ?" );
            $query->execute( array($datedebut, $total, $exposition) );
            return $final = $query->fetchAll();
        }
    } else {
        if ($exposition == 0) {
            $query = $dbh->prepare( "$type" . " AND c.tarif_id = ?" );
            $query->execute( array($datedebut, $total, $idprix) );
            return $final = $query->fetchAll();
        } else {
            $query = $dbh->prepare( "$type" . " AND c.exposition = ? AND c.tarif_id = ?" );
            $query->execute( array($datedebut, $total, $exposition, $idprix) );
            return $final = $query->fetchAll();
        }
    }
}