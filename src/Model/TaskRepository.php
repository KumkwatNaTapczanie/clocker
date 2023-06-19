<?php

namespace App\Model;

use Exception;
use PDO;

class TaskRepository extends AbstractRepository
{

    public static function taskFromRow($row)
    {
        $task = new Task();
        $task
            ->setId($row['id'])
            ->setUserId($row['userId'])
            ->setProjectId($row['projectId'])
            ->setTitle($row['title'])
            ->setStartTime($row['startTime'])
            ->setStopTime($row['stopTime'])
            ->setProgress($row['progress']);
        return $task;
    }

    public function save($task)
    {
        if ($task->getId()) {
            $sql = "UPDATE Task SET userId = :userId, projectId = :projectId, title = :title, startTime = :startTime, stopTime = :stopTime, progress = :progress WHERE id = :id";
            $params = [
                'id' => $task->getId(),
                'userId' => $task->getUserId(),
                'projectId' => $task->getProjectId(),
                'title' => $task->getTitle(),
                'startTime' => $task->getStartTime(),
                'stopTime' => $task->getStopTime(),
                'progress' => $task->getProgress()
            ];
        } else {
            $sql = "INSERT INTO Task(userId, projectId, title, startTime, stopTime, progress) VALUES (:userId, :projectId, :title, :startTime, :stopTime, :progress)";
            $params = [
                'userId' => $task->getUserId(),
                'projectId' => $task->getProjectId(),
                'title' => $task->getTitle(),
                'startTime' => $task->getStartTime(),
                'stopTime' => $task->getStopTime(),
                'progress' => $task->getProgress()
            ];
        }
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        if (!$task->getId()) {
            $task->setId($this->connection->lastInsertId());
        }
        $this->closeDatabaseConnection();
        return $task;
    }

    public function findById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Task WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $task = $this->taskFromRow($row);

        $this->closeDatabaseConnection();
        return $task;
    }

    public function findByUserId($userId)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Task WHERE userId = :userId ORDER BY startTime DESC";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('userId' => $userId));
        $tasks = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->taskFromRow($row);
        }

        $this->closeDatabaseConnection();
        return $tasks;
    }

    public function getProjectByTaskId($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT Project.id, Project.userId, Project.clientId, Project.projectName, Project.wage FROM Task JOIN Project ON Task.projectId = Project.id WHERE Task.id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->closeDatabaseConnection();
        return ProjectRepository::projectFromRow($row);
    }

    public function getClientByTaskId($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT Client.id, Client.userId, Client.clientName FROM Task JOIN Project ON Task.projectId = Project.id JOIN Client ON Project.clientId = Client.id WHERE Task.id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->closeDatabaseConnection();
        return ClientRepository::clientFromRow($row);
    }

    public function getWageById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT wage FROM Task JOIN Project ON Task.projectId = Project.id WHERE Task.id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->closeDatabaseConnection();
        return $row['wage'];
    }

    public function getNumberOfTasks()
    {
        $this->openDatabaseConnection();
        $sql = "SELECT COUNT(*) AS number FROM Task";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $this->closeDatabaseConnection();
        return $row['number'];
    }

    public function getTotalTasksTimeThisPeriod($period = '')
    {
        $this->openDatabaseConnection();
        switch ($period) {
            case 'week':
                $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) AS time FROM Task WHERE stopTime IS NOT NULL AND WEEK(stopTime) = WEEK(CURDATE())";
                break;
            case 'month':
                $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) AS time FROM Task WHERE stopTime IS NOT NULL AND MONTH(stopTime) = MONTH(CURDATE())";
                break;
            case 'year':
                $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) AS time FROM Task WHERE stopTime IS NOT NULL AND YEAR(stopTime) = YEAR(CURDATE())";
                break;
            default:
                $sql = "SELECT SUM(TIMESTAMPDIFF(SECOND, startTime, stopTime)) AS time FROM Task WHERE stopTime IS NOT NULL";
                break;
        }
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $this->closeDatabaseConnection();
        return $row['time'];
    }

    public function filterForUser($userId, $name) {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM Task WHERE userId = :userId AND LOWER(title) LIKE :name ORDER BY startTime DESC";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array(
            'userId' => $userId,
            'name' => '%' . strtolower($name) . '%'
        ));
        $tasks = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $tasks[] = $this->taskFromRow($row);
        }
        $this->closeDatabaseConnection();
        return $tasks;
    }

    function deleteById($id)
    {
        $this->openDatabaseConnection();
        $sql = "DELETE FROM Task WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);
        $this->closeDatabaseConnection();
    }

    /**
     * @param $queryBuilder
     * @return array
     * @throws Exception
     */
    public function executeQueryFromBuilder($queryBuilder)
    {
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($queryBuilder->getStatement());
        $statement->execute($queryBuilder->getParams());
        $rows = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $row;
        }
        $this->closeDatabaseConnection();
        return $rows;
    }
}