<?php

namespace App\Model;

use PDO;

class ClientRepository extends AbstractRepository
{

    public static function clientFromRow($row)
    {
        $client = new Client();
        $client
            ->setId($row['id'])
            ->setUserId($row['userId'])
            ->setClientName($row['clientName']);
        return $client;
    }

    public function save($client)
    {
        if ($client->getId()) {
            $sql = "UPDATE Client SET userId = :userId, clientName = :clientName WHERE id = :id";
            $params = [
                'id' => $client->getId(),
                'userId' => $client->getUserId(),
                'clientName' => $client->getClientName()
            ];
        } else {
            $sql = "INSERT INTO Client(userId, clientName) VALUES (:userId, :clientName)";
            $params = [
                'userId' => $client->getUserId(),
                'clientName' => $client->getClientName()
            ];
        }
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        if (!$client->getId()) {
            $client->setId($this->connection->lastInsertId());
        }
        $this->closeDatabaseConnection();
        return $client;
    }

    public function findById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Client WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $client = $this->clientFromRow($row);

        $this->closeDatabaseConnection();
        return $client;
    }

    public function findByUserId($userId)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Client WHERE userId = :userId ORDER BY clientName";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('userId' => $userId));
        $clients = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $clients[] = $this->clientFromRow($row);
        }

        $this->closeDatabaseConnection();
        return $clients;
    }

    public function filterForUser($userId, $name) {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Client WHERE userId = :userId AND LOWER(clientName) LIKE :name ORDER BY clientName";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array(
            'userId' => $userId,
            'name' => '%' . strtolower($name) . '%'
        ));
        $clients = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $clients[] = $this->clientFromRow($row);
        }
        $this->closeDatabaseConnection();
        return $clients;
    }

    function deleteById($id)
    {
        $this->openDatabaseConnection();
        $sql = "DELETE FROM Client WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);
        $this->closeDatabaseConnection();
    }
}