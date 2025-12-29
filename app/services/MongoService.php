<?php

use MongoDB\Client;

class MongoService
{
    private $collection;

    public function __construct()
    {
        $client = new Client("mongodb://127.0.0.1:27017");
        $db = $client->wai;
        $this->collection = $db->images;
    }

    public function saveImage(array $data): void
    {
        $this->collection->insertOne($data);
    }
}