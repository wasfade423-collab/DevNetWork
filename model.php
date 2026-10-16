<?php

require_once __DIR__ . '/db.php';

class Model
{
    private MongoDB\Collection $collection;

    public function __construct()
    {
        $this->collection = (new Database())->getConnection();
    }

    public function getAllDevs(): array
    {
        $documents = $this->collection->find();
        $devs = array_map(
            fn ($document): array => $this->normalizeDev($document->getArrayCopy()),
            $documents->toArray()
        );

        usort($devs, static fn (array $left, array $right): int => strnatcasecmp(
            trim(($left['prenom'] ?? '') . ' ' . ($left['nom'] ?? '')),
            trim(($right['prenom'] ?? '') . ' ' . ($right['nom'] ?? ''))
        ));

        return $devs;
    }

    public function getDevById(string $id): ?array
    {
        if (preg_match('/^[a-f\d]{24}$/i', $id) !== 1) {
            return null;
        }

        $document = $this->collection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);

        return $document === null ? null : $this->normalizeDev($document->getArrayCopy());
    }

    public function addDev(array $dev): void
    {
        $this->collection->insertOne($dev);
    }

    public function deleteDev(string $id): bool
    {
        if (preg_match('/^[a-f\d]{24}$/i', $id) !== 1) {
            return false;
        }

        return $this->collection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)])->getDeletedCount() > 0;
    }

    public function updateDev(string $id, array $updateDev): bool
    {
        if (preg_match('/^[a-f\d]{24}$/i', $id) !== 1) {
            return false;
        }

        $result = $this->collection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => $updateDev]
        );

        return $result->getMatchedCount() > 0;
    }

    private function normalizeDev(array $dev): array
    {
        $dev['_id'] = (string) $dev['_id'];

        foreach ([
            'prenom' => 'firstname',
            'nom' => 'lastname',
            'niveau' => 'level',
        ] as $field => $legacyField) {
            if (!array_key_exists($field, $dev) && array_key_exists($legacyField, $dev)) {
                $dev[$field] = $dev[$legacyField];
            }
        }
        if (!array_key_exists('anneeDexperience', $dev)) {
            $dev['anneeDexperience'] = $dev['anneeDexerience'] ?? $dev['xp'] ?? 0;
        }

        $technos = $dev['technos'] ?? [];
        if ($technos instanceof Traversable) {
            $technos = iterator_to_array($technos, false);
        }
        $dev['technos'] = is_array($technos) ? array_values($technos) : [];

        return $dev;
    }
}
