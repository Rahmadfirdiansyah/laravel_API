<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD API dengan Axios</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        .card {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            background: #f9f9f9;
        }

        .form-container {
            background: #eef;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            color: white;
        }

        .btn-primary {
            background: #007bff;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-warning {
            background: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background: #e0a800;
        }

        .action-buttons {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <h1>Manajemen Produk</h1>

    <div class="form-container">
        <h3 id="form-title">Tambah Produk Baru</h3>
        <form id="form-produk" onsubmit="simpanProduk(event)">
            <input type="hidden" id="produk_id">

            <label>Nama Produk:</label>
            <input type="text" id="name" required>

            <label>Harga:</label>
            <input type="number" id="price" required>

            <label>Kategori (ID):</label>
            <select id="category_id" required>
                <option value="1">Elektronik (1)</option>
                <option value="2">Minuman (2)</option>
                <option value="3">Alat (3)</option>
            </select>

            <label>Deskripsi:</label>
            <textarea id="description" rows="3"></textarea>

            <button type="submit" class="btn-primary" id="btn-submit">Simpan Produk</button>
            <button type="button" onclick="resetForm()" style="background: #6c757d;">Batal</button>
        </form>
    </div>

    <hr>

    <h2>Daftar Produk</h2>
    <button class="btn-primary" onclick="ambilDataProduk()">Refresh Data</button>
    <div id="tempat-produk" style="margin-top: 15px;">
        <p>Memuat data...</p>
    </div>

    <script>
        // URL dasar API kita
        const baseURL = 'http://127.0.0.1:8000/api/products';

        // Langsung panggil data saat halaman pertama kali dibuka
        window.onload = function() {
            ambilDataProduk();
        };

        // 1. FUNGSI READ (GET)
        function ambilDataProduk() {
            const wadah = document.getElementById('tempat-produk');
            wadah.innerHTML = "<i>Sedang mengambil data...</i>";

            axios.get(baseURL)
                .then(function(response) {
                    const hasil = response.data.data;
                    wadah.innerHTML = "";

                    if (hasil.length === 0) {
                        wadah.innerHTML = "<p>Data produk masih kosong.</p>";
                        return;
                    }

                    hasil.forEach(function(produk) {
                        const namaKategori = produk.category ? produk.category.name : 'Tanpa Kategori';
                        // Agar tidak error saat deskripsi null, berikan string kosong
                        const deskripsiAsli = produk.description || '';

                        wadah.innerHTML += `
                            <div class="card">
                                <h3>${produk.name}</h3>
                                <p><strong>Kategori:</strong> ${namaKategori}</p>
                                <p><strong>Harga:</strong> Rp ${produk.price}</p>
                                <p><em>${deskripsiAsli}</em></p>
                                
                                <div class="action-buttons">
                                    <button class="btn-warning" onclick="siapkanEdit(${produk.id}, '${produk.name}', ${produk.price}, ${produk.category_id}, '${deskripsiAsli}')">Edit</button>
                                    
                                    <button class="btn-danger" onclick="hapusProduk(${produk.id})">Hapus</button>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(function(error) {
                    console.error("Terjadi Error:", error);
                    wadah.innerHTML = "<p style='color:red;'>Gagal mengambil data.</p>";
                });
        }

        // 2. FUNGSI CREATE (POST) & UPDATE (PUT)
        function simpanProduk(event) {
            event.preventDefault(); // Mencegah halaman refresh saat form disubmit

            // Ambil nilai dari form
            const id = document.getElementById('produk_id').value;
            const dataInput = {
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                category_id: document.getElementById('category_id').value,
                description: document.getElementById('description').value
            };

            if (id) {
                // JIKA ADA ID: Berarti kita sedang mode UPDATE (PUT)
                axios.put(`${baseURL}/${id}`, dataInput)
                    .then(response => {
                        alert('Produk berhasil diperbarui!');
                        resetForm();
                        ambilDataProduk(); // Refresh tabel
                    })
                    .catch(error => {
                        alert('Gagal mengupdate: ' + JSON.stringify(error.response.data.errors));
                    });
            } else {
                // JIKA TIDAK ADA ID: Berarti kita sedang mode CREATE (POST)
                axios.post(baseURL, dataInput)
                    .then(response => {
                        alert('Produk berhasil ditambahkan!');
                        resetForm();
                        ambilDataProduk(); // Refresh tabel
                    })
                    .catch(error => {
                        alert('Gagal menyimpan: ' + JSON.stringify(error.response.data.errors));
                    });
            }
        }

        // 3. FUNGSI DELETE
        function hapusProduk(id) {
            const konfirmasi = confirm("Apakah Anda yakin ingin menghapus produk ini?");
            if (konfirmasi) {
                axios.delete(`${baseURL}/${id}`)
                    .then(response => {
                        alert('Produk berhasil dihapus!');
                        ambilDataProduk(); // Refresh tabel setelah dihapus
                    })
                    .catch(error => {
                        alert('Gagal menghapus produk.');
                        console.error(error);
                    });
            }
        }

        // FUNGSI PENDUKUNG: Memasukkan data ke form saat tombol Edit diklik
        function siapkanEdit(id, name, price, category_id, description) {
            document.getElementById('form-title').innerText = "Edit Produk";
            document.getElementById('btn-submit').innerText = "Update Produk";

            document.getElementById('produk_id').value = id;
            document.getElementById('name').value = name;
            document.getElementById('price').value = price;
            document.getElementById('category_id').value = category_id;
            document.getElementById('description').value = description;

            window.scrollTo(0, 0); // Scroll ke atas (ke arah form)
        }

        // FUNGSI PENDUKUNG: Mengembalikan form ke mode Tambah Data
        function resetForm() {
            document.getElementById('form-produk').reset();
            document.getElementById('produk_id').value = "";
            document.getElementById('form-title').innerText = "Tambah Produk Baru";
            document.getElementById('btn-submit').innerText = "Simpan Produk";
        }
    </script>
</body>

</html>
