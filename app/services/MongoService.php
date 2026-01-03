<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

class MongoService
{
    private Collection $images;
    private Collection $users;

    public function __construct()
    {
        $client = new Client("mongodb://127.0.0.1:27017");
        $db = $client->wai;

        $this->images = $db->images;
        $this->users  = $db->users;
    }

    /* ================= USERS ================= */

    public function getUserById(string $id)
    {
        return $this->users->findOne([
            '_id' => new ObjectId($id)
        ]);
    }

    public function findUserByLogin(string $login)
    {
        return $this->users->findOne(['login' => $login]);
    }

    public function saveUser(array $data): void
    {
        $this->users->insertOne($data);
    }

    /* ================= IMAGES ================= */

    public function saveImage(array $data): void
    {
        $this->images->insertOne($data);
    }

    public function countImages(): int
    {
        return $this->images->countDocuments(['public' => true]);
    }

    /**
     * Jedno zdjęcie po ID (dla saved)
     */
    public function getImageById(string $id)
    {
        return $this->images->findOne([
            '_id' => new ObjectId($id)
        ]);
    }

    /**
     * Wiele zdjęć po ID (wydajnie – jedno zapytanie)
     */
    public function getImagesByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $objectIds = array_map(
            fn ($id) => new ObjectId($id),
            $ids
        );

        return $this->images
            ->find(['_id' => ['$in' => $objectIds]])
            ->toArray();
    }

    /**
     * Galeria bez autorów (opcjonalne)
     */
    public function getImages(int $limit, int $skip): array
    {
        return $this->images->aggregate([
            ['$match' => ['public' => true]],
            ['$sort'  => ['uploaded_at' => -1]],
            ['$skip'  => $skip],
            ['$limit' => $limit],
        ])->toArray();
    }

    /**
     * Galeria z autorami (używane w gallery.php)
     */
    public function getImagesWithAuthors(int $limit, int $skip): array
    {
        return $this->images->aggregate([
            ['$match' => ['public' => true]],
            ['$sort'  => ['uploaded_at' => -1]],
            ['$skip'  => $skip],
            ['$limit' => $limit],
            [
                '$lookup' => [
                    'from'          => 'users',
                    'localField'    => 'user_id',
                    'foreignField' => '_id',
                    'as'            => 'author'
                ]
            ],
            ['$unwind' => '$author'],
            [
                '$project' => [
                    'filename'             => 1,
                    'title'                => 1,
                    'author_login'         => '$author.login',
                    'author_profile_photo' => '$author.profile_photo',
                    'uploaded_at'          => 1
                ]
            ]
        ])->toArray();
    }
}
