<?php

require_once __DIR__ . '/vendor/autoload.php';

class Database
{
    public function getConnection(): MongoDB\Collection
    {
        try {
            $client = new MongoDB\Client('mongodb://localhost:27017');

            return $client->selectCollection('baseFormation1', 'devs');
        } catch (Throwable $exception) {
            error_log($exception->__toString());
            throw new RuntimeException('Impossible de se connecter à MongoDB.', 0, $exception);
        }
    }
}
