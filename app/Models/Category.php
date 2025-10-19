<?php
namespace App\Models;

use Core\Model;
use PDO;

class Category extends Model
{
    protected string $table = 'categories';

    public function all()
    {
        $stmt = $this->db->query("SELECT id, name, slug, created_at FROM {$this->table} ORDER BY name");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT id, name, slug, created_at FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findBySlug(string $slug)
    {
        $stmt = $this->db->prepare("SELECT id, name, slug, created_at FROM {$this->table} WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function create(string $name, string $slug)
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, slug) VALUES (:name, :slug)");
        $stmt->execute(['name' => $name, 'slug' => $slug]);

        return (int)$this->db->lastInsertId();
    }

    public function withCounts()
    {
        $sql = "
            SELECT c.id, c.name, c.slug, c.created_at, COUNT(p.id) AS products_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.name
        ";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
