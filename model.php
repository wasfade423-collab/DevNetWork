<?php
require_once 'db.php';
class Model
{

    private $db;
    private $connection;

    public function __construct()
    {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    private function testConnection()
    {
        if (str_contains($this->connection, "Erreur lors de la connection")) {
            return false;
        }
        return true;
    }
    function getAlldevs()
    {
        if (!$this->testConnection()) {
            echo $this->connection;
            return;
        }

        $devs = $this->connection->find();
        $aptDevs = [];
        if (
            $devs == null
        ) {
            return $aptDevs = "Aucun dev trouvé pour le moment.";
        } else {

            $devs = $devs->toArray();

            foreach ($devs as $dev) {
                $aptDev = $dev->getArrayCopy();
                $aptDev["_id"] = (string) $aptDev["_id"];
                $aptDev["technos"] = $aptDev["technos"]->getArrayCopy();

                $aptDevs[] = $aptDev;
            }

            return $aptDevs;
        }
    }

    function getDevById($id)
    {
        if (!$this->testConnection()) {
            echo $this->connection;
            return;
        }

        $dev = $this->connection->findOne(["_id" => new MongoDB\BSON\ObjectId($id)]);
        if ($dev == null) {
            return $dev = "Dev non trouvé avec l'id: " . $id;
        } else {
            $dev = $dev->getArrayCopy();
            $dev["_id"] = (string) $dev["_id"];
            $dev["technos"] = $dev["technos"]->getArrayCopy();

            return $dev;
        }
    }

    function addDev($dev)
    {
        if (!$this->testConnection()) {
            echo $this->connection;
            return;
        }

        if ($this->connection->insertOne($dev)) {
            return true;
        } else {
            return false;
        }
    }

    function deleteDev($devId)
    {
        if (!$this->testConnection()) {
            echo $this->connection;
            return;
        }
        $request = $this->connection->deleteOne(["_id" => new MongoDB\BSON\ObjectId($devId)]);
        if ($request) {
            return true;
        } else {
            return false;
        }
    }

    function updateDev($devId, $updateDev)
    {
        if (!$this->testConnection()) {
            echo $this->connection;
            return;
        }

        $request = $this->connection->updateOne(['_id' => new MongoDB\BSON\ObjectId($devId)], $updateDev);

        if ($request) {
            return true;
        } else {
            return false;
        }
    }
}
// $control = new Model();



// $dev = [
//     "nom" => 'Wasfade',
//     "prenom" => 'Tonoukoin',
//     "type" => 'Fullstack',
//     "xp" => 2,
//     "level" => 'Junior',
//     "technos" => [
//         "html",
//         "css",
//         "JavaScript",
//         "Angular",
//         "Tailwindcss",
//         "Php",
//         "Laravel",
//         "MySQL",
//         "MongoDB",
//         "PostgreeSQL"
//     ]
// ];
// echo $control->updateDev('6ac6a8068390659c61b94599', $dev);