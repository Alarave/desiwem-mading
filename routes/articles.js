const express = require('express');
const router = express.Router();
const db = require('../config/database');
const { requireAuth } = require('../middleware/auth');

router.get('/', (req, res) => {
  const { category_id, search } = req.query;
  let sql = `
    SELECT a.*, c.name as category_name, u.name as author_name 
    FROM articles a
    JOIN categories c ON a.category_id = c.id
    JOIN users u ON a.created_by = u.id
  `;
  const params = [];
  const conditions = [];

  if (category_id) {
    conditions.push("a.category_id = ?");
    params.push(category_id);
  }
  if (search) {
    conditions.push("(a.title LIKE ? OR a.content LIKE ?)");
    params.push(`%${search}%`, `%${search}%`);
  }

  if (conditions.length > 0) {
    sql += " WHERE " + conditions.join(" AND ");
  }
  sql += " ORDER BY a.created_at DESC";

  db.all(sql, params, (err, rows) => {
    if (err) return res.status(500).json({ error: "Gagal mengambil data artikel." });
    res.json(rows);
  });
});

router.get('/:id', (req, res) => {
  db.get(`
    SELECT a.*, c.name as category_name, u.name as author_name 
    FROM articles a
    JOIN categories c ON a.category_id = c.id
    JOIN users u ON a.created_by = u.id
    WHERE a.id = ?
  `, [req.params.id], (err, row) => {
    if (err) return res.status(500).json({ error: "Gagal mengambil detail artikel." });
    if (!row) return res.status(404).json({ error: "Artikel tidak ditemukan." });
    res.json(row);
  });
});

router.post('/', requireAuth, (req, res) => {
  const { category_id, title, content, image_url } = req.body;
  if (!category_id || !title || !content) {
    return res.status(400).json({ error: "Kategori, judul, dan isi artikel wajib diisi." });
  }

  db.run(
    'INSERT INTO articles (category_id, title, content, image_url, created_by) VALUES (?, ?, ?, ?, ?)',
    [category_id, title, content, image_url || null, req.session.userId],
    function(err) {
      if (err) return res.status(500).json({ error: "Gagal menyimpan artikel." });
      res.status(201).json({ id: this.lastID, category_id, title, content, image_url });
    }
  );
});

router.put('/:id', requireAuth, (req, res) => {
  const { category_id, title, content, image_url } = req.body;
  const { id } = req.params;

  if (!category_id || !title || !content) {
    return res.status(400).json({ error: "Kategori, judul, dan isi artikel wajib diisi." });
  }

  db.run(
    'UPDATE articles SET category_id = ?, title = ?, content = ?, image_url = ? WHERE id = ?',
    [category_id, title, content, image_url || null, id],
    function(err) {
      if (err) return res.status(500).json({ error: "Gagal memperbarui artikel." });
      if (this.changes === 0) return res.status(404).json({ error: "Artikel tidak ditemukan." });
      res.json({ id, category_id, title, content, image_url });
    }
  );
});

router.delete('/:id', requireAuth, (req, res) => {
  db.run('DELETE FROM articles WHERE id = ?', [req.params.id], function(err) {
    if (err) return res.status(500).json({ error: "Gagal menghapus artikel." });
    if (this.changes === 0) return res.status(404).json({ error: "Artikel tidak ditemukan." });
    res.json({ message: "Artikel berhasil dihapus." });
  });
});

module.exports = router;
