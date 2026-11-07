document.addEventListener('DOMContentLoaded', () => {
    loadDashboardStats();
    loadBooks();
    loadCategories();

    document.getElementById('book-form').addEventListener('submit', handleBookSubmit);
    document.getElementById('category-form').addEventListener('submit', handleCategorySubmit);
});

function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

    if (tabName === 'books') {
        document.querySelector("button[onclick=\"switchTab('books')\"]").classList.add('active');
        document.getElementById('tab-books').classList.add('active');
    } else {
        document.querySelector("button[onclick=\"switchTab('categories')\"]").classList.add('active');
        document.getElementById('tab-categories').classList.add('active');
    }
}

function showAlert(message, type = 'success') {
    const alertBox = document.getElementById('alert-box');
    alertBox.className = `alert alert-${type}`;
    alertBox.textContent = message;
    alertBox.style.display = 'block';
    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 3500);
}

async function loadDashboardStats() {
    try {
        const [booksRes, categoriesRes] = await Promise.all([
            api.get('/books'),
            api.get('/categories')
        ]);

        if (booksRes.status === 'success' && Array.isArray(booksRes.data)) {
            const books = booksRes.data;
            document.getElementById('stat-total-books').textContent = books.length;
            const totalStock = books.reduce((acc, curr) => acc + (parseInt(curr.stok) || 0), 0);
            document.getElementById('stat-total-stock').textContent = totalStock;
        }

        if (categoriesRes.status === 'success' && Array.isArray(categoriesRes.data)) {
            document.getElementById('stat-total-categories').textContent = categoriesRes.data.length;
        }
    } catch (err) {
        console.error('Gagal memuat statistik:', err);
    }
}

async function loadBooks() {
    const listEl = document.getElementById('book-list');
    try {
        const res = await api.get('/books');
        if (res.status === 'success' && Array.isArray(res.data)) {
            listEl.innerHTML = '';
            if (res.data.length === 0) {
                listEl.innerHTML = `<tr><td colspan="6" style="text-align: center;">Belum ada data buku.</td></tr>`;
                return;
            }

            res.data.forEach(book => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${book.id}</td>
                    <td><strong>${book.judul}</strong></td>
                    <td>${book.penulis || '-'}</td>
                    <td>${book.tahun || '-'}</td>
                    <td><span class="badge">${book.stok ?? 0}</span></td>
                    <td>
                        <button class="btn btn-danger" onclick="deleteBook(${book.id})">Hapus</button>
                    </td>
                `;
                listEl.appendChild(tr);
            });
        }
    } catch (err) {
        listEl.innerHTML = `<tr><td colspan="6" style="color: red; text-align: center;">Gagal memuat buku: ${err.message}</td></tr>`;
    }
}

async function handleBookSubmit(e) {
    e.preventDefault();

    const payload = {
        judul: document.getElementById('judul').value.trim(),
        penulis: document.getElementById('penulis').value.trim(),
        tahun: parseInt(document.getElementById('tahun').value),
        stok: parseInt(document.getElementById('stok').value)
    };

    try {
        const res = await api.post('/books', payload);
        if (res.status === 'success') {
            showAlert('Buku berhasil ditambahkan!', 'success');
            document.getElementById('book-form').reset();
            loadBooks();
            loadDashboardStats();
        } else {
            showAlert(res.message || 'Gagal menyimpan buku', 'error');
        }
    } catch (err) {
        showAlert(`Error: ${err.message}`, 'error');
    }
}

async function deleteBook(id) {
    if (!confirm(`Hapus buku ID #${id}?`)) return;

    try {
        const res = await api.delete(`/books/${id}`);
        if (res.status === 'success') {
            showAlert('Buku berhasil dihapus!', 'success');
            loadBooks();
            loadDashboardStats();
        } else {
            showAlert(res.message || 'Gagal menghapus buku', 'error');
        }
    } catch (err) {
        showAlert(`Error: ${err.message}`, 'error');
    }
}

async function loadCategories() {
    const listEl = document.getElementById('category-list');
    try {
        const res = await api.get('/categories');
        if (res.status === 'success' && Array.isArray(res.data)) {
            listEl.innerHTML = '';
            if (res.data.length === 0) {
                listEl.innerHTML = `<tr><td colspan="4" style="text-align: center;">Belum ada data kategori.</td></tr>`;
                return;
            }

            res.data.forEach(cat => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${cat.id}</td>
                    <td><strong>${cat.nama}</strong></td>
                    <td>${cat.deskripsi || '-'}</td>
                    <td>
                        <button class="btn btn-danger" onclick="deleteCategory(${cat.id})">Hapus</button>
                    </td>
                `;
                listEl.appendChild(tr);
            });
        }
    } catch (err) {
        listEl.innerHTML = `<tr><td colspan="4" style="color: red; text-align: center;">Gagal memuat kategori: ${err.message}</td></tr>`;
    }
}

async function handleCategorySubmit(e) {
    e.preventDefault();

    const payload = {
        nama: document.getElementById('cat-nama').value.trim(),
        deskripsi: document.getElementById('cat-deskripsi').value.trim()
    };

    try {
        const res = await api.post('/categories', payload);
        if (res.status === 'success') {
            showAlert('Kategori berhasil ditambahkan!', 'success');
            document.getElementById('category-form').reset();
            loadCategories();
            loadDashboardStats();
        } else {
            showAlert(res.message || 'Gagal menyimpan kategori', 'error');
        }
    } catch (err) {
        showAlert(`Error: ${err.message}`, 'error');
    }
}

async function deleteCategory(id) {
    if (!confirm(`Hapus kategori ID #${id}?`)) return;

    try {
        const res = await api.delete(`/categories/${id}`);
        if (res.status === 'success') {
            showAlert('Kategori berhasil dihapus!', 'success');
            loadCategories();
            loadDashboardStats();
        } else {
            showAlert(res.message || 'Gagal menghapus kategori', 'error');
        }
    } catch (err) {
        showAlert(`Error: ${err.message}`, 'error');
    }
}