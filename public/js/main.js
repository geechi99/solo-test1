(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        const productsContainer = document.getElementById('productsContainer');
        const categoriesList = document.getElementById('categoriesList');
        const sortSelect = document.getElementById('sortSelect');
        const buyModalBody = document.getElementById('buyModalBody');

        if (!productsContainer) {
            console.warn('productsContainer not found in DOM. Exiting main.js.');
            return;
        }

        let _modalInstance = null;
        function getModalInstance() {
            if (_modalInstance) return _modalInstance;
            const el = document.getElementById('buyModal');
            if (!el) return null;
            if (typeof bootstrap === 'undefined') {
                console.warn('Bootstrap is not loaded yet.');
                return null;
            }
            _modalInstance = new bootstrap.Modal(el);
            return _modalInstance;
        }

        let currentCategoryId = '';
        let currentSort = '';

        function escapeHtml(s){
            return String(s||'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
        }

        function buildProductCard(product){
            const img = 'https://content2.rozetka.com.ua/goods/images/big/523974716.jpg';
            return `
              <div class="product-item">
                <div class="product-card">
                  <div class="product-thumb">
                    <img src="${img}" alt="${escapeHtml(product.name)}">
                  </div>
                  <div class="product-title">${escapeHtml(product.name)}</div>
                  <div class="product-desc">${escapeHtml(product.description || '')}</div>
                  <div class="product-price">${Number(product.price).toFixed(2)} грн</div>
                  <div class="mt-2">
                    <button class="btn btn-sm btn-warning buyBtn" data-id="${product.id}">Купить</button>
                  </div>
                </div>
              </div>
            `;
        }

        function renderProducts(items){
            if (!items || items.length === 0){
                productsContainer.innerHTML = '<div class="text-muted">Товары не найдены.</div>';
                return;
            }
            const frag = document.createDocumentFragment();
            const tmp = document.createElement('div');
            items.forEach(p => {
                tmp.innerHTML = buildProductCard(p);
                Array.from(tmp.children).forEach(ch => frag.appendChild(ch));
                tmp.innerHTML = '';
            });
            productsContainer.innerHTML = '';
            productsContainer.appendChild(frag);
        }

        function showLoading(){
            productsContainer.innerHTML = '<div class="text-muted">Загрузка...</div>';
        }

        function apiFetch(params = {}) {
            const sp = new URLSearchParams();
            if (params.category_id) sp.append('category_id', params.category_id);
            if (params.sort) sp.append('sort', params.sort);
            if (params.product_id) sp.append('product_id', params.product_id);

            const url = '/api/products' + (sp.toString() ? '?' + sp.toString() : '');
            return fetch(url, { credentials: 'same-origin' })
                .then(r => {
                    if (!r.ok) throw new Error('Network error: ' + r.status);
                    return r.json();
                });
        }

        function loadProducts(){
            showLoading();
            apiFetch({ category_id: currentCategoryId, sort: currentSort })
                .then(data => renderProducts(data))
                .catch(err => {
                    console.error(err);
                    productsContainer.innerHTML = '<div class="text-danger">Error loading products</div>';
                });
        }

        function openBuyModalWith(product){
            buyModalBody && (buyModalBody.innerHTML = `
              <div class="row">
                <div class="col-md-5">
                  <img src="https://content2.rozetka.com.ua/goods/images/big/523974716.jpg" alt="${escapeHtml(product.name)}" class="img-fluid">
                </div>
                <div class="col-md-7">
                  <h4>${escapeHtml(product.name)}</h4>
                  <p>${escapeHtml(product.description || '')}</p>
                  <p><strong>Price: ${Number(product.price).toFixed(2)} грн</strong></p>
                </div>
              </div>
            `);

            const modal = getModalInstance();
            if (modal) {
                modal.show();
            } else {
                alert(`${product.name}\n\n${product.description || ''}\n\nЦена: ${Number(product.price).toFixed(2)} грн`);
            }
        }

        if (categoriesList) {
            categoriesList.addEventListener('click', function(e){
                const li = e.target.closest('.category-item');
                if (!li) return;
                e.preventDefault();
                document.querySelectorAll('.category-item').forEach(x => x.classList.remove('active'));
                li.classList.add('active');
                currentCategoryId = li.getAttribute('data-id') || '';
                loadProducts();
            });
        }

        if (sortSelect) {
            sortSelect.addEventListener('change', function(){
                currentSort = this.value;
                loadProducts();
            });
        }

        document.body.addEventListener('click', function(e){
            const btn = e.target.closest('.buyBtn');
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            if (!id) return;
            apiFetch({ product_id: id })
                .then(arr => {
                    const product = arr && arr[0] ? arr[0] : null;
                    if (!product) return alert('Товар не найден');
                    openBuyModalWith(product);
                })
                .catch(err => {
                    console.error('Product load error', err);
                    alert('Ошибка при загрузке товара');
                });
        });

        loadProducts();
    });
})();
