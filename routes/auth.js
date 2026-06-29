const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const db = require('../config/database');

router.post('/login', (req, res) => {
  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ error: "Username dan password wajib diisi." });
  }

  db.get('SELECT * FROM users WHERE username = ?', [username], async (err, user) => {
    if (err) return res.status(500).json({ error: "Database error." });
    if (!user) return res.status(401).json({ error: "Username atau password salah." });

    const match = await bcrypt.compare(password, user.password);
    if (!match) return res.status(401).json({ error: "Username atau password salah." });

    req.session.userId = user.id;
    req.session.username = user.username;
    req.session.name = user.name;

    return res.json({ message: "Login berhasil", user: { id: user.id, username: user.username, name: user.name } });
  });
});

router.post('/logout', (req, res) => {
  req.session.destroy(err => {
    if (err) return res.status(500).json({ error: "Gagal melakukan logout." });
    res.clearCookie('connect.sid');
    return res.json({ message: "Logout berhasil" });
  });
});

router.get('/me', (req, res) => {
  if (req.session && req.session.userId) {
    return res.json({ loggedIn: true, user: { id: req.session.userId, username: req.session.username, name: req.session.name } });
  }
  return res.json({ loggedIn: false });
});

module.exports = router;
