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
            SELECT 
                id,
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
                created_at
            FROM users
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $results; // KEINE Mapping auf User-Klasse nötig, du nutzt assoziative Arrays
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
     * Find user by ID (for admin purposes)
     */
    public function findUserById(int $id): ?array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Update user data (for user account update)
     */
    public function updateAccount(int $userId, array $data, bool $isAdmin = false): bool
    {
        global $pdo;

        $fields = [
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'street' => $data['street'],
            'house_number' => $data['house_number'],
            'given_name' => $data['given_name'],
            'surname' => $data['surname'],
            'pronouns' => $data['pronouns'],
            'username' => $data['username'],
            // 'password_hash' => $data['password_hash'] ?? null,
            // 'user_state' => $data['user_state'] ?? null,
            // 'role' => $data['role'] ?? null,           
        ];
    
        if ($isAdmin) {
            $fields['role'] = $data['role'];
            $fields['user_state'] = $data['user_state'];
        }
    
        $setString = implode(', ', array_map(fn($key) => "$key = :$key", array_keys($fields)));
    
        $stmt = $pdo->prepare("
            UPDATE users SET $setString
            WHERE id = :id
        ");
    
        $fields['id'] = $userId;
        // file_put_contents('debug_update.log', print_r($fields, true));
        return $stmt->execute($fields);
    }


}

