<?php

namespace App\Model;

use PDO;

class ProjectRepository extends AbstractRepository
{
    public static function projectFromRow($row)
    {
        $project = new Project();
        $project
            ->setId($row['id'])
            ->setUserId($row['userId'])
            ->setClientId($row['clientId'])
            ->setProjectName($row['projectName'])
            ->setWage($row['wage']);
        return $project;
    }

    public function save($project)
    {
        if ($project->getId()) {
            $sql = "UPDATE Project SET userId = :userId, clientId = :clientId, projectName = :projectName, wage = :wage WHERE id = :id";
            $params = [
                'id' => $project->getId(),
                'userId' => $project->getUserId(),
                'clientId' => $project->getClientId(),
                'projectName' => $project->getProjectName(),
                'wage' => $project->getWage()
            ];
        } else {
            $sql = "INSERT INTO Project(userId, clientId, projectName, wage) VALUES (:userId, :clientId, :projectName, :wage)";
            $params = [
                'userId' => $project->getUserId(),
                'clientId' => $project->getClientId(),
                'projectName' => $project->getProjectName(),
                'wage' => $project->getWage()
            ];
        }
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        if (!$project->getId()) {
            $project->setId($this->connection->lastInsertId());
        }
        $this->closeDatabaseConnection();
        return $project;
    }

    public function findById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Project WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $project = $this->projectFromRow($row);

        $this->closeDatabaseConnection();
        return $project;
    }

    public function findByUserId($userId)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Project WHERE userId = :userId ORDER BY projectName";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('userId' => $userId));
        $projects = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->projectFromRow($row);
        }

        $this->closeDatabaseConnection();
        return $projects;
    }

    public function findByClientId($clientId)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Project WHERE clientId = :clientId";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('clientId' => $clientId));
        $projects = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->projectFromRow($row);
        }

        $this->closeDatabaseConnection();
        return $projects;
    }

    public function getClientByProjectId($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT Client.id, Client.userId, Client.clientName FROM Project JOIN Client ON Project.clientId = Client.id WHERE Project.id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->closeDatabaseConnection();
        return ClientRepository::clientFromRow($row);
    }

    public function filterForUser($userId, $name) {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Project WHERE userId = :userId AND LOWER(projectName) LIKE :name ORDER BY projectName";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array(
            'userId' => $userId,
            'name' => '%' . strtolower($name) . '%'
        ));
        $projects = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $projects[] = $this->projectFromRow($row);
        }
        $this->closeDatabaseConnection();
        return $projects;
    }

    function deleteById($id)
    {
        $this->openDatabaseConnection();
        $sql = "DELETE FROM Project WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);
        $this->closeDatabaseConnection();
    }
}