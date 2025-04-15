<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db.php';

class UserLogic {
    
    public function getAllUsers(): array {
        global $pdo;
        $stmt = $pdo->query("SELECT user_id, username, given_name, surname, email, role, user_state, created_at FROM users");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new User(
            $row['user_id'],
            $row['username'],
            $row['given_name'],
            $row['surname'],
            $row['email'],
            $row['role'],
            $row['user_state'],
            $row['created_at']
        ), $results);
    }

    public function userExists(string $username, string $email): bool {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        return $stmt->fetch() !== false;
    }

    public function register(array $data): ?array {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users
            (username, pronouns, given_name, surname, email, telephone, country, city, postal_code, street, house_number, role, user_state, password_hash)
            VALUES
            (:username, :pronouns, :given_name, :surname, :email, :telephone, :country, :city, :postal_code, :street, :house_number, 'user', 'active', :password_hash)");

        if ($stmt->execute($data)) {
            $id = $pdo->lastInsertId();
            return ['id' => $id, 'email' => $data['email'], 'username' => $data['username']];
        }

        return null;
    }

    public function findByLogin(string $login): ?array {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :login OR email = :login LIMIT 1");
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}