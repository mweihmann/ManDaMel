<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../config/db.php';

class ProductLogic {
    public function getAll(): array {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM products WHERE active = 1 ORDER BY created_at DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => new Product($row), $data);
    }

    public function update(array $data): bool {
        global $pdo;

        $stmt = $pdo->prepare("
            UPDATE products
            SET name = :name, description = :description, price = :price, rating = :rating, category_id = :category_id
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'rating' => $data['rating'],
            'category_id' => $data['category_id']
        ]);
    }

    public function deactivate(int $id): bool {
        global $pdo;
        return $pdo->prepare("UPDATE products SET active = 0 WHERE id = ?")->execute([$id]);
    }

    public function updateWithFiles(array $data): bool {
        global $pdo;
    
        $fields = [
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'rating' => $data['rating'],
            'category_id' => $data['category_id']
        ];

        if (isset($data['active'])) {
            $fields['active'] = (int)$data['active'];
        }        
    
        if (!empty($data['file_path'])) {
            $fields['file_path'] = $data['file_path'];
        }
    
        if (!empty($data['image'])) {
            $fields['image'] = $data['image'];
        }
    
        $setString = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
    
        $fields['id'] = $data['id'];
        $stmt = $pdo->prepare("UPDATE products SET $setString WHERE id = :id");
        return $stmt->execute($fields);
    }

    public function create(array $data): bool {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO products (name, description, rating, price, category_id, image, file_path, active, created_at)
            VALUES (:name, :description, :rating, :price, :category_id, :image, :file_path, 1, NOW())
        ");
    
        return $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'],
            'rating' => $data['rating'],
            'price' => $data['price'],
            'category_id' => $data['category_id'],
            'image' => $data['image'],
            'file_path' => $data['file_path']
        ]);
    }
    
    public function findById(int $id): ?array {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    
}