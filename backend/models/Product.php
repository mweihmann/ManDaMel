<?php
class Product {
    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public int $rating;
    public string $file_path;
    public string $image;
    public int $category_id;
    public bool $active;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = (float)$data['price'];
        $this->rating = (int)$data['rating'];
        $this->file_path = $data['file_path'];
        $this->image = $data['image'];
        $this->category_id = $data['category_id'];
        $this->active = $data['active'] ?? true;
    }
}
