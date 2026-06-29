const express = require('express');
const router = express.Router();
const db = require('../config/database');

router.get('/metrics', (req, res) => {
  // Get count of articles, categories, and article counts grouped by category name
  const stats = {
    total_articles: 0,
    total_categories: 0,
    categories_chart: [],
    latest_articles: []
  };

  db.get('SELECT COUNT(*) as count FROM articles', (err, row) => {
    if (err) return res.status(500).json({ error: "Database error." });
    stats.total_articles = row.count || 0;

    db.get('SELECT COUNT(*) as count FROM categories', (err, row) => {
      if (err) return res.status(500).json({ error: "Database error." });
      stats.total_categories = row.count || 0;

      db.all(`
        SELECT c.name as category_name, COUNT(a.id) as article_count 
        FROM categories c
        LEFT JOIN articles a ON c.id = a.category_id
        GROUP BY c.id
      `, (err, rows) => {
        if (err) return res.status(500).json({ error: "Database error." });
        stats.categories_chart = rows || [];

        db.all(`
          SELECT a.title, a.created_at, c.name as category_name 
          FROM articles a
          JOIN categories c ON a.category_id = c.id
          ORDER BY a.created_at DESC LIMIT 5
        `, (err, rows) => {
          if (err) return res.status(500).json({ error: "Database error." });
          stats.latest_articles = rows || [];
          res.json(stats);
        });
      });
    });
  });
});

module.exports = router;
