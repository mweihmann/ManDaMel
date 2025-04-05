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
}
