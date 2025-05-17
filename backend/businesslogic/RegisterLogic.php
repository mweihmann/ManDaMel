<?php
require_once __DIR__ . '/../config/db.php';

class RegisterLogic
{
    /**
     * Registriert einen neuen Benutzer in der Datenbank
     */
    public static function registerUser(array $user): ?array
    {
        global $pdo;

        $pdo->beginTransaction();

        try {
            // Benutzer eintragen
            $stmt = $pdo->prepare("INSERT INTO users (
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
            )");

            $stmt->execute([
                'username' => $user['username'],
                'pronouns' => $user['pronouns'],
                'given_name' => $user['given_name'],
                'surname' => $user['surname'],
                'email' => $user['email'],
                'telephone' => $user['telephone'],
                'country' => $user['country'],
                'city' => $user['city'],
                'postal_code' => $user['postal_code'],
                'street' => $user['street'],
                'house_number' => $user['house_number'],
                'password_hash' => $user['password_hash']
            ]);

            $userId = $pdo->lastInsertId();

            // Falls Zahlungsdaten angegeben, IBAN oder Kreditkarte speichern
            if (!empty($user['iban']) || !empty($user['creditcard_number'])) {
                $paymentStmt = $pdo->prepare("INSERT INTO payment_info (
                    user_id, method, iban, creditcard_number, creditcard_expiry, creditcard_cvv, holder_name
                ) VALUES (
                    :user_id, :method, :iban, :creditcard_number, :creditcard_expiry, :creditcard_cvv, :holder_name
                )");

                $paymentStmt->execute([
                    'user_id' => $userId,
                    'method' => $user['iban'] ? 'iban' : 'creditcard',
                    'iban' => $user['iban'] ?? null,
                    'creditcard_number' => $user['creditcard_number'] ?? null,
                    'creditcard_expiry' => $user['creditcard_expiry'] ?? null,
                    'creditcard_cvv' => $user['creditcard_cvv'] ?? null,
                    'holder_name' => $user['holder_name'] ?? ($user['given_name'] . ' ' . $user['surname'])
                ]);
            }

            $pdo->commit();
            return [
                'id' => (int)$userId,
                'email' => $user['email'],
                'username' => $user['username']
            ];
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log('DB Error: ' . $e->getMessage());
            return null;
        }        
    }
}