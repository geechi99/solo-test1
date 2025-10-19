<?php
namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class CategoryController
{
    protected Category $categoryModel;
    protected Product $productModel;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->productModel = new Product();
    }

    public function index(): void
    {
        $categories = $this->categoryModel->withCounts();
        require __DIR__ . '/../../app/Views/categories/index_ajax.php';
    }

    public function show(string $slug)
    {
        $cat = $this->categoryModel->findBySlug($slug);
        if (!$cat) {
            http_response_code(404);
            echo "Category not found";
            return;
        }
        $products = $this->productModel->allByCategory((int)$cat['id']);

        require __DIR__ . '/../../app/Views/categories/index.php';
    }

    public function apiProducts()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!empty($_GET['product_id'])) {
            $pid = (int)$_GET['product_id'];
            $product = $this->productModel->find($pid);

            echo json_encode($product ? [$product] : []);
            exit;
        }

        $opts = [];
        if (isset($_GET['category_id']) && $_GET['category_id'] !== '') $opts['category_id'] = (int)$_GET['category_id'];
        if (!empty($_GET['sort'])) $opts['sort'] = $_GET['sort'];

        $products = $this->productModel->fetch($opts);

        echo json_encode($products);
        exit;
    }
}
