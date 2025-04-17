<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/db.php';

class UserLogic
{
    /**
     * Get all users (for admin purposes)
     */
    public function getAllUsers(): array
    {
        global $pdo;
        $stmt = $pdo->query("
            SELECT id, username, given_name, surname, email, role, user_state, created_at
            FROM users
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new User(
            $row['id'],
            $row['username'],
            $row['given_name'],
            $row['surname'],
            $row['email'],
            $row['role'],
            $row['user_state'],
            $row['created_at']
        ), $results);
    }

    /**
     * Check if a user with the given username or email already exists
     */
    public function userExists(string $username, string $email): bool
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT id FROM users
            WHERE username = :username OR email = :email
        ");
        $stmt->execute([
            'username' => $username,
            'email' => $email
        ]);
        return $stmt->fetch() !== false;
    }

    /**
     * Create new user from data (used for registration)
     */
    public function register(array $data): ?array
    {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO users (
                username,
                pronouns,
                given_name,
                surname,
                email,
                telephone,
                country,
                city,
                postal_code,
                street,
                house_number,
                role,
                user_state,
                password_hash
            ) VALUES (
                :username,
                :pronouns,
                :given_name,
                :surname,
                :email,
                :telephone,
                :country,
                :city,
                :postal_code,
                :street,
                :house_number,
                'user',
                'active',
                :password_hash
            )
        ");

        if ($stmt->execute($data)) {
            $id = $pdo->lastInsertId();
            return [
                'id' => (int) $id,
                'email' => $data['email'],
                'username' => $data['username']
            ];
        }

        return null;
    }

    /**
     * Find user by login (either username or email)
     */
    public function findByLogin(string $login): ?array {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM users
            WHERE username = :login1 OR email = :login2
            LIMIT 1
        ");
        $stmt->execute([
            'login1' => $login,
            'login2' => $login
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    } 

    /**
     * Update user data (for admin purposes)
     */
    public function updateUser(array $data): bool
    {
        global $pdo;
    
        if (!isset($data['id'])) {
            throw new InvalidArgumentException('User ID is required to update user.');
        }
    
        $stmt = $pdo->prepare("
            UPDATE users SET
                username = :username,
                given_name = :given_name,
                surname = :surname,
                email = :email,
                role = :role,
                user_state = :user_state
            WHERE id = :id
        ");
    
        $stmt->execute($data);
        return $stmt->rowCount() > 0;
    } 
}

