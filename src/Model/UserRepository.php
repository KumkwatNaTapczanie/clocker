<?php

namespace App\Model;

use Exception;
use PDO;

class UserRepository extends AbstractRepository
{
    /**
     * @param $row
     * @return User
     */
    public static function userFromRow($row)
    {
        $user = new User();
        $user
            ->setId($row['id'])
            ->setUsername($row['username'])
            ->setEmail($row['email'])
            ->setPassword($row['password'])
            ->setRole($row['role']);
        return $user;
    }

    public static function userFromRowExceptPassword($row)
    {
        $user = new User();
        $user
            ->setId($row['id'])
            ->setUsername($row['username'])
            ->setEmail($row['email'])
            ->setRole($row['role']);
        return $user;
    }

    /**
     * @param $id
     * @return null|User
     * @throws Exception
     */
    public function findById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM User WHERE id = :id";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $user = $this->userFromRow($row);

        $this->closeDatabaseConnection();
        return $user;
    }

    /**
     * @param $id
     * @return null|User
     * @throws Exception
     */
    public function findOneByEmail($email)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM User WHERE email = :email";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('email' => $email));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $user = $this->userFromRow($row);

        $this->closeDatabaseConnection();
        return $user;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function save($user)
    {
        if ($user->getId()) {
            $sql = "UPDATE User SET username = :username, email = :email, password = :password, role = :role WHERE id = :id";
            $params = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'role' => $user->getRole()
            ];
        } else {
            $sql = "INSERT INTO User(username, email, password, role) VALUES (:username, :email, :password, :role)";
            $params = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'role' => $user->getRole()
            ];
        }
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        if (!$user->getId()) {
            $user->setId($this->connection->lastInsertId());
        }
        $this->closeDatabaseConnection();
        return $user;
    }

    public function saveExceptPassword($user)
    {
        if ($user->getId()) {
            $sql = "UPDATE User SET username = :username, email = :email, role = :role WHERE id = :id";
            $params = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ];
        } else {
            $sql = "INSERT INTO User(username, email, role) VALUES (:username, :email, :role)";
            $params = [
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ];
        }
        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        if (!$user->getId()) {
            $user->setId($this->connection->lastInsertId());
        }
        $this->closeDatabaseConnection();
        return $user;
    }

    public function savePassword($user)
    {
        $sql = "UPDATE User SET password = :password WHERE id = :id";
        $params = [
            'id' => $user->getId(),
            'password' => $user->getPassword(),
        ];

        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        $this->closeDatabaseConnection();
        return $user;
    }

    /*public function saveUsername($user)
    {
        $sql = "UPDATE User SET username = :username WHERE id = :id";
        $params = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
        ];

        $this->openDatabaseConnection();
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        $this->closeDatabaseConnection();
        return $user;
    }*/

    /**
     * @param $str
     * @return array
     */
    public function searchByUsername($str)
    {
        $sql = "SELECT * FROM User WHERE LOWER(username) LIKE :str";
        return $this->findByStr($str, $sql);
    }

    /**
     * @param $str
     * @return array
     */
    public function searchByEmail($str)
    {
        $sql = "SELECT * FROM User WHERE LOWER(email) LIKE :str";
        return $this->findByStr($str, $sql);
    }

    /**
     * @param $str
     * @param $sql
     * @return array
     */
    private function findByStr($str, $sql)
    {
        $this->openDatabaseConnection();
        $str = '%' . strtolower($str) . '%';
        $statement = $this->connection->prepare($sql);

        $statement->execute(array('str' => $str));
        $users = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->userFromRow($row);
        }

        $this->closeDatabaseConnection();
        return $users;
    }

    public function getUsernameById($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT username FROM User WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(array('id' => $id));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $this->closeDatabaseConnection();
        return $row['username'];
    }

    public function getNumberOfUsers()
    {
        $this->openDatabaseConnection();
        $sql = "SELECT COUNT(*) AS number FROM User";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $this->closeDatabaseConnection();
        return $row['number'];
    }

    public function getAllUsersExceptId($id)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM User WHERE id != :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(array('id' => $id));
        $users = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->userFromRow($row);
        }
        $this->closeDatabaseConnection();
        return $users;
    }

    public function findByName($username)
    {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM User WHERE username = :username";
        $statement = $this->connection->prepare($sql);

        $statement->execute(['username' => $username]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }
        $user = $this->userFromRow($row);

        $this->closeDatabaseConnection();
        return $user;
    }

    public function filterForUser($userId, $name) {
        $this->openDatabaseConnection();
        $sql = "SELECT * FROM User WHERE id <> :userId AND LOWER(username) LIKE :name ORDER BY username";
        $statement = $this->connection->prepare($sql);

        $statement->execute(array(
            'userId' => $userId,
            'name' => '%' . strtolower($name) . '%'
        ));
        $users = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->userFromRow($row);
        }
        $this->closeDatabaseConnection();
        return $users;
    }

    function deleteById($id)
    {
        $this->openDatabaseConnection();
        $sql = "DELETE FROM User WHERE id = :id";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);
        $this->closeDatabaseConnection();
    }
}