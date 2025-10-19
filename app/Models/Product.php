<?php

namespace App\Models;

use Core\Model;
use PDO;

class Product extends Model
{
    protected string $table = 'products';

    public function fetch(array $opts = [])
    {
        $params = [];
        $where = [];
        $order = "p.name ASC";

        if (!empty($opts['category_id'])) {
            $where[] = "p.category_id = :cid";
            $params[':cid'] = (int)$opts['category_id'];
        }

        if (!empty($opts['sort'])) {
            switch ($opts['sort']) {
                case 'price_asc':
                    $order = "p.price ASC";
                    break;
                case 'alpha':
                    $order = "p.name ASC";
                    break;
                case 'new':
                    $order = "p.created_at DESC";
                    break;
                default:
                    $order = "p.name ASC";
            }
        }

        $sql = "SELECT p.id, p.category_id, p.name, p.price, p.description, p.created_at 
                FROM {$this->table} p";

        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY {$order}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
