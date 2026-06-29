module.exports = {
  requireAuth: (req, res, next) => {
    if (req.session && req.session.userId) {
      return next();
    }
    return res.status(401).json({ error: "Sesi tidak valid atau telah berakhir. Silakan login kembali." });
  }
};
