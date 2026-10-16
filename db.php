<?php
require 'vendor/autoload.php';
class Database
{
    function getConnection()
    {
        try {
            $client = new MongoDB\Client("mongodb://localhost:27017");
            return $client->baseFormation1->devs;
        } catch (Exception $e) {
            return "Erreur lors de la connection " . $e->getMessage();
        }
    }
}
