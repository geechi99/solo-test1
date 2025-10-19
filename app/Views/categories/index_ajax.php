<!doctype html>
    <html lang="ru">
        <head>
            <meta charset="utf-8">
            <title>Категории и товары</title>
            <meta name="viewport" content="width=device-width,initial-scale=1">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <link href="../css/style.css" rel="stylesheet">
            <script src="../js/main.js"></script>
        </head>
        <body>
            <div class="container-fluid">
                <div class="row">
                    <aside class="col-md-3 sidebar">
                        <h5 class="mb-3">Категории</h5>
                        <ul class="list-group" id="categoriesList" aria-label="Список категорий">
                            <?php
                            $cats = $this->categoryModel->withCounts();
                            foreach ($cats as $c): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center category-item" data-id="<?=intval($c['id'])?>">
                                    <a href="#" class="category-link text-decoration-none"><?=htmlspecialchars($c['name'])?></a>
                                    <span class="badge bg-secondary rounded-pill badge-count"><?=intval($c['products_count'])?></span>
                                </li>
                            <?php endforeach; ?>

                            <li class="list-group-item d-flex justify-content-between align-items-center category-item" data-id="">
                                <a href="#" class="category-link text-decoration-none">Все</a>
                                <span class="badge bg-secondary rounded-pill badge-count"><?=array_sum(array_column($cats,'products_count'))?></span>
                            </li>
                        </ul>
                    </aside>
                    <main class="col-md-9">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 id="pageTitle" class="m-0">Товары</h3>
                            <div class="d-flex align-items-center">
                                <label for="sortSelect" class="form-label me-2 mb-0">Сортировать:</label>
                                <select id="sortSelect" class="form-select d-inline-block" style="width:auto;">
                                    <option value="">По алфавиту</option>
                                    <option value="price_asc">Сначала дешёвые</option>
                                    <option value="new">Сначала новые</option>
                                </select>
                            </div>
                        </div>

                        <div id="productsContainer" class="product-grid" aria-live="polite">
                        </div>
                    </main>
                </div>
            </div>
            <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="buyModalLabel">Купить товар</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                        </div>
                        <div class="modal-body" id="buyModalBody">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary">Оформить заказ</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="buyModalLabel">Купівля товару</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                        </div>
                        <div class="modal-body">
                            <div><strong>Назва:</strong> <span id="modalProductName"></span></div>
                            <div><strong>Опис:</strong> <span id="modalProductDesc"></span></div>
                            <div><strong>Ціна:</strong> <span id="modalProductPrice"></span> грн</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                            <button type="button" class="btn btn-success">Підтвердити покупку</button>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
