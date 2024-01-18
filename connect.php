
<?php
include "config.inc.php";
error_reporting(E_ALL);
try
{
	$bdd = new PDO("mysql:host=$server; charset=UTF8; dbname=$database", $user, $passwd);
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}
?>