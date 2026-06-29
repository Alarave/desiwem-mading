const API = {
  async get(url) {
    try {
      const res = await fetch(url);
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Terjadi kesalahan sistem.');
      return data;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  async post(url, body) {
    try {
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Terjadi kesalahan sistem.');
      return data;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  async put(url, body) {
    try {
      const res = await fetch(url, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(body)
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Terjadi kesalahan sistem.');
      return data;
    } catch (err) {
      console.error(err);
      throw err;
    }
  },

  async delete(url) {
    try {
      const res = await fetch(url, { method: 'DELETE' });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Terjadi kesalahan sistem.');
      return data;
    } catch (err) {
      console.error(err);
      throw err;
    }
  }
};
