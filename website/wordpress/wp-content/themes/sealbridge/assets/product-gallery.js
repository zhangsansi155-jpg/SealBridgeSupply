(function () {
  const galleries = document.querySelectorAll('[data-product-gallery]');

  galleries.forEach((gallery) => {
    const main = gallery.querySelector('[data-gallery-main]');
    const thumbs = gallery.querySelectorAll('[data-gallery-src]');

    thumbs.forEach((thumb) => {
      thumb.addEventListener('click', () => {
        if (!main) {
          return;
        }

        main.src = thumb.getAttribute('data-gallery-src');
        thumbs.forEach((item) => item.classList.remove('is-active'));
        thumb.classList.add('is-active');
      });
    });
  });

  const filter = document.querySelector('[data-product-filter]');
  const grid = document.querySelector('[data-product-grid]');
  const status = document.querySelector('[data-product-status]');
  const pagination = document.querySelector('[data-product-pagination]');

  if (filter && grid && window.sealbridgeProducts) {
    let activeFilter = 'all';

    const renderPagination = (page, maxPages) => {
      if (!pagination) {
        return;
      }

      pagination.innerHTML = '';

      if (maxPages <= 1) {
        return;
      }

      for (let index = 1; index <= maxPages; index += 1) {
        const button = document.createElement('button');
        button.type = 'button';
        button.textContent = index;
        button.className = index === page ? 'is-active' : '';
        button.addEventListener('click', () => loadProducts(activeFilter, index));
        pagination.appendChild(button);
      }
    };

    const loadProducts = async (value, page = 1) => {
      activeFilter = value;
      grid.setAttribute('aria-busy', 'true');

      if (status) {
        status.textContent = 'Loading products...';
      }

      const form = new FormData();
      form.append('action', 'sealbridge_load_products');
      form.append('filter', value);
      form.append('page', page);

      try {
        const response = await fetch(window.sealbridgeProducts.ajaxUrl, {
          method: 'POST',
          body: form,
          credentials: 'same-origin',
        });
        const payload = await response.json();

        if (!payload.success) {
          throw new Error('Product request failed');
        }

        grid.innerHTML = payload.data.html;
        renderPagination(payload.data.page, payload.data.maxPages);

        if (status) {
          const start = payload.data.total === 0 ? 0 : (payload.data.page - 1) * payload.data.perPage + 1;
          const end = Math.min(payload.data.page * payload.data.perPage, payload.data.total);
          status.textContent = `${start}-${end} of ${payload.data.total} products shown`;
        }
      } catch (error) {
        if (status) {
          status.textContent = 'Products could not load. Please refresh the page.';
        }
      } finally {
        grid.removeAttribute('aria-busy');
      }
    };

    filter.querySelectorAll('[data-filter]').forEach((button) => {
      button.addEventListener('click', () => {
        const value = button.getAttribute('data-filter');

        filter.querySelectorAll('[data-filter]').forEach((item) => item.classList.remove('is-active'));
        button.classList.add('is-active');
        loadProducts(value, 1);
      });
    });

    loadProducts('all', 1);
  }
})();
