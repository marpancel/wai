<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class MongoService
{
    private MongoDB\Collection $images;
    private MongoDB\Collection $users;

    public function __construct()
    {
        $client = new MongoDB\Client("mongodb://127.0.0.1:27017");
        $db = $client->wai;

        $this->images = $db->images;
        $this->users  = $db->users;
    }


    public function getUserById(string $id)
    {
        return $this->users->findOne([
            '_id' => new MongoDB\BSON\ObjectId($id)
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


    public function saveImage(array $data): void
    {
        $this->images->insertOne($data);
    }

    public function countImages(): int
    {
        return $this->images->countDocuments(['public' => true]);
    }

    public function getImages(int $limit, int $skip): array
    {
        return $this->images->aggregate([
            ['$match' => ['public' => true]],
            ['$sort'  => ['uploaded_at' => -1]],
            ['$skip'  => $skip],
            ['$limit' => $limit],
            [
                '$lookup' => [
                    'from'         => 'users',
                    'localField'   => 'user_id',
                    'foreignField'=> '_id',
                    'as'           => 'author'
                ]
            ],
            ['$unwind' => '$author']
        ])->toArray();
    }
}