const sqlite3 = require('sqlite3').verbose();
const bcrypt = require('bcryptjs');
const path = require('path');

const dbPath = path.join(__dirname, 'database.sqlite');

async function setup() {
  const hashedPassword = await bcrypt.hash('admin123', 10);
  const db = new sqlite3.Database(dbPath);

  db.serialize(() => {
    // Drop old tables if they exist to start clean
    db.run("DROP TABLE IF EXISTS transactions");
    db.run("DROP TABLE IF EXISTS items");
    db.run("DROP TABLE IF EXISTS articles");
    db.run("DROP TABLE IF EXISTS categories");
    db.run("DROP TABLE IF EXISTS users");

    // Create tables
    db.run(`CREATE TABLE users (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      username TEXT UNIQUE NOT NULL,
      password TEXT NOT NULL,
      name TEXT NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )`);

    db.run(`CREATE TABLE categories (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT UNIQUE NOT NULL,
      description TEXT,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )`);

    db.run(`CREATE TABLE articles (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      category_id INTEGER NOT NULL,
      title TEXT NOT NULL,
      content TEXT NOT NULL,
      image_url TEXT,
      created_by INTEGER NOT NULL,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
      FOREIGN KEY (created_by) REFERENCES users(id)
    )`);

    // Seed default admin
    db.run(
      `INSERT INTO users (username, password, name) VALUES (?, ?, ?)`,
      ['admin', hashedPassword, 'Admin Kampus GUNDAR']
    );

    // Seed default categories
    db.run(`INSERT INTO categories (name, description) VALUES ('Info Sekolah', 'Pengumuman resmi dan berita seputar kampus Sekolah Tinggi GUNDAR.')`);
    db.run(`INSERT INTO categories (name, description) VALUES ('Seni', 'Wadah tulisan kreatif, puisi, cerpen, ilustrasi, dan karya seni mahasiswa.')`);
    db.run(`INSERT INTO categories (name, description) VALUES ('Ilmiah', 'Artikel edukatif, karya ilmiah populer, essay, dan tips akademik.')`);

    console.log("Database initialized and GUNDAR seed data inserted successfully!");
    db.close();
  });
}

setup().catch(err => {
  console.error("Setup failed:", err);
});
