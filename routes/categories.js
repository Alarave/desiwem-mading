const express = require('express');
const router = express.Router();
const db = require('../config/database');
const { requireAuth } = require('../middleware/auth');

router.get('/', (req, res) => {
  db.all('SELECT * FROM categories ORDER BY name ASC', [], (err, rows) => {
    if (err) return res.status(500).json({ error: "Gagal mengambil data kategori." });
    res.json(rows);
  });
});

router.post('/', requireAuth, (req, res) => {
  const { name, description } = req.body;
  if (!name) return res.status(400).json({ error: "Nama kategori wajib diisi." });

  db.run('INSERT INTO categories (name, description) VALUES (?, ?)', [name, description], function(err) {
    if (err) {
      if (err.message.includes('UNIQUE')) {
        return res.status(400).json({ error: "Kategori dengan nama tersebut sudah ada." });
      }
      return res.status(500).json({ error: "Gagal menyimpan kategori." });
    }
    res.status(201).json({ id: this.lastID, name, description });
  });
});

router.put('/:id', requireAuth, (req, res) => {
  const { name, description } = req.body;
  const { id } = req.params;
  if (!name) return res.status(400).json({ error: "Nama kategori wajib diisi." });

  db.run('UPDATE categories SET name = ?, description = ? WHERE id = ?', [name, description, id], function(err) {
    if (err) {
      if (err.message.includes('UNIQUE')) {
        return res.status(400).json({ error: "Kategori dengan nama tersebut sudah ada." });
      }
      return res.status(500).json({ error: "Gagal memperbarui kategori." });
    }
    if (this.changes === 0) return res.status(404).json({ error: "Kategori tidak ditemukan." });
    res.json({ id, name, description });
  });
});

router.delete('/:id', requireAuth, (req, res) => {
  const { id } = req.params;

  // Check if articles exist
  db.get('SELECT COUNT(*) as count FROM articles WHERE category_id = ?', [id], (err, row) => {
    if (err) return res.status(500).json({ error: "Gagal memeriksa relasi kategori." });
    if (row.count > 0) {
      return res.status(400).json({ error: "Kategori tidak dapat dihapus karena masih memiliki artikel terkait." });
    }

    db.run('DELETE FROM categories WHERE id = ?', [id], function(err) {
      if (err) return res.status(500).json({ error: "Gagal menghapus kategori." });
      res.json({ message: "Kategori berhasil dihapus." });
    });
  });
});

module.exports = router;
