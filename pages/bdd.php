<?php
/* Connexion � une base MySQL avec l'invocation de pilote */
$dsn = 'mysql:host=localhost;dbname=stage; charset=utf8';
$user = 'root';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}
?>