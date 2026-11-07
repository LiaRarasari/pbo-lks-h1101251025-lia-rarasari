document.addEventListener('DOMContentLoaded', () => {
    loadCategories();
    loadBooks();
});

async function loadCategories() {
    const statusEl = document.getElementById('category-status');
    const tableEl = document.getElementById('category-table');
    const listEl = document.getElementById('category-list');

    try {
        const res = await api.get('/categories');
        if (res.status === 'success' && Array.isArray(res.data)) {
            listEl.innerHTML = '';
            res.data.forEach(cat => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${cat.id}</td>
                    <td><strong>${cat.nama}</strong></td>
                    <td>${cat.deskripsi || '-'}</td>
                `;
                listEl.appendChild(tr);
            });
            statusEl.style.display = 'none';
            tableEl.style.display = 'table';
        } else {
            statusEl.textContent = 'Gagal memuat kategori: Format data tidak sesuai.';
        }
    } catch (err) {
        statusEl.textContent = `Gagal terhubung ke API Kategori: ${err.message}`;
    }
}

async function loadBooks() {
    const statusEl = document.getElementById('book-status');
    const tableEl = document.getElementById('book-table');
    const listEl = document.getElementById('book-list');

    try {
        const res = await api.get('/books');
        if (res.status === 'success' && Array.isArray(res.data)) {
            listEl.innerHTML = '';
            res.data.forEach(book => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${book.id}</td>
                    <td>${book.judul}</td>
                    <td>${book.penulis || '-'}</td>
                    <td>${book.tahun || '-'}</td>
                    <td><span class="badge">${book.stok ?? 0}</span></td>
                `;
                listEl.appendChild(tr);
            });
            statusEl.style.display = 'none';
            tableEl.style.display = 'table';
        } else {
            statusEl.textContent = 'Gagal memuat buku: Format data tidak sesuai.';
        }
    } catch (err) {
        statusEl.textContent = `Gagal terhubung ke API Buku: ${err.message}`;
    }
}